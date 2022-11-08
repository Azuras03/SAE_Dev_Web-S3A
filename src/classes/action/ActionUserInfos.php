<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;

class ActionUserInfos extends Action
{
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $user = unserialize($_SESSION['user']);
            return <<<HTML
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
            $user = unserialize($_SESSION['user']);
            $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
            $pseudo = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING);
            $date_naissance = filter_var($_POST['date_naissance'], FILTER_SANITIZE_STRING);

            $db = ConnectionFactory::makeConnection();
            $stmt = $db->prepare('UPDATE userinfo SET prenom = ?, nom = ?, pseudo = ?, date_naissance = ? WHERE id_user = ?');
            if ($stmt->execute([$prenom, $nom, $pseudo, $date_naissance, $user->id])) {
                $user->infos[0]['prenom'] = $prenom;
                $user->infos[0]['nom'] = $nom;
                $user->infos[0]['pseudo'] = $pseudo;
                $user->infos[0]['date_naissance'] = $date_naissance;
                $_SESSION['user'] = serialize($user);
                return <<<HTML
                Vos informations ont été modifiées
            HTML;
            } else {
                return <<<HTML
                Une erreur s'est produite
            HTML;
            }
        }
    }

}