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

        $query = $db->prepare("SELECT * FROM episode WHERE id = ?");
        $query->execute([$episode->id]);
        $episodes = $query->fetchAll(\PDO::FETCH_CLASS, "netvod\Episode");
        $episodes = $episodes[0];
        return <<<HTML
        <h1>{$episodes->title}</h1>
        <p>{$episodes->description}</p>
        <video width="320" height="240" controls>
        <source src="/video/{$episodes->file}" type="video/mp4">
        </video>
        HTML;
    }


}