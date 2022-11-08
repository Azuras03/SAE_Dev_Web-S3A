<?php

namespace netvod\action;

use netvod\catalogue\Episode;
use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;

class ActionDisplaySerie extends Action
{

    public function execute(): string
    {
        $html = Serie::displaySerie();
        $html .= Episode::displayDataEpisode();
        return $html;
    }
}