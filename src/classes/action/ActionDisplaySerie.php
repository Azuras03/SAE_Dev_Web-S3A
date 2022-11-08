<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;

class ActionDisplaySerie extends Action
{

    public function execute(): string
    {
        $idSerie = $_GET['serie'];
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT titre, descriptif, annee, date_ajout FROM serie WHERE id = ?');
        $stmt->bindParam(1, $idSerie);

        if ($stmt->execute()) {
            $donnees = $stmt->fetch();

            $titre = $donnees['titre'];
            $detail = 'Cette série de ' . $donnees['annee'] . ' a été ajouté sur netVOD le ' . $donnees['date_ajout'];
            $desc = $donnees['descriptif'];
        }

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

        return <<<HTML

            <p>Détails de la série $titre :</p>
            <p>$detail</br>
            Descriptif : $desc</p>

            <p>$episode</p>
          
        HTML;

    }
}