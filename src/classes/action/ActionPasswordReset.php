<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;
use netvod\user\User;

class ActionPasswordReset extends Action
{

    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            return <<<HTML
            <h3>Changement de mot de passe</h3>
            <form method="post">
                <table class="miseenforme">
                    <tr>
                        <th><label>Nouveau mot de passe : </label></th>
                        <th><label>Confirmer mot de passe : </label></th>
                    </tr>
                    <tr>                   
                        <th><input type="password" name="password" placeholder="<password>"><br></th>
                        <th><input type="password" name="confirm" placeholder="<password>"><br></th>
                        <th><button type="submit">Changer</button></th>
                    </tr>
                </table>
            </form>
            HTML;
        } else {
            $token = filter_var($_GET['token'], FILTER_SANITIZE_STRING);
            $pwd = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            $confirm = filter_var($_POST['confirm'], FILTER_SANITIZE_SPECIAL_CHARS);
            if (!($pwd == $confirm)) {
                return "<p>Les mots de passes ne sont pas identiques</p>";
            } else {
                $db = ConnectionFactory::makeConnection();
                $chiffre = password_hash($pwd, PASSWORD_DEFAULT);

                $stmt1 = $db->prepare("UPDATE user SET passwd = ? WHERE resetpwd_token = ?");
                $stmt1->execute([$chiffre, $token]);

                $stmt2 = $db->prepare("SELECT email FROM user WHERE resetpwd_token = ?");
                $stmt2->execute([$token]);

                $stmt3 = $db->prepare("UPDATE user SET resetpwd_token = NULL WHERE resetpwd_token = ?");
                $stmt3->execute([$token]);

                return <<<HTML
                    <head>
                        <meta http-equiv="refresh" content="3; url=Index.php">
                    </head>
                    <p>Mot de passe changé</p>
                    <p><strong>Vous allez être redirigé vers l'accueil</strong></p>
                HTML;

            }
        }
    }
}