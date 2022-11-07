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
        <li><a href="?action=display">Afficher catalogue</a></li>
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
                case "display" :
                    $action = new \netvod\action\ActionDisplayCatalogue();
                    $affichage .= $action->execute();
                    break;
            }
        }
        else $affichage .= "BONJOUR A TOUS";

        $this->renderPage($affichage);
    }

    /**
     * Method that return string corresponding to the main content to show to user
     * @param string $html
     * @return void
     */
    private function renderPage(string $html) : void {
        echo $html;
    }

}