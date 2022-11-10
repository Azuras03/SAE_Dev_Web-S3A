<?php

namespace netvod\review;

use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;

class FavList
{
    public static function preferedSeries() : string
    {
        $db = ConnectionFactory::makeConnection();
        $seriesPref = "";

        $addSerie = $db->prepare("SELECT titre, img, id_serie as id FROM serie INNER JOIN user2serie ON user2serie.id_serie = serie.id INNER JOIN user ON user2serie.id_user = user.id WHERE user.id = ?;");
        $addSerie->bindParam(1, unserialize($_SESSION['user'])->id);
        if ($addSerie->execute()) {
            if ($addSerie->rowCount() == 0) {
                $seriesPref =  "<p>Vous n'avez pas de série préférée... Pour l'instant 😉</p>";
            } else {
                $seriesPref .= '<p>Nombre de séries : ' . $addSerie->rowCount() . '</p>';

                $seriesPref .= Serie::showSeriesTiles($addSerie);
            }
        }
        return $seriesPref;
    }
}