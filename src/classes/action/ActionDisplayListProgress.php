<?php

namespace netvod\action;

use netvod\catalogue\Episode;
use netvod\db\ConnectionFactory;

class ActionDisplayListProgress extends Action
{

    public function execute(): string
    {
        $html = \netvod\list\ProgressList::displayProgress();
        return '<div class = "container"><h3>Liste de vos épisodes en cours 🕰️ :</h3> <p class="listeSerie">'.$html.'</p></div>';
    }
}