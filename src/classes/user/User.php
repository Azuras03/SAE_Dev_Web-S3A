<?php
namespace netvod\user;

use iutnc\deefy\audio\exception\NonEditablePropertyException;
use iutnc\deefy\audio\lists\PlaylistList;
use iutnc\deefy\db\ConnectionFactory;
use PDO;

class User
{

    public string $email;
    public string $password;
    public string $role;

    public function __construct(string $email, string $password, string $role)
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function __set(string $at, mixed $value): void
    {
        if (property_exists($this, $at)) {
            $this->$at = $value;
        } else {
            throw new NonEditablePropertyException($at);
        }
    }
    public function getPlaylists(): array
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('select p.id, nom from `playlist` p, `user2playlist` u2p, `User` u where p.id = u2p.id_pl and u2p.id_user = u.id and u.email = ?');
        $var = $this->email;
        $stmt->bindParam(1, $var);
        $stmt->execute();
        $userPlaylists = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $playlist = new PlaylistList($row['nom']);
            $playlist->id = $row['id'];
            array_push($userPlaylists, $playlist);
        }
        return $userPlaylists;
    }
}