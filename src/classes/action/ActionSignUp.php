<?php

namespace netvod\action;

use iutnc\deefy\exception\AlreadyStoredException;
use iutnc\deefy\exception\PasswordStrenghException;
use netvod\auth\Authentification;
use netvod\exception\InvalidPropertyNameException;

class ActionSignUp extends Action
{

    public function execute(): string
    {
        $html = "<h3>Inscription</h3>";
        if ($this->http_method == 'GET') {
            $html .=  <<<HTML
            <form method="post" action="?action=signup">
                <table class="miseenforme">
                    <tr>
                        <th><label>Email : </label></th>
                        <th><label>Mot de passe : </label></th>
                    </tr>
                    <tr>                   
                        <th><input type="email" name="email" placeholder="<email>"><br></th>
                        <th><input type="password" name="password" placeholder="<password>"><br></th>
                        <th><button type="submit">S'inscrire</button></th>
                    </tr>
                </table>
            </form>
            HTML;
        } else {
            $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $pwd = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);

            try {
                Authentification::register($mail, $pwd);

                $html .= "<p><strong>" . $mail .
                    " a été enregistré. Vous pouvez maintenant vous connecter</strong></p>";
            }
            catch (PasswordStrenghException | AlreadyStoredException | InvalidPropertyNameException $e)
            {
                $html .= $e->getMessage();
            }
        }
        return $html;
    }
}