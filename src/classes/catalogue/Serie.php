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
            $fav = '<a class="favoriteButton" href="index.php?action=saveseriefav&id=' . $idSerie . '">✴</a>';
        }
        else $fav = '<a class="favoriteButton" href="index.php?action=saveseriefav&id=' . $idSerie . '&fav=ajout">⭐</a>';

        return <<<HTML
            <h3>$titre</h3>$fav
            <p>$detail</p>
            <p>Note moyenne : $note | <a href="index.php?action=review-list&id=$idSerie">Voir les commentaires</a></p>
            
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

    public static function displaySeriesList(): string
    {
        if (isset($_SESSION['user'])) {
            $db = ConnectionFactory::makeConnection();
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $addSerie = $db->prepare("SELECT titre, img, id FROM serie");

                if ($addSerie->execute())
                    $series = Serie::showSeriesTiles($addSerie);
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST ['seriesearch'])) {
                    $string = filter_var($_POST['seriesearch'], FILTER_SANITIZE_STRING);
                    $addSerie = $db->prepare("SELECT titre, img, id FROM serie WHERE titre LIKE '%$string%' OR descriptif LIKE '%$string%'");
                    $series = "";
                    if ($addSerie->execute()) {
                        if ($addSerie->rowCount() != 0)
                            $series = Serie::showSeriesTiles($addSerie);
                        else $series = "<p>Aucune série ne correspond à votre recherche</p>";
                    }
                }
                if (isset($_POST ['trierSerie'])) {
                    $series = "";
                    $query = match ($_POST['trierSerie']) {
                        'titre' => "SELECT titre, img, id FROM serie ORDER BY titre",
                        'annee' => "SELECT titre, img, id FROM serie ORDER BY annee",
                        'nbepisodes' => "SELECT serie.titre, serie.img, serie.id, COUNT(*) FROM serie INNER JOIN episode ON serie.id = episode.serie_id GROUP BY serie.titre, serie.img, serie.id ORDER BY COUNT(*)",
                        'note' => "SELECT titre, img, id FROM serie ORDER BY note_moy",
                        default => "SELECT titre, img, id FROM serie",
                    };
                    $addSerie = $db->prepare($query . ' ' . $_POST['triCrDr']);
                    if ($addSerie->execute()) {
                        $series = Serie::showSeriesTiles($addSerie);
                    } else {
                        $series = "<p>Une erreur est survenue</p>";
                    }
                }
            }

            if (!isset($_POST["seriesearch"])) $_POST["seriesearch"] = "";
            return <<<HTML
                    <div class = "container"><h3>Liste des séries :</h3>
                    <form method = "post">
                        <input type="search" name="seriesearch" placeholder="Rechercher une série" value="{$_POST["seriesearch"]}">
                        <select name="trierSerie" id="trierSerie" >
                            <option value="" disabled selected>Trier par...</option>
                            <option value="annee">Année</option>
                            <option value="titre">Titre</option>
                            <option value="nbepisodes">NbEp</option>
                            <option value="note">Note</option>
                        </select>
                        <select name="triCrDr" id="triCrDr" >
                            <option value="ASC">🔺</option>
                            <option selected value="DESC">🔻</option>
                        </select>
                        <button>🔎</button>
                    </form>
                  <p class="listeSerie">' . $series . '</p></div>
                  HTML;
        }
        return "";
    }
}