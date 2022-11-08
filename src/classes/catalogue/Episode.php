<?php

namespace netvod\catalogue;

use netvod\db\ConnectionFactory;

class Episode
{
    public int $id;
    public int $numero;
    public string $titre;
    public string $resume;
    public string $file;
    public int $duree;
    public int $serie_id;

    public function __construct(int $id, int $numero, string $titre, string $resume, string $file, int $duree, int $serie_id)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->titre = $titre;
        $this->resume = $resume;
        $this->file = $file;
        $this->duree = $duree;
        $this->serie_id = $serie_id;
    }

    public static function displayEpisode ()
    {
        $idSerie = $_GET['serie'];
        $db = ConnectionFactory::makeConnection();

        $stmt2 = $db->prepare('SELECT numero, titre, duree FROM episode WHERE serie_id = ?');
        $stmt2->bindParam(1, $idSerie);
        $episode = "<ul>";

        if ($stmt2->execute()) {
            while ($donneesEp = $stmt2->fetch()) {
                $num = $donneesEp['numero'];
                $titreEp = $donneesEp['titre'];
                $dureeEp = $donneesEp['duree'];
                $episode .= '<li>Épisode ' . $num . ' : </br> Titre : ' . $titreEp . '</br> Durée : ' . $dureeEp . ' minutes</br></br></li>';
            }
        }

        return "$episode</ul>";
    }


}