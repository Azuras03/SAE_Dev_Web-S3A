<?php

namespace netvod\review;

use netvod\db\ConnectionFactory;
use netvod\user\User;

class Review
{
    public static function displayReviews(int $serId) : string
    {
        $db = ConnectionFactory::makeConnection();
        $q = "SELECT email, note, comment FROM `avis`, user
                WHERE avis.id_user = user.id
                AND id_serie = ?";
        $st = $db->prepare($q);
        $st->execute([$serId]);

        $html = "<h4>Tous les avis</h4>";
        $init = $html;
        foreach ($st as $data) {
            $html .= <<<HTML
                    <article>
                        <small> <b>{$data['email']}</b> ({$data['note']}/5) a écrit :</small>
                        <p></p>
                        <p>{$data['comment']}</p>
                    </article>
                    HTML;
        }
        if ($html === $init) $html .= "Aucun avis pour l'instant...";
        return $html;
    }

    public static function displayReviewForm(int $idSerie): string
    {

        $comment = "<h4>Ajouter un avis</h4>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!($code = self::insertReview($_POST["commentaire"], $_POST["note"], $idSerie)))
                $comment .= "<small>La note doit être comprise entre 1 et 5 ! 🔴</small>";
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

        return $comment;
    }

    public static function insertReview($commentaire, $note, $idSerie) : int|bool
    {
        $db = ConnectionFactory::makeConnection();
        $commentaire = filter_var($commentaire, FILTER_SANITIZE_SPECIAL_CHARS);
        $note = filter_var($note, FILTER_SANITIZE_NUMBER_INT);

        if ($note < 0 || $note > 5) return false;

        $q = "SELECT count(*) FROM avis WHERE id_user = ? AND id_serie = ?";
        $st = $db->prepare($q);
        $st->execute([unserialize($_SESSION['user'])->id, $idSerie]);
        $data = $st->fetch();

        if ($data[0] > 0)
        {
            $q = "UPDATE avis
                SET note = ?, comment = ?
                WHERE id_user = ?
                AND id_serie = ?";
            $st = $db->prepare($q);
            $st->execute([$note, $commentaire, unserialize($_SESSION['user'])->id, $idSerie]);
            $res = 2;
        }
        else
        {
            $q = "INSERT INTO avis(id_user, id_serie, note, comment) VALUES (?,?,?,?)";
            $st = $db->prepare($q);
            $st->execute([unserialize($_SESSION['user'])->id, $idSerie, $note, $commentaire]);
            $res = 1;
        }
        self::updateAvgSerieReview($idSerie);
        return $res;
    }

    public static function updateAvgSerieReview(int $idSerie): void
    {
        $db = ConnectionFactory::makeConnection();
        $q = "UPDATE serie 
               SET serie.note_moy = (SELECT avg(note) FROM avis WHERE id_serie = ?)
               WHERE id = ?";
        $st = $db->prepare($q);
        $st->execute([$idSerie, $idSerie]);
    }


}