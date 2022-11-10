<?php

namespace netvod\action;

use netvod\auth\Authentification;
use netvod\db\ConnectionFactory;
use netvod\user\User;

class ActionSignIn extends Action
{

    public function execute(): string
    {
        if ($this->http_method == 'GET') {
            return <<<HTML
            <h3>Connexion</h3>
            <form method="post" action="?action=signin">
                <table class="miseenforme">
                    <tr>
                        <th><label>Email : </label></th>
                        <th><label>Mot de passe : </label></th>  
                    </tr>
                    <tr>                        
                        <th><input type="email" name="email" placeholder="<email>"><br></th>
                        <th><input type="password" name="password" placeholder="<password>"><br></th>
                        <th><button type="submit">Se Connecter</button></th>
                    </tr>
                    <tr>
                        <th><a href="?action=emailpasswordreset" style="color: #0c0c0c">Mot de passe oublié ?</p></th>
                    </tr>
                </table>         
            </form>           
            HTML;
        } else {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            if (Authentification::authenticate($email, $password)) {
                $user = User::userByEmail($email);
                $db = ConnectionFactory::makeConnection();
                $stmt = $db->prepare('SELECT * FROM userinfo WHERE id_user = ?');
                if ($stmt->execute([$user->id])) {
                    $infos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $user->infos = $infos;
                }
                $_SESSION['user'] = serialize($user);

                return <<<HTML
                   <head>
                        <meta http-equiv="refresh" content="0;URL=index.php">
                   </head>
                HTML;


            } else {
                return <<<HTML
                    Une erreur s'est produite : Identifiants invalides
                HTML;
            }
        }
    }
}