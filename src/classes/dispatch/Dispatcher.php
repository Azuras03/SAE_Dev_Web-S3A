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
        <style>
            body {
                background-image: url("/images/background.png");
            }
            .head {
                background-color: rgb(120, 120, 120, 0.3);
            }
            .bienvenue {
                color: #34BBE2;
                text-align: center;
            }
            
            .accueilPannel {
                text-align: center;
                padding-top: 20px;              
            }
            
            .bouton {
                text-decoration: none;
                color: white;
                background-color: black;
                border: solid 7px #34BBE2;
                padding: 7px 7px 7px 7px;
                margin: 0px 20px 0px 20px;
            }
            
            .bouton:hover {
                color: black;
                background-color: white;
            }
            
            .connection {
                padding-top: 10px;
                padding-left: 10px;
            }
            
            .nomcompte {
                color: red;
            }
            
            .inscription {
                color: green;
            }
            
            h3 {
                color: black;
                text-align: center;
                padding-top: 10px;
            }
          
        </style>
        <div class="head">
            <p class="connection">Vous êtes connecté en tant que <b class="nomcompte">$currUser</b></p>
            <h1 class="bienvenue">Bienvenue sur le service de VOD netVOD</h1>
        
            <ul class="accueilPannel">
                <a href="Index.php" class="bouton">Accueil</a>
                <a href="?action=signup" class="bouton">S'inscrire</a>
                <a href="?action=signin" class="bouton">Se connecter</a>
            </ul>
        </div>
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
                case "showfavserie" :
                    $action = new \netvod\action\ActionDisplayFavoriteSeries();
                    $affichage .= $action->execute();
            }
        }

        $this->renderPage($affichage);

        if(isset($_SESSION['user']) && !isset($_GET['action'])){
            $db = ConnectionFactory::makeConnection();

            $addSerie = $db->prepare("SELECT titre, img, id FROM serie");
            $series = "";
            if ($addSerie->execute()) {
                while ($donnees = $addSerie->fetch()) {
                    $minia = '<img src="images/' . $donnees["img"] . '" height=200px width=500px>';
                    $url = '?action=display-serie&serie=' . $donnees["id"];
                    $series .= '<a href=' . $url . '>' . $donnees['titre'] . '</a><br>'. $minia .'</br>';
                }
            }
            echo '<h3>Liste des séries :</h3> <p>' . $series . '</p>';
        }
    }

    /**
     * Method that return string corresponding to the main content to show to user
     * @param string $html
     * @return void
     */
    private function renderPage(string $html): void
    {
        echo $html;
    }

}