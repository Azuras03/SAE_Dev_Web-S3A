<?php

namespace netvod\render;

/**
 * Used to return HTML
 */
interface Renderer
{

    public function render(): string;

}