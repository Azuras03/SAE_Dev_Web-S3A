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
        $episode = unserialize($_SESSION['episode']);
        return <<<HTML
        <h1>{$episode->titre}</h1>
        <p>{$episode->resume}</p>
        <video width="320" height="240" controls>
        <source src="video/{$episode->file}" type="video/mp4">
        </video>
        HTML;
    }


}