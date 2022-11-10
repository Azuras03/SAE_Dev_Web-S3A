<?php

namespace netvod\action;

use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;
use netvod\review\FavList;

class ActionDisplayFavoriteSeries extends Action
{

    public function execute(): string
    {
        $seriesPref = FavList::preferedSeries();
        return '<div class = "container"><h3>Liste de vos séries préférées ⭐ :</h3> <p class="listeSerie">' . $seriesPref . '</p></div>';
    }
}