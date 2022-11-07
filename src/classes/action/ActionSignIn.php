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
            if (Authentification::authenticate($email, $password)) {
                $user = User::userById($email);
                $db = ConnectionFactory::makeConnection();
                $stmt = $db->prepare('SELECT * FROM userinfo WHERE id_user = ?');
                if ($stmt->execute([$user->id])) {
                    $infos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $user->infos = $infos;
                }
                $_SESSION['user'] = serialize($user);


                return <<<HTML
                    
                    $email est connecté au service NetVOD <br>                
                    Vous pouvez modifier vos infos personnelles </br>                                    
                    <form method="post" action="?action=userinfos">
                    <label>Prénom : </label>
                    <input type="text" name="prenom" value="{$user->infos[0]['prenom']}"><br>
                    <label>Nom : </label>
                    <input type="text" name="nom" value="{$user->infos[0]['nom']}"><br>
                    <label>Pseudo : </label>
                    <input type="text" name="pseudo" value="{$user->infos[0]['pseudo']}"><br>
                    <label>Date de naissance</label>
                    <input type="date" name="date_naissance" value="{$user->infos[0]['date_naissance']}"><br>
                    <button type="submit">Modifier</button>
                    </form>
                   

                HTML;

            } else {
                return <<<HTML
                    Une erreur s'est produite : Identifiants invalides
                HTML;
            }
        }
    }
}