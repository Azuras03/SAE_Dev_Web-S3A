<?php

namespace netvod\auth;

use netvod\exception\AlreadyStoredException;
use netvod\exception\PasswordStrenghException;
use netvod\db\ConnectionFactory;
use netvod\exception\InvalidPropertyNameException;
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

    /**
     * Method that register a new user in the database if he doesn't exist
     * @throws InvalidPropertyNameException
     * @throws AlreadyStoredException
     * @throws PasswordStrenghException
     */
    public static function register($email, $password)
    {
        if (!self::checkPasswordStrengh($password, 10))
            throw new PasswordStrenghException();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new InvalidPropertyNameException("Email invalide");

        $db = ConnectionFactory::makeConnection();
        $q1 = "SELECT email FROM user WHERE email = ?";
        $st = $db->prepare($q1);

        $st->execute([$email]);

        if ($st->fetch(\PDO::FETCH_ASSOC))
            throw new AlreadyStoredException("Utilisateur déjà dans la base.");


        $hash = password_hash($password, PASSWORD_DEFAULT);

        $q2 = "INSERT INTO user (email, passwd, role) VALUES (?, ?, ?)";
        $st2 = $db->prepare($q2);
        $st2->execute([$email, $hash, 1]);

    }

    /**
     * Check the strengh of the password sent
     * @param string $pass
     * @param int $long
     * @return bool
     */
    public static function checkPasswordStrengh(string $pass, int $long) : bool
    {
        return strlen($pass) >= $long;
    }

    public static function generateToken(string $email): string
    {
        $bytes = random_bytes(10);
        return bin2hex($bytes);
    }

    public static function userByToken(string $token)
    {

    }


}