<?php

namespace netvod\action;

class ActionDisplayEpisode extends Action
{

    public function execute(): string
    {
        $episode = unserialize($_SESSION['episode']);
        $user = $_SESSION["user"];
        $user->addEpisodeInProgress();
        $renderer = new \netvod\render\RenderEpisode($episode);
        return $renderer->render();
    }
}