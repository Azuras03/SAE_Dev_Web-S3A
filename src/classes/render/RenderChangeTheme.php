<?php

namespace netvod\render;

/**
 * Used to return the change theme view
 */
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