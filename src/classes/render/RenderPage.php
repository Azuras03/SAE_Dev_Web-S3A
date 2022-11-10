<?php

namespace netvod\render;

use netvod\dispatch\Dispatcher;

class RenderPage implements Renderer
{

    public function __construct()
    {
    }

    public function render(): string
    {
        $affichage = "";
        $connected = false;
        $currUser = 'invité';
        if (!isset($_SESSION['CSSThemeChanges']))
            $_SESSION['CSSThemeChanges'] = '';

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

        $dispatcher = new Dispatcher();
        return <<<HTML
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
        <body>
        <header>
            <p class="connection">Vous êtes connecté en tant que <b class="nomcompte">$currUser</b></p>
            <img src="images/logo.png" class="logo" alt="Logo NetVod">
        
            <ul class="accueilPannel">
                <a href="?action=chgtheme" class="bouton" id="theme">Change Theme ♦</a>
                $resultatConnexion
            </ul>
        </header>
        {$dispatcher->run()}
        <footer>
            <p>© 2022 - NetVOD🎬 - Tous droits réservés</p>
            <p><a href = "?action=informations">Informations</a></p>
        </footer>
        </body></html>
        HTML;
    }
}