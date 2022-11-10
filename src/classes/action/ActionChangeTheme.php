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
            
            .rectangleSerie:hover{
                box-shadow: 0 0 10px 0 rgba(255, 255, 255);
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
            
            .rectangleSerie:hover{
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.5);
            }
            
            ';
        }
        return <<<HTML
        <meta http-equiv="refresh" content="0;URL={$_SERVER["HTTP_REFERER"]}">
        <p>Changé 🟢</p>
        HTML;
    }
}