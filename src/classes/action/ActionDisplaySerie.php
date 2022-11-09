<?php

namespace netvod\action;

use netvod\catalogue\Episode;
use netvod\catalogue\Serie;
use netvod\db\ConnectionFactory;

class ActionDisplaySerie extends Action
{

    public function execute(): string
    {
        if (isset($_GET['serie'])) {
            $html = Serie::displaySerie();
            $html .= Episode::displayDataEpisode();
            return $html;
        }
        else {
            if (isset($_SESSION['user'])) {
                $db = ConnectionFactory::makeConnection();
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                    $addSerie = $db->prepare("SELECT titre, img, id FROM serie");
                    $series = "";
                    if ($addSerie->execute()) {
                        while ($donnees = $addSerie->fetch()) {
                            $minia = '<img src="images/' . $donnees["img"] . '" height=200px width=500px>';
                            $url = '?action=display-serie&serie=' . $donnees["id"];
                            $series .= '<a href=' . $url . ' class="titreSerie"><div class ="rectangleSerie">' . $donnees['titre'] . '<br>' . $minia . '</div></a></br>';
                        }
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $string = filter_var($_POST['seriesearch'], FILTER_SANITIZE_STRING);
                    $addSerie = $db->prepare("SELECT titre, img, id FROM serie WHERE titre LIKE '%$string%' OR descriptif LIKE '%$string%'");
                    $series = "";
                    if ($addSerie->execute()) {
                        if ($addSerie->rowCount() != 0) {
                            while ($donnees = $addSerie->fetch()) {
                                $minia = '<img src="images/' . $donnees["img"] . '" height=200px width=500px>';
                                $url = '?action=display-serie&serie=' . $donnees["id"];
                                $series .= '<a href=' . $url . ' class="titreSerie"><div class ="rectangleSerie">' . $donnees['titre'] . '<br>' . $minia . '</div></a></br>';
                            }
                        } else {
                            $series = "<p>Aucune série ne correspond à votre recherche</p>";
                        }
                    }
                }

                return '<div class = "container"><h3>Liste des séries :</h3> 
                    <form method = post>
                        <input type="search" name="seriesearch" name="q">
                        <button>🔎</button>
                    </form>
                  <p class="listeSerie">' . $series . '</p></div>';

            }
        }
        return "";
    }
}