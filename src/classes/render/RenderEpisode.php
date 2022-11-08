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
        $db = ConnectionFactory::makeConnection();
        return <<<HTML
        <h1>{$this->episode->titre}</h1>
        <p>{$this->episode->resume}</p>
        <video width="320" height="240" controls>
        <source src="video/{$this->episode->file}" type="video/mp4">
        </video>
        HTML;
    }


}