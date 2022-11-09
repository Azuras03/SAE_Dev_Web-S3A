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

        $fav = '<a class="favoriteButton" href="Index.php?action=saveseriefav&id=' . $idSerie . '&fav=ajout">⭐</a>';

        $sql = 'SELECT * FROM user2serie WHERE id_serie = ? AND id_user = ?';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $idSerie);
        $stmt->bindParam(2, unserialize($_SESSION['user'])->id);
        $stmt->execute();
        if($stmt->rowCount() != 0) {
            $fav = '<a class="favoriteButton" href="Index.php?action=saveseriefav&id=' . $idSerie . '">✴</a>';
        }

        return <<<HTML
            <p>Détails de la série $titre :</p>
            <p>$detail</p>
            $fav
            <p>Descriptif : $desc</p>

        HTML;
    }
}