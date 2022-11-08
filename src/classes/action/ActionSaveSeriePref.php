<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;

class ActionSaveSeriePref extends Action
{

    public function execute(): string
    {
        $sql = "DELETE FROM `user2serie` WHERE id_user = ? AND id_serie = ?;";
        $sql2 = "INSERT INTO `user2serie` VALUES (?, ?)";
        $db = ConnectionFactory::makeConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, unserialize($_SESSION['user'])->id);
        $stmt->bindParam(2, $this->id_serie);
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindParam(1, unserialize($_SESSION['user'])->id);
        $stmt2->bindParam(2, $this->id_serie);
        try{
            $stmt->execute();
            $stmt2->execute();
            if($stmt->rowCount() != 0) return '<p>La série est déjà dans vos préférences 🟠</p>';
        }
        catch (\PDOException $e){
            return "<p>Une erreur est survenue 🔴</p>";
        }
        return "<p>Insertion réussie 🟢</p>";
    }
}