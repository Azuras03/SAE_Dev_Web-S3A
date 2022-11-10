<?php

namespace netvod\catalogue;

use netvod\db\ConnectionFactory;
use netvod\user\Review;
use netvod\user\User;

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
        if (($note = self::getMoySerie($idSerie)) != 0)
            $note .= "/5";
        else $note = "Pas assez de données";

        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT titre, descriptif, annee, date_ajout FROM serie WHERE id = ?');

        if ($stmt->execute([$idSerie])) {
            $donnees = $stmt->fetch();

            $titre = $donnees['titre'];
            $detail = 'Cette série de ' . $donnees['annee'] . ' a été ajouté sur netVOD le ' . $donnees['date_ajout'];
            $desc = $donnees['descriptif'];
        }

        $sql = 'SELECT * FROM user2serie WHERE id_serie = ? AND id_user = ?';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $idSerie);
        $stmt->bindParam(2, unserialize($_SESSION['user'])->id);
        $stmt->execute();
        if($stmt->rowCount() != 0) {
            $fav = '<a class="favoriteButton" href="Index.php?action=saveseriefav&id=' . $idSerie . '">✴</a>';
        }
        else $fav = '<a class="favoriteButton" href="Index.php?action=saveseriefav&id=' . $idSerie . '&fav=ajout">⭐</a>';

        return <<<HTML
            <h3>$titre</h3>$fav
            <p>$detail</p>
            <p>Note moyenne : $note | <a href="Index.php?action=review-list&id=$idSerie">Voir les commentaires</a></p>
            
            <p>📃 Descriptif : $desc</p>

        HTML;
    }


    public static function getMoySerie(mixed $idSerie) : string
    {
        $db = ConnectionFactory::makeConnection();
        $q = "SELECT note_moy FROM `serie` WHERE id = ?";
        $st = $db->prepare($q);
        $st->execute([$idSerie]);
        $data = $st->fetch();

        return $data[0];
    }

    public static function showSeriesTiles($addSerie): string
    {
        $series = "";
        while ($donnees = $addSerie->fetch()) {
            $minia = '<img src="images/' . $donnees["img"] . '" height=200px width=500px>';
            $url = '?action=display-serie&serie=' . $donnees["id"];
            $series .= '<a href=' . $url . ' class="titreSerie"><div class ="rectangleSerie">' . $donnees['titre'] . '<br>' . $minia . '</div></a></br>';
        }
        return $series;
    }
}