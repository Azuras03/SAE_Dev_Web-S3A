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
            
            .logo{
                filter : invert(100%);
            }
            
            .logo:hover{
                filter : invert(90%);
            }
            ';

        } else {
            $_SESSION['theme'] = 'colorBackgroundChangeLight';
            $_SESSION['CSSThemeChanges'] .= '
            p, h3, h1, h2, h3, h4, #titreEpisode, #resumeEpisode, li, label{
                color: black;
            }
            
            .logo{
                filter : invert(0%);
            }
            
            .logo:hover{
                filter : invert(10%);
            }
            ';
        }
        return '
        <head>
            <meta http-equiv="refresh" content="0;URL=Index.php">
        </head>
        <p>Changé 🟢</p>';
    }
}