<?php

namespace netvod\action;

use netvod\catalogue\Avis;
use netvod\catalogue\Episode;
use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;
use netvod\review\Review;
use netvod\user\User;

class ActionDisplayReviews extends Action
{

    public function execute(): string
    {
        $idSerie = $_GET['id'];
        return Review::displayComments($idSerie);
    }
}