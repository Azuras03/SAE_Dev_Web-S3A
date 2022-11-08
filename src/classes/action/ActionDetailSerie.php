<?php

namespace netvod\action;

use netvod\catalogue\Episode;
use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;

class ActionDetailSerie extends Action
{

    public function execute(): string
    {
        $html = Serie::displaySerie();
        $html .= Episode::displayEpisode();
        return $html;

    }
}