<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;

class ActionDetailSerie extends Action
{

    public function execute(): string
    {
        $idSerie = $_GET['id'];
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare('SELECT descriptif FROM serie WHERE id = ?');
        $stmt->bindParam(1, $idSerie);
        $desc = "";

        if ($stmt->execute()) {
            $donnees = $stmt->fetch();
            $desc = $donnees['descriptif'];
        }

        return <<<HTML

            <p>Descriptif : $desc</p>

        HTML;

    }
}