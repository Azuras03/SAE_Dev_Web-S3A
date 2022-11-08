<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;

class ActionUserInfos extends Action
{
    public function execute(): string
    {
        $user = unserialize($_SESSION['user']);

        $user->infos[0]['prenom'] = $user->infos[0]['prenom'] ?? "";
        $user->infos[0]['nom'] = $user->infos[0]['nom'] ?? "";
        $user->infos[0]['pseudo'] = $user->infos[0]['pseudo'] ?? "";
        $user->infos[0]['date_naissance'] = $user->infos[0]['date_naissance'] ?? "";

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            return <<<HTML
                    <h3>Vous pouvez modifier vos infos personnelles</h3></br>                                                    
                    <form method="post" action="?action=userinfos">
                        <table class="miseenforme">
                            <tr>
                                <th><label>Prénom</label></th>
                                <th><label>Nom</label></th>
                                <th><label>Pseudo</label></th>
                                <th><label>Date de naissance</label></th>                       
                            </tr>
                            <tr>
                                <th><input type="text" name="prenom" value="{$user->infos[0]['prenom']}"><br></th>
                                <th><input type="text" name="nom" value="{$user->infos[0]['nom']}"><br></th>
                                <th><input type="text" name="pseudo" value="{$user->infos[0]['pseudo']}"><br></th>
                                <th><input type="date" name="date_naissance" value="{$user->infos[0]['date_naissance']}"><br></th>
                                <th><button type="submit">Modifier</button></th>
                            </tr>                                        
                        </table>
                    </form>                   

                HTML;
        } else {
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