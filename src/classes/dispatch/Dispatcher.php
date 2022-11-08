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
        if (isset($_SESSION['user'])) {
            $currUser = unserialize($_SESSION['user'])->email;
        }

        //Affichage du header
        $affichage .= <<<HTML
        <style>
            body {
                background-image: url("/images/background.png");
            }
            .head {
                background-color: black;
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
                
            }
            
            .nomcompte {
                color: red;
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
                case "detail" :
                    $action = new \netvod\action\ActionDetailSerie();
                    $affichage .= $action->execute();
                    break;
            }
        }

        $this->renderPage($affichage);

        if (isset($_SESSION['user'])) {
            $db = ConnectionFactory::makeConnection();

            $addSerie = $db->prepare("SELECT titre, img FROM serie");
            $series = "";
            $id = 1;
            if ($addSerie->execute()) {
                while ($donnees = $addSerie->fetch()) {
                    $minia = '<img src="images/' . $donnees["img"] . '" height=200px width=500px>';
                    $url = '?action=detail&id=' . $id;
                    $series .= '<a href=' . $url . '>' . $donnees['titre'] . '</a><br>' . $minia . '</br>';
                    $id++;
                }
            }
            echo '<h4>Liste des séries :</h4> <p>' . $series . '</p>';
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