<?php

namespace netvod\action;

use netvod\auth\Authentification;
use netvod\db\ConnectionFactory;

class ActionEmailPasswordReset extends Action
{
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET"){
            return <<<HTML
            <h3>Mot de passe oublié ?</h3>
            <form method="post">
                <table class="miseenforme">
                    <tr>
                        <th><label>Email : </label></th>
                    </tr>
                    <tr>
                        <th><input type="email" name="email" placeholder="<email>"><br></th>
                        <th><button type="submit">Envoyer un email de réinitialisation</button></th>
                    </tr>
                </table>
            </form>
            HTML;
        } else {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $token = Authentification::generateToken($email);
            $db = ConnectionFactory::makeConnection();
            $stmt = $db->prepare("UPDATE user SET resetpwd_token = ? WHERE email = ?");
            if ($stmt->execute([$token, $email])) {
                $html = <<<HTML
                    <p style="text-align: center">Vous avez reçu un ✨ EMAIL ✨ (et oui c'est possible)</p><br>
                    <div class="lettre">
                    <table>
                        <tr>
                            <th id="email">From :</th>
                            <th id="emailCorrespondants">contact@netvod.tv</th>
                        </tr>
                        <tr>
                            <th id="email">To :</th>
                            <th id="emailCorrespondants">$email</th>
                        </tr>
                        <tr>
                            <th id="email">Subject : </th>
                            <th id="emailCorrespondants">Réinitialisation de votre mot de passe</th>
                        </tr>
                    </table>
                    <p id="emailCorrespondants" style="padding: 20px 20px 20px 20px">Bonjour, vous recevez cet email car vous avez demandé à changer de mot de passe<br>
                    Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email<br>
                    Si vous êtes à l'origine de cette demande, cliquez sur le lien ci-dessous pour changer votre mot de passe<br>
                    <a style="color: white;" href="?action=passwordreset&token=$token">Changer mon mot de passe</a></p><br>
                    Thomas, de l'équipe NetVod
                    </div>
                HTML;
                return $html;
            } else {
                return "Une erreur est survenue";
            }

        }
    }


}