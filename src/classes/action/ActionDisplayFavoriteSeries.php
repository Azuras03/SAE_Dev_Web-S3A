<?php

namespace netvod\action;

use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;
use netvod\list\FavList;

class ActionDisplayFavoriteSeries extends Action
{

    public function execute(): string
    {
        $seriesPref = FavList::displayFavSeries();
        return '<div class = "container"><h3>Liste de vos séries préférées ⭐ :</h3> <p class="listeSerie">' . $seriesPref . '</p></div>';
    }
}