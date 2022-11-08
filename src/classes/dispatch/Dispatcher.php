<?php

namespace netvod\dispatch;

use netvod\action\ActionDisplayListProgress;
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
        $connected = false;
        $currUser = 'invité';
        if (isset($_SESSION['user'])) {
            $currUser = unserialize($_SESSION['user'])->email;
            $connected = true;
        }

        if ($connected) {
            $resultatConnexion = <<<HTML
                <a href="Index.php" class="bouton">Accueil</a>
                <a href="?action=signout" class="bouton">Se déconnecter</a>
                <a href="?action=showfavserie" class="bouton">Vos titres préférés ⭐</a>
                <a href="?action=progress-list" class="bouton">Vos épisodes en cours 🕰️</a>
            HTML;
        } else {
            $resultatConnexion = <<<HTML
                <a href="?action=signup" class="bouton">S'inscrire</a>
                <a href="?action=signin" class="bouton">Se connecter</a>
            HTML;
        }

        //Affichage du header
        $affichage .= <<<HTML
        <style>
            body {
                background-image: url("/images/background.png");
            }
            
            @keyframes opacityIntro {
                0%{
                    filter: opacity(0%);
                }
                25%{
                    filter: opacity(0%);
                }
                100%{
                    filter: opacity(100%);
                }
            }
            
            @keyframes colorBackgroundChange {
                0%{
                    background-color: #ffffff;
                }
                100%{
                    background-color: #f1f1f1;
                }
            }
            
            html{
                animation-name: opacityIntro;
                animation-direction: normal;
                animation-iteration-count: 1;
                animation-duration: 2s;
                transition: 1s all cubic-bezier(0, 0, 0, 1);
                width: 100%;
                background-color: rgb(120, 120, 120, 0.3);
                font-family: 'Roboto', sans-serif;
                animation: colorBackgroundChange 1s infinite alternate-reverse;
            }
            .head {
                background-color: rgb(120, 120, 120, 0.3);
            }
            .bienvenue {
                color: black;
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
                transition : all 0.5s ease-out;
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
            
            .titreSerie {
                text-decoration: none;
                color: #34BBE2;
            }
            
            .listSerie {
                text-align: right;
            }
            
            #titreEpisode {
                color: black;
                text-align: center;
                margin-top: 30px;
                background-color: rgb(120, 120, 120, 0.3);
                padding: 10px 0 10px 0;
                border: solid 7px #34BBE2;
                border-radius: 10px;
                margin-bottom: 0;
            }
            
            #resumeEpisode {
                color: black;
                text-align: center;
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
                background-color: rgb(120, 120, 120, 0.3);
                width: 50%;
                margin-left: 25%;
                margin-top: 0;
                border: solid 7px #34BBE2;
                border-top: none;
                padding: 10px 0 10px 0;
            }
            
            body {                
            background-color: rgb(120, 120, 120, 0.3);
            margin: 0 0 0 0;
            padding: 10px 10px 10px 10px;
            }
            }
            
            video {
            padding: 0 25% 5% 25%;
            
            width: 50%;
            }
          
        </style>
        <html>
        <body>
        <div class="head">
            <p class="connection">Vous êtes connecté en tant que <b class="nomcompte">$currUser</b></p>
            <h1 class="bienvenue">Bienvenue sur le service de VOD netVOD</h1>
        
            <ul class="accueilPannel">
                $resultatConnexion
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
                    break;
                case "progress-list" :
                    $action = new ActionDisplayListProgress();
                    $affichage .= $action->execute();
                    break;
            }
        }

        $this->renderPage($affichage);

        if (isset($_SESSION['user']) && !isset($_GET['action'])) {
            $db = ConnectionFactory::makeConnection();

            $addSerie = $db->prepare("SELECT titre, img, id FROM serie");
            $series = "";
            if ($addSerie->execute()) {
                while ($donnees = $addSerie->fetch()) {
                    $minia = '<img src="images/' . $donnees["img"] . '" height=200px width=500px>';
                    $url = '?action=display-serie&serie=' . $donnees["id"];
                    $series .= '<a href=' . $url . ' class="titreSerie">' . $donnees['titre'] . '</a><br>' . $minia . '</br>';
                }
            }
            echo '<h3>Liste des séries :</h3> <p class="listeSerie">' . $series . '</p>';
        }
    }

    /**
     * Method that return string corresponding to the main content to show to user
     * @param string $html
     * @return void
     */
    private function renderPage(string $html): void
    {
        echo $html . '</body></html>';
    }
}