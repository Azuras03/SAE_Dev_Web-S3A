<?php

namespace netvod\dispatch;

class Dispatcher
{

    public ?string $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? null;
    }

    public function run(): void
    {
        $affichage = "";

        //Affichage du header
        $affichage .= <<<HTML
        <h1>Bienvenue sur le service de VOD netVOD</h1>
        
        <ul>
        <li><a href="Index.php">Accueil</a></li>
        <li><a href="?action=signup">S'inscrire</a></li>
        <li><a href="?action=signin">Se connecter</a></li>
        </ul>
        HTML;

        //Affichage du contenu
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case "signin" :
                    $action = new \netvod\action\ActionSignIn();
                    $affichage .= $action->execute();
                    break;

                case "userinfos" :
                    $action = new \netvod\action\ActionUserInfos();
                    $affichage .= $action->execute();
                    break;
                case "signup" :
                    $action = new \netvod\action\ActionSignUp();
                    $affichage .= $action->execute();
                    break;
            }
        }
        echo $affichage;
    }

}