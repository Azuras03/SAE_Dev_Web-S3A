<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;

class ActionDisplayCatalogue extends Action
{

    public function execute(): string
    {
        if (isset($_SESSION['user'])) {
            $db = ConnectionFactory::makeConnection();
            $stmt = $db->prepare("SELECT titre FROM serie");
            $series = "";
            if ($stmt->execute()) {
                while ($donnees = $stmt->fetch()) {
                    $series .= $donnees['titre'] . '</br>';
                }
            }
            return <<<HTML
                    <p>$series</p>
                HTML;
        }
        return "";
    }
}