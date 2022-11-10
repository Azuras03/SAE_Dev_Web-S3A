<?php

namespace netvod\list;

use netvod\db\ConnectionFactory;

class ProgressList
{
    public static function displayProgress(): string
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
        if ($init === $html) $html = "Commencez à regarder des épisodes et ils s'afficheront ici ! 🍿";
        return $html;
    }
}