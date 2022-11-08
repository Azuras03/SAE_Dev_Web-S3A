<?php

namespace netvod\action;

use netvod\dispatch\Dispatcher;

class ActionChangeTheme extends Action
{
    public function execute(): string
    {
        if ($_SESSION['theme'] == 'colorBackgroundChangeLight') {
            $_SESSION['theme'] = 'colorBackgroundChangeDark';
            Dispatcher::$themeChangesStyle .= '
            p{
                color: white;
            }
            
            h3{
                color: white;
            }
            ';

        } else {
            $_SESSION['theme'] = 'colorBackgroundChangeLight';
            Dispatcher::$themeChangesStyle .= '
            p{
                color: black;
            }
            
            h3{
                color: black;
            }
            ';
        }
        return '<p>Changé 🟢</p>';
    }
}