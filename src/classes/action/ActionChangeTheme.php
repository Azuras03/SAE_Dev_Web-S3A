<?php

namespace netvod\action;

use netvod\dispatch\Dispatcher;

class ActionChangeTheme extends Action
{
    public function execute(): string
    {
        if ($_SESSION['theme'] == 'colorBackgroundChangeLight') {
            $_SESSION['theme'] = 'colorBackgroundChangeDark';
            $_SESSION['CSSThemeChanges'] .= '
            p, h3, h1, h2, h3, h4, #titreEpisode, #resumeEpisode, li, label{
                color: white;
            }
            ';

        } else {
            $_SESSION['theme'] = 'colorBackgroundChangeLight';
            $_SESSION['CSSThemeChanges'] .= '
            p, h3, h1, h2, h3, h4, #titreEpisode, #resumeEpisode, li, label{
                color: black;
            }
            ';
        }
        return '<p>Changé 🟢</p>';
    }
}