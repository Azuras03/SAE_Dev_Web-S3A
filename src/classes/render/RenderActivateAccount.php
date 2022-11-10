<?php

namespace netvod\render;

use netvod\user\User;

class RenderActivateAccount implements Renderer
{

    public function render(): string
    {
        if (User::activatedAccount())
            return <<<HTML
                   <p>Votre compte à bien été activé</p>
                   <p>Vous allez être redirigé vers la page d'accueil, veuillez patienter</p>
                   <meta http-equiv="refresh" content="2;URL=index.php">
                HTML;
        else return "Une erreur est survenue";
    }
}