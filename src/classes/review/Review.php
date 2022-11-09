<?php

namespace netvod\user;

use netvod\db\ConnectionFactory;

class Review
{
    public static function displayComments(int $serId)
    {
        $db = ConnectionFactory::makeConnection();
        $q = "SELECT email, note, comment FROM `avis`, user
                WHERE avis.id_user = user.id
                AND id_serie = ?";
        $st = $db->prepare($q);
        $st->execute([$serId]);

        $html = "";
        foreach ($st->fetchAll(\PDO::FETCH_ASSOC) as $data) {
            $html .= <<<HTML
                    <article>
                        <h4>$data->email</h4>
                    </article>
                    HTML;
        }
        return $html;
    }

    public static function displayReviewForm(int $serId): string
    {

        $comment = "";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!($code = User::insertAvis($_POST["commentaire"], $_POST["note"], $serId)))
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


}