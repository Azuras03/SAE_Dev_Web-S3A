<?php

namespace netvod\dispatch;

use netvod\action\ActionDisplayListProgress;

class Dispatcher
{

    public static string $themeChangesStyle = "";
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
        if (!isset($_SESSION['CSSThemeChanges']))
            $_SESSION['CSSThemeChanges'] = '';

        $affichage2 = "";
        //Affichage du contenu
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case "signin" :
                    $action = new \netvod\action\ActionSignIn();
                    $affichage2 .= $action->execute();
                    break;
                case "userinfos" :
                    $action = new \netvod\action\ActionUserInfos();
                    $affichage2 .= $action->execute();
                    break;
                case "signup" :
                    $action = new \netvod\action\ActionSignUp();
                    $affichage2 .= $action->execute();
                    break;
                case "display-episode" :
                    $action = new \netvod\action\ActionDisplayEpisode();
                    $affichage2 .= $action->execute();
                    break;
                case "display-serie" :
                    $action = new \netvod\action\ActionDisplaySerie();
                    $affichage2 .= $action->execute();
                    break;
                case "saveseriefav" :
                    $action = new \netvod\action\ActionSaveSeriePref();
                    $affichage2 .= $action->execute();
                    break;
                case "showfavserie" :
                    $action = new \netvod\action\ActionDisplayFavoriteSeries();
                    $affichage2 .= $action->execute();
                    break;
                case "progress-list" :
                    $action = new ActionDisplayListProgress();
                    $affichage2 .= $action->execute();
                    break;
                case "chgtheme" :
                    $action = new \netvod\action\ActionChangeTheme();
                    $affichage2 .= $action->execute();
                    break;
                case "signout" :
                    $action = new \netvod\action\ActionSignOut();
                    $affichage2 .= $action->execute();
                    break;
                case"activateAccount":
                    $action = new \netvod\action\ActionActivateAccount();
                    $affichage2 .= $action->execute();
                    break;
                case "emailpasswordreset":
                    $action = new \netvod\action\ActionEmailPasswordReset();
                    $affichage2 .= $action->execute();
                    break;
                    case "passwordreset":
                    $action = new \netvod\action\ActionPasswordReset();
                    $affichage2 .= $action->execute();
            }
        } else {
            $action = new \netvod\action\ActionDisplaySerie();
            $affichage2 .= $action->execute();
        }


        if (isset($_SESSION['user'])) {
            $currUser = unserialize($_SESSION['user'])->email;
            $connected = true;
        }

        if (!isset($_SESSION['theme'])) {
            $_SESSION['theme'] = 'colorBackgroundChangeLight';
        }

        if ($connected) {
            $resultatConnexion = <<<HTML
                <a href="?action=display-serie" class="bouton">Accueil 🏠</a>
                <a href="?action=showfavserie" class="bouton">Vos titres préférés ⭐</a>
                <a href="?action=progress-list" class="bouton">Vos épisodes en cours 🕰️</a>
                <a href="?action=userinfos" class="bouton">Mes informations 📜</a>
                <a href="?action=signout" class="bouton">Se déconnecter 🚪</a>
            HTML;
        } else {
            $resultatConnexion = <<<HTML
                <a href="?action=signup" class="bouton">S'inscrire 🆕</a>
                <a href="?action=signin" class="bouton">Se connecter 🙍</a>
            HTML;
        }

        $currTheme = $_SESSION['theme'];
        $themeToChange = $_SESSION['CSSThemeChanges'];

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

            @keyframes colorBackgroundChangeLight {
                0%{
                    background-color: #ffffff;
                }
                100%{
                    background-color: #f1f1f1;
                }
            }
            
            @keyframes colorBackgroundChangeDark {
                0%{
                    background-color: #171717;
                }
                100%{
                    background-color: #0c0c0c;
                }
            }
            
            .favoriteButton {
                font-size: 3em;
            }

            html{
                animation-name: opacityIntro;
                animation-direction: normal;
                animation-iteration-count: 1;
                animation-duration: 2s;
                transition: 1s all cubic-bezier(0, 0, 0, 1);
                width: 100%;
                font-family: 'Roboto', sans-serif;
                animation: $currTheme 1s infinite alternate-reverse;
                background-color: rgb(120, 120, 120, 0.3);
                margin: 0 0 0 0;
            }
            .head {
                background-color: rgb(120, 120, 120, 0.3);
            }
            h1 {
                color: black;
                text-align: center;
            }
            
            .accueilPannel {
                text-align: center;
                padding-top: 20px;   
                display: flex;    
                justify-content: center;       
            }
            
            a {
                text-decoration: none;
                color: #34BBE2;
            
            }
            
            .bouton {
                text-decoration: none;
                color: white;
                background-color: black;
                border: solid 7px #34BBE2;
                padding: 7px 7px 7px 7px;
                margin: 0px 20px 0px 20px;
                transition: all 0.5s cubic-bezier(0, 0, 0, 1);
            }
            
            .bouton:hover {
                color: black;
                background-color: #34BBE2;
                transform: scale(1.08);
                border-color: #e6f4ff;
                box-shadow: 0 0 10px #34BBE2;
                border-radius: 10px;
            }
            
            .bouton:active {
                filter: blur(4px);
                transform: scale(0.8);
            }
            
            #theme {
                color: #8b99f5;
                border: solid 7px #2f1c9d;
            }
            
            #theme:hover {
                color: white;
                background-color: #2f1c9d;
                box-shadow: 0 0 10px #3442e2;
                border-color: #e6f4ff;
            }
            
            .connection {
                padding-top: 10px;
                padding-left: 10px;
                text-align: center;
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
            
            .container{
                display: flex;
                flex-direction: column;
                flex-grow: 1;
                align-items: center;
                text-align: center;
            }
            
            .rectangleSerie{
                background-color: #006f8a;
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.5);
                margin-bottom: 40px;
                border : 10px solid #6eb4c5;
                padding: 10px;
                border-radius: 10px;
                transition: all 0.5s cubic-bezier(0, 0, 0, 1);
            }
            
            .rectangleSerie:hover{
                filter: brightness(1.2);
                transform: scale(1.05);
            }
            
            .rectangleSerie img{
                border-radius: 20px;
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
            
            video {
                padding: 0 25% 5% 25%;
                width: 50%;
            }
            
            .miseenforme {
                background-color: #b6b6b6;
                justify-self: center;
                border: solid 2px black;
                margin: auto;
            }   
            
            th {
                padding: 5px 10px 5px 10px;
            }
            
            .connexionUser {
                background-color: black;
            }    
            
            .logo {
                width: 200px;
                height: 200px;
                display: flex;
                margin-left: auto;
                margin-right: auto;
                background-color: white;
                padding: 20px;
                border-radius: 10%;
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.5);
                transition: all 0.5s cubic-bezier(0, 0, 0, 1);
                flex-grow: 1;
            }
            
            .logo:hover {
                box-shadow: 0px 10px 10px 0 rgba(0, 0, 0, 0.5);
                transform : translateY(-5px) scale(1.02);
            }
            
            .lettre {
            margin: 0 30% 0 30%;
            padding: 30px 30px 30px 30px;
            background-color: lightgray;
            }    
            
            #email {
            text-align: right;
            }
            
            #emailCorrespondants {
            text-align: left;
            background-color: grey;
            border: solid 2px dimgray;
            border-radius: 2px;
            margin-bottom: 0;
            }
            
             
          
           $themeToChange
          
        </style>
        <html>
        <body>
        <div class="head">
            <p class="connection">Vous êtes connecté en tant que <b class="nomcompte">$currUser</b></p>
            <img src="images/logo.png" class="logo">
        
            <ul class="accueilPannel">
                <a href="?action=chgtheme" class="bouton" id="theme">Change Theme ♦</a>
                $resultatConnexion
            </ul>
        </div>
        HTML. $affichage2;

        $this->renderPage($affichage);


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