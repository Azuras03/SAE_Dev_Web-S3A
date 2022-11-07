<?php

namespace netvod\user;

use iutnc\deefy\exception\AlreadyStoredException;
use netvod\auth\Authentification;
use netvod\db\ConnectionFactory;
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

    public function __set(string $at, mixed $value): void
    {
        if (property_exists($this, $at)) {
            $this->$at = $value;
        } else {
            throw new NonEditablePropertyException($at);
        }
    }

    public function isAddEpisodeInProgress () : bool
    {
        if (!isset($_SESSION["episode"])) return false;
        $episode = unserialize($_SESSION["episode"]);

        $db = ConnectionFactory::makeConnection();
        $q = "SELECT id_user, id_episode FROM `userprogressepisode` 
                WHERE id_user = ?
                AND id_episode = ?;";
        $st = $db->prepare($q);
        $st->bindParam(1, $this->id);
        $st->bindParam(2, $episode->id);
        $st->execute();

        if ($st->fetch(\PDO::FETCH_ASSOC)) return false;
        return true;
    }

    public function addEpisodeInProgress ()
    {
        if (!$this->isAddEpisodeInProgress()) return;

        $episode = unserialize($_SESSION["episode"]);

        $db = ConnectionFactory::makeConnection();
        $q = "INSERT INTO `userprogressepisode` (id_user, id_episode) VALUES (?, ?)";
        $st = $db->prepare($q);
        $st->bindParam(1, $this->id);
        $st->bindParam(2, $episode->id);
        $st->execute();
    }
}