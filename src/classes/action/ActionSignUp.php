<?php

namespace netvod\action;

use netvod\auth\Authentification;
use netvod\exception\AlreadyStoredException;
use netvod\exception\InvalidPropertyNameException;
use netvod\exception\PasswordStrenghException;

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
                        <th><label>Confirmer Mot de passe :</label></th>
                    </tr>
                    <tr>                   
                        <th><input type="email" name="email" placeholder="<email>"><br></th>
                        <th><input type="password" name="password" placeholder="<password>"><br></th>
                        <th><input type="password" name="confirm" placeholder="<password>"><br></th>
                        <th><button type="submit">S'inscrire</button></th>
                    </tr>
                </table>
            </form>
            HTML;
        } else {
            $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $pwd = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            $confirm = filter_var($_POST['confirm'], FILTER_SANITIZE_SPECIAL_CHARS);

            if (!($pwd == $confirm)){
                $html .= "<p>Les mots de passes ne sont pas identiques</p>";
            } else {
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
        }
        return $html;
    }
}