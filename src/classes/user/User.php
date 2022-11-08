<?php

namespace netvod\user;

use iutnc\deefy\exception\AlreadyStoredException;
use netvod\auth\Authentification;
use netvod\db\ConnectionFactory;
use netvod\exception\InvalidPropertyNameException;
use netvod\exception\InvalidPropertyValueException;
use netvod\exception\NonEditablePropertyException;

class User
{

    public string $id;
    public string $email;
    public string $password;
    public array $infos = [];

    public function __construct(string $id, string $email, string $password, array $infos = [])
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->infos = $infos;
    }

    public static function userById(string $email){
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM user WHERE email = ?');
        if ($stmt->execute([$email])) {
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($user) {
                $utilisateur = new User($user['id'], $user['email'], $user['passwd']);
                return $utilisateur;
            }
        }
        return null;
    }

    /**
     * @throws NonEditablePropertyException
     */
    public function __set(string $at, mixed $value): void
    {
        if (property_exists($this, $at)) {
            $this->$at = $value;
        } else {
            throw new NonEditablePropertyException($at);
        }
    }

    /**
     * @throws InvalidPropertyValueException
     */
    public function isAddEpisodeInProgress (string $epId) : bool
    {
        /*
        if (!isset($_SESSION["episode"])) throw new InvalidPropertyValueException("\$_SESSION[\"episode\"]", "Inexistant");
        $ep = unserialize($_SESSION["episode"]);

*/
        $db = ConnectionFactory::makeConnection();
        $q = "SELECT id_user, id_episode FROM `userprogressepisode` 
                WHERE id_user = ?
                AND id_episode = ?";
        $st = $db->prepare($q);
        $st->bindParam(1, $this->id);
        $st->bindParam(2, $epId);
        $st->execute();

        echo $this->id . "<br>" . $ep;
        if ($st->fetch(\PDO::FETCH_ASSOC)) return false;
        return true;
    }

    public function addEpisodeInProgress (string $id)
    {
        if (!$this->isAddEpisodeInProgress($id)) return;

        $db = ConnectionFactory::makeConnection();
        $idepisode = $db->prepare("SELECT id FROM episode WHERE serie_id = ? AND numero = ?;");
        $idepisode->bindParam(1, $_GET["serie"]);
        $idepisode->bindParam(2, $_GET["episode"]);
        $idepisode->execute();
        $episode = $idepisode->fetch(\PDO::FETCH_ASSOC);
        $id = $episode["id"];

        $db = ConnectionFactory::makeConnection();
        $q = "INSERT INTO `userprogressepisode` (id_user, id_episode) VALUES (?, ?)";
        $st = $db->prepare($q);
        $st->bindParam(1, $this->id);
        $st->bindParam(2, $id);
        $st->execute();
    }
}