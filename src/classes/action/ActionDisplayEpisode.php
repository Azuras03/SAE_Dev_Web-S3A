<?php

namespace netvod\action;

class ActionDisplayEpisode extends Action
{

    public function execute(): string
    {
        $episode = unserialize($_SESSION['episode']);
        //$user = $_SESSION["user"];
        $renderer = new \netvod\render\RenderEpisode($episode);
        //$user->addEpisodeInProgress();
        return $renderer->render();
    }
}