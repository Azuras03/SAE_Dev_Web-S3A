<?php

namespace netvod\dispatch;

use netvod\db\ConnectionFactory;

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
        $currUser = 'invité';
        if (isset($_SESSION['user'])){
            $currUser = unserialize($_SESSION['user'])->email;
        }

        //Affichage du header
        $affichage .= <<<HTML
        <p>Vous êtes connecté en tant que <b>$currUser</b></p>
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
                case "display-episode" :
                    $action = new \netvod\action\ActionDisplayEpisode();
                    $affichage .= $action->execute();
                    break;
                case "display-serie" :
                    $action = new \netvod\action\ActionDisplaySerie();
                    $affichage .= $action->execute();
                    break;
                case "saveseriefav" :
                    $action = new \netvod\action\ActionSaveSeriePref();
                    $affichage .= $action->execute();
                    break;
            }
        }

        $this->renderPage($affichage);

        if(isset($_SESSION['user']) && !isset($_GET['action'])){
            $db = ConnectionFactory::makeConnection();

            $addSerie = $db->prepare("SELECT titre, img FROM serie");
            $series = "";
            $id = 1;
            if ($addSerie->execute()) {
                while ($donnees = $addSerie->fetch()) {
                    $minia = '<img src="images/' . $donnees["img"] . '" height=200px width=500px>';
                    $url = '?action=display-serie&serie=' . $id;
                    $series .= '<a href=' . $url . '>' . $donnees['titre'] . '</a><br>'. $minia .'</br>';
                    $id++;
                }
            }
            echo '<h4>Liste des séries :</h4> <p>'.$series.'</p>';
        }
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