<?php

namespace netvod\action;

use netvod\dispatch\Dispatcher;
use netvod\render\RenderChangeTheme;
use netvod\theme\Theme;

class ActionChangeTheme extends Action
{
    public function execute(): string
    {
        Theme::changeTheme();
        return (new RenderChangeTheme())->render();
    }
}