<?php

namespace netvod\auth;

class Auth
{

    public static function authenticate($email, $psswrd)
    {
        $db = \iutnc\deefy\db\ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');

        if ($stmt->execute([$email])) {
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($user && password_verify($psswrd, $user['passwd'])) {
                $utilisateur = new \iutnc\deefy\user\User($user['email'], $user['passwd'], $user['role']);
                $_SESSION['user'] = serialize($utilisateur);
                return $utilisateur;
            }
        }
        return null;
    }

    public static function register($email, $psswrd)
    {
        $db = \iutnc\deefy\db\ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT count(email) FROM User WHERE email = ?');
        $stmt->execute([$email]);
        $count = $stmt->fetch(\PDO::FETCH_ASSOC);

        $existemail = ($count['count(email)'] > 0);
        $length = (strlen($psswrd) < 10);

        $hash = password_hash($psswrd, PASSWORD_DEFAULT, ['cost' => 12]);

        if (!$existemail && !$length) {
            $stmt = $db->prepare('INSERT INTO User (email, passwd, role) VALUES (?, ?, ?)');
            $stmt->execute([$email, $hash, 1]);
            return true;
        }
        return false;
    }

    public static function access($idpl)
    {
        $user = unserialize($_SESSION['user']);
        $db = \iutnc\deefy\db\ConnectionFactory::makeConnection();

        //Recup id utilisateur
        $stmt = $db->prepare('SELECT id FROM User WHERE email  = ?');
        $stmt->execute([$user->email]);
        $response = $stmt->fetch(\PDO::FETCH_ASSOC);
        $iduser = $response['id'];

        //Recup playlist de l'utilisateur
        $stmt2 = $db->prepare('SELECT id_pl FROM user2playlist WHERE id_user = ?');
        $stmt2->execute([$iduser]);
        $response2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
        $idpluser = [];
        foreach ($response2 as $row) {
            array_push($idpluser, $row['id_pl']);
        }

        //Verification
        if (in_array($idpl, $idpluser) || $user->role == 100) {
            return true;
        } else {
            return false;
        }
    }

}