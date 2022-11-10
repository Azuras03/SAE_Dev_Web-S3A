<?php

namespace netvod\render;

use netvod\dispatch\Dispatcher;
use netvod\theme\Theme;

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

        if (isset($_SESSION['user'])) {
            $currUser = unserialize($_SESSION['user'])->email;
            $connected = true;
        }

        if ($connected) {
            $resultatConnexion = <<<HTML
                <a href="?action=display-serie" class="bouton">Accueil 🏠</a>
                <a href="?action=userinfos" class="bouton">Mes informations 📜</a>
                <a href="?action=signout" class="bouton">Se déconnecter 🚪</a>
                <a href="?action=showfavserie" class="bouton">Vos séries préférées ⭐</a>
                <a href="?action=progress-list" class="bouton">Vos épisodes en cours 🕰️</a>
            HTML;
        } else {
            $resultatConnexion = <<<HTML
                <a href="?action=signup" class="bouton">S'inscrire 🆕</a>
                <a href="?action=signin" class="bouton">Se connecter 🙍</a>
            HTML;
        }

        if (!isset($_SESSION['theme'])) $_SESSION['theme'] = "";
        $srcStyleTheme = Theme::getSrcStylesheet();

        if ($_SESSION["theme"] == "")
            $menuTheme = <<<HTML
                <a href="?action=chgtheme" class="bouton theme">Manual Theme ♦</a>
            HTML;
        else
            $menuTheme = <<<HTML
                <a href="?action=autotheme" class="bouton theme">Auto Theme ♦</a>
                <a href="?action=chgtheme" class="bouton theme">Switch Theme 🔁</a>
                HTML;

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
         <link rel="stylesheet" href="$srcStyleTheme">
        </head>        
        <body>
        <header>
            <p class="connection">Vous êtes connecté en tant que <b class="nomcompte">$currUser</b></p>
            <img src="images/logo.png" class="logo" alt="Logo NetVod">
        
            <nav class="accueilPannel">
                $menuTheme
                $resultatConnexion
            </nav>
        </header>
        <main>
        {$dispatcher->run()}
        </main>
        <footer>
            <p>© 2022 - NetVOD🎬 - Tous droits réservés</p>
            <p><a href = "?action=informations">Informations</a></p>
        </footer>
        </body></html>
        HTML;
    }
}