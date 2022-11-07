<?php

namespace netvod\action;

use netvod\auth\Authentification;

class ActionSignIn extends Action
{

    public function execute(): string
    {
        if ($this->http_method == 'GET') {
            return <<<HTML
            <form method="post" action="?action=signin">
            <label>Email : </label>
            <input type="email" name="email" placeholder="<email>"><br>
            <label>Password : </label>
            <input type="password" name="password" placeholder="<password>"><br>
            <button type="submit">Se Connecter</button>
            </form>
            HTML;
        } else {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            $user = Authentification::authenticate($email, $password);
            if ($user) {
                return <<<HTML
                    $email est connecté au service NetVOD
                HTML;

            } else {
                return <<<HTML
                    Une erreur s'est produite : Identifiants invalides
                HTML;
            }
        }
    }
}