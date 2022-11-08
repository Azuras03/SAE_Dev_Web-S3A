<?php

namespace netvod\catalogue;

use netvod\db\ConnectionFactory;

class Serie
{
    public string $titre, $descriptif, $date_ajout;
    public int $annee;

    public function __construct(string $titre, string $desc, string $dataj, int $annee)
    {
        $this->titre = $titre;
        $this->descriptif = $desc;
        $this->date_ajout = $dataj;
        $this->annee = $annee;
    }

    public static function displaySerie (): string
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

        return <<<HTML
            <p>Détails de la série $titre :</p>
            <a href="Index.php?action=saveseriefav&id=$idSerie">⭐</a>
            <p>$detail</p>
            <p>Descriptif : $desc</p>

        HTML;
    }

}