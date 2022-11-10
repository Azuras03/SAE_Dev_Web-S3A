<?php

namespace netvod\action;

use netvod\catalogue\Episode;
use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;
use netvod\review\Review;

class ActionDisplaySerie extends Action
{

    public function execute(): string
    {
        if (isset($_GET['serie'])) {
            $review = Review::displayReviewForm($_GET['serie']); //Astuce pour que la note soit actualisée au chargement de la page.
            $html = Serie::displaySerie();
            $html .= Episode::displayDataEpisode();
            $html .= $review;
            return $html;
        }
        else return Serie::displaySeriesList();
    }
}