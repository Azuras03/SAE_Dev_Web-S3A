<?php

namespace netvod\action;

use netvod\dispatch\Dispatcher;
use netvod\theme\Theme;

class ActionChangeTheme extends Action
{
    public function execute(): string
    {
        Theme::changeTheme();
        return <<<HTML
        <meta http-equiv="refresh" content="0;URL={$_SERVER["HTTP_REFERER"]}">
        <p>Changé 🟢</p>
        HTML;
    }
}