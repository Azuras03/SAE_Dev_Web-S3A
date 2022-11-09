<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;

class ActionSaveSeriePref extends Action
{

    public function execute(): string
    {
        $res = "<p>Série retirée 🟢</p>";
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
            if(isset($_GET['fav'])){
                $stmt2->execute();
                $res = "<p>Série ajoutée 🟢</p>";
            }
        }
        catch (\PDOException $e){
            return "<p>Une erreur est survenue 🔴</p>";
        }
        return $res;
    }
}