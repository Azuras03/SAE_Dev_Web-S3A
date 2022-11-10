<?php

namespace netvod\action;

use netvod\theme\Theme;

class ActionAutoTheme extends Action
{

    public function execute() : string
    {
        Theme::switchAutoTheme();
        return <<<HTML
        <meta http-equiv="refresh" content="0;URL={$_SERVER["HTTP_REFERER"]}">
        <p>Changé 🟢</p>
        HTML;
    }
}