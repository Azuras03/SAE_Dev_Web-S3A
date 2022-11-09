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
        if ($note = self::getMoySerie($idSerie) == 0)
            $note = "Pas assez de données";
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT titre, descriptif, annee, date_ajout FROM serie WHERE id = ?');

        if ($stmt->execute([$idSerie])) {
            $donnees = $stmt->fetch();

            $titre = $donnees['titre'];
            $detail = 'Cette série de ' . $donnees['annee'] . ' a été ajouté sur netVOD le ' . $donnees['date_ajout'];
            $desc = $donnees['descriptif'];
        }

        return <<<HTML
            <p>Détails de la série $titre :</p>
            <a href="Index.php?action=saveseriefav&id=$idSerie">⭐ Enregistrer</a>
            <p>Note moyenne : $note</p>
            <p>$detail</p>
            <p>📃 Descriptif : $desc</p>

        HTML;
    }


    public static function displayReviewForm (int $serId): string
    {

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

        return $comment;
    }

    public static function getMoySerie(mixed $idSerie)
    {
        $db = ConnectionFactory::makeConnection();
        $q = "SELECT note_moy FROM `serie` WHERE id = ?";
        $st = $db->prepare($q);
        $st->execute([$idSerie]);
        $data = $st->fetch();

        return $data[0];
    }
}