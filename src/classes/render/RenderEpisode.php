<?php

namespace netvod\render;

use netvod\catalogue\Episode;
use netvod\db\ConnectionFactory;

class RenderEpisode implements Renderer
{

    public Episode $episode;

    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    public function render(): string
    {
        return <<<HTML
        <h1 id="titreEpisode">{$this->episode->titre}</h1>
        <p id="resumeEpisode">{$this->episode->resume}</p>
        <video width="1730" height="550" controls>
        <source src="video/{$this->episode->file}" type="video/mp4">
        </video>
        HTML;
    }


}