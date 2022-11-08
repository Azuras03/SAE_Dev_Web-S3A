<?php

namespace netvod\action;

class ActionChangeTheme extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['theme'])) {
            $_SESSION['theme'] = 'colorBackgroundChangeDark';
        } else {
            if ($_SESSION['theme'] == 'colorBackgroundChangeLight') {
                $_SESSION['theme'] = 'colorBackgroundChangeDark';
            } else {
                $_SESSION['theme'] = 'colorBackgroundChangeLight';
            }
        }
        return 'Changé 🟢';
    }
}