<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;

class ActionActivateAccount extends Action
{

    public function execute(): string
    {
        $token = filter_var($_GET['token'], FILTER_SANITIZE_SPECIAL_CHARS);

        $db = ConnectionFactory::makeConnection();
        $q1 = "SELECT email FROM user WHERE activation_token = ?";
        $st = $db->prepare($q1);
        $st->execute([$token]);
        $user = $st->fetch(\PDO::FETCH_ASSOC);
        $email = $user['email'];

        $q2 = "UPDATE user SET activation_token = NULL WHERE email = ?";
        $st2 = $db->prepare($q2);

        $q3 = "UPDATE user SET account_activated = 1 WHERE email = ?";
        $st3 = $db->prepare($q3);


        if ($st2->execute([$email]) && $st3->execute([$email])) {
            return <<<HTML
                   <p>Votre compte à bien été activé</p>
                   <p>Vous allez être redirigé vers la page d'accueil, veuillez patienter</p>
                   <head>
                        <meta http-equiv="refresh" content="2;URL=index.php">
                   </head>
                HTML;
        } else {
            return "Une erreur est survenue";
        }
    }
}
