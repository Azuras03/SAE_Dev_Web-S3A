<?php

namespace netvod\render;

class RenderChangeTheme implements Renderer
{

    public function render(): string
    {
        return <<<HTML
        <meta http-equiv="refresh" content="0;URL={$_SERVER["HTTP_REFERER"]}">
        <p>Changé 🟢</p>
        HTML;
    }
}