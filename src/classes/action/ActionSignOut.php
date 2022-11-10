<?php

namespace netvod\action;

class ActionSignOut extends Action
{

    public function execute(): string
    {
        session_destroy();
        header('Location: index.php');
        return "";
    }
}