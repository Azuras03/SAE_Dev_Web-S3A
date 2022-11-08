<?php

namespace netvod\action;

use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;

class ActionDetailSerie extends Action
{

    public function execute(): string
    {
        $html = Serie::displaySerie();

        $db = ConnectionFactory::makeConnection();

        $stmt2 = $db->prepare('SELECT numero, titre, duree FROM episode WHERE serie_id = ?');
        $stmt2->bindParam(1, $idSerie);
        $episode = "";

        if ($stmt2->execute()) {
            while ($donneesEp = $stmt2->fetch()) {
                $num = $donneesEp['numero'];
                $titreEp = $donneesEp['titre'];
                $dureeEp = $donneesEp['duree'];
                $episode .= 'Épisode ' . $num . ' : </br> Titre : ' . $titreEp . '</br> Durée : ' . $dureeEp . ' minutes</br></br>';
            }
        }

        $html .= "<p>$episode</p>";
        return $html;

    }
}