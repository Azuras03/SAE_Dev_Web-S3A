<?php

namespace netvod\action;

use netvod\catalogue\Avis;
use netvod\catalogue\Episode;
use netvod\db\ConnectionFactory;
use netvod\user\User;

class ActionDisplayEpisode extends Action
{

    public function execute(): string
    {
        $idEpisode = $_GET['episode'];
        $idSerie = $_GET['serie'];
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT * FROM episode WHERE serie_id = ? AND numero = ?');
        $stmt->bindParam(1, $idSerie);
        $stmt->bindParam(2, $idEpisode);
        $episode = "";
        if ($stmt->execute()) {
            $donnees = $stmt->fetch();
            $id = $donnees['id'];
            $numero = $donnees['numero'];
            $titre = $donnees['titre'];
            $resume = $donnees['resume'];
            $duree = $donnees['duree'];
            $file = $donnees['file'];
            $serId = $donnees['serie_id'];
            $episode = new Episode($id, $numero, $titre, $resume, $file, $duree, $idSerie);
        }
        $renderer = new \netvod\render\RenderEpisode($episode);
        $user = unserialize($_SESSION["user"]);
        $user->addEpisodeInProgress($id);

        $comment = "";
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (!($code = User::insertAvis($_POST["commentaire"], $_POST["note"], $serId)))
                $comment .= "<small>La note doit être comprise entre 0 et 5 ! 🔴</small>";
            else if ($code == 1)
                $comment .= "<small>Avis ajouté 🟢</small>";
            else if ($code == 2)
                $comment .= "<small>Avis mis à jour 🟢</small>";
        }

        $comment .= <<<HTML
        <form method="post" action="">
            <label>Commentaire : </label>
            <textarea name="commentaire" placeholder="<Ecrivez ici>"></textarea><br>
            <label>Note : </label>
            <input type="number" name="note" placeholder="<note>" min="0" max="5"><br>
            <button type="submit">Envoyer</button>
        </form>
        HTML;

        return $renderer->render() . $comment;
    }
}