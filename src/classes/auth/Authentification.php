<?php

namespace netvod\auth;

use netvod\db\ConnectionFactory;
use netvod\user\User;

class Authentification
{

    public static function authenticate($email, $psswrd)
    {
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM user WHERE email = ?');

        if ($stmt->execute([$email])) {
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($user && password_verify($psswrd, $user['passwd'])) {
                $utilisateur = new User($user['id'], $user['email'], $user['passwd']);
                $_SESSION['user'] = serialize($utilisateur);
                return $utilisateur;
            }
        }
        return null;
    }


}