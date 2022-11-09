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
                case 'review-list':
                    $action = new \netvod\action\ActionDisplayReviews();
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
                <a href="?action=userinfos" class="bouton">Mes informations 📜</a>
                <a href="?action=signout" class="bouton">Se déconnecter 🚪</a>
                <a href="?action=showfavserie" class="bouton">Vos titres préférés ⭐</a>
                <a href="?action=progress-list" class="bouton">Vos épisodes en cours 🕰️</a>
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
        <!doctype html>
        <html lang="fr">
        <head>
        <meta charset="UTF-8">
                     <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                                 <meta http-equiv="X-UA-Compatible" content="ie=edge">
                     <title>NetVOD</title>
                     <link rel="stylesheet" href="src/style/main.css">
                     <style>
                     html {
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
                       $themeToChange
                    </style>
        </head>        
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
        $affichage2
        </body></html>
        HTML;

        $this->renderPage($affichage);


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