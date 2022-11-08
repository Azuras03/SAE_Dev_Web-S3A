<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;

class ActionDisplayFavoriteSeries extends Action
{

    public function execute(): string
    {
        $db = ConnectionFactory::makeConnection();
        $seriesPref = "";

        $addSerie = $db->prepare("SELECT titre, img, id_serie FROM serie INNER JOIN user2serie ON user2serie.id_serie = serie.id INNER JOIN user ON user2serie.id_user = user.id WHERE user.id = ?;");
        $addSerie->bindParam(1, unserialize($_SESSION['user'])->id);
        if ($addSerie->execute()) {
            while ($donnees = $addSerie->fetch()) {
                $minia = '<img src="images/' . $donnees["img"] . '" height=200px width=500px>';
                $url = '?action=display-serie&serie=' . $donnees["id_serie"];
                $seriesPref .= '<a href=' . $url . '>' . $donnees['titre'] . '</a><br>'. $minia .'</br>';
            }
        }

        return '<h3>Liste de vos séries préférées ⭐ :</h3> <p>'.$seriesPref.'</p>';
    }
}