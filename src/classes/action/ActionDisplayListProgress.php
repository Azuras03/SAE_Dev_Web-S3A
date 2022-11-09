<?php

namespace netvod\action;

use netvod\catalogue\Episode;
use netvod\db\ConnectionFactory;

class ActionDisplayListProgress extends Action
{

    public function execute(): string
    {
        $q = "SELECT serie_id, numero, episode.titre AS ep_titre FROM userprogressepisode, episode, serie
            WHERE userprogressepisode.id_episode = episode.id
            AND episode.serie_id = serie.id
            AND userprogressepisode.id_user = ?";

        $db = ConnectionFactory::makeConnection();
        $html = "<ul>";
        $init = "<ul></ul>";

        $st = $db->prepare($q);
        $st->bindParam(1, unserialize($_SESSION['user'])->id);
        if ($st->execute()) {
            while ($donnees = $st->fetch()) {
                $url = '?action=display-episode&serie=' . $donnees["serie_id"] . "&episode=" . $donnees["numero"];
                $html .= '<li><a href=' . $url . '>' . $donnees['ep_titre'] . '</a></li>';
            }
            $html .= "</ul>";
        }
        if ($init === $html) $html = "Commencez à regarder des épisodes et ils s'afficheront ici !";

        return '<div class = "container"><h3>Liste de vos épisodes en cours 🕰️ :</h3> <p class="listeSerie">'.$html.'</p></div>';


    }
}