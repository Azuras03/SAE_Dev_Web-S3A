<?php

namespace netvod\action;

use netvod\catalogue\Episode;
use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;
use netvod\review\Review;

class ActionDisplaySerie extends Action
{

    public function execute(): string
    {
        if (isset($_GET['serie'])) {
            $review = Review::displayReviewForm($_GET['serie']); //Astuce pour que la note soit actualisée au chargement de la page.
            $html = Serie::displaySerie();
            $html .= Episode::displayDataEpisode();
            $html .= $review;
            return $html;
        } else {
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

                return '<div class = "container"><h3>Liste des séries :</h3>
                    <form method = post>
                        <input type="search" name="seriesearch" placeholder="Rechercher une série" ">
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
                  <p class="listeSerie">' . $series . '</p></div>';

            }
        }
        return "";
    }
}