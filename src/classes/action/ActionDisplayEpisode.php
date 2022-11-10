<?php

namespace netvod\action;

use netvod\catalogue\Avis;
use netvod\catalogue\Episode;
use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;
use netvod\review\Review;
use netvod\user\User;

class ActionDisplayEpisode extends Action
{

    public function execute(): string
    {
        $episode = Episode::loadEpisode();
        $user = unserialize($_SESSION["user"]);
        $user->addEpisodeInProgress($episode->id);
        $comment = Review::displayReviewForm($episode->serie_id);
        return (new \netvod\render\RenderEpisode($episode))->render() . $comment;
    }
}