<?php

namespace netvod\action;

use netvod\catalogue\Avis;
use netvod\catalogue\Episode;
use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;
use netvod\user\Review;
use netvod\user\User;

class ActionDisplayReviews extends Action
{

    public function execute(): string
    {
        $idEpisode = $_GET['episode'];
        $idSerie = $_GET['serie'];

        Review::displayComments($idSerie);
        /*
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM episode WHERE serie_id = ? AND numero = ?');
        $stmt->bindParam(1, $idSerie);
        $stmt->bindParam(2, $idEpisode);
        $episode = "";
        if ($stmt->execute()) {
            $donnees = $stmt->fetch();
            $id = $donnees['id'];
            $numero = $donnees['numero'];
            $titre = $donnees['titre'];
            $resume = $donnees['resume'];
            $duree = $donnees['duree'];
            $file = $donnees['file'];
            $serId = $donnees['serie_id'];
            $episode = new Episode($id, $numero, $titre, $resume, $file, $duree, $idSerie);
        }
        $renderer = new \netvod\render\RenderEpisode($episode);
        $user = unserialize($_SESSION["user"]);
        $user->addEpisodeInProgress($id);


        $comment = Review::displayReviewForm($serId);
        return $renderer->render() . $comment;
        */
    }
}