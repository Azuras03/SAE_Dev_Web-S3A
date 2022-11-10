<?php

namespace netvod\action;

use netvod\db\ConnectionFactory;
use netvod\render\RenderActivateAccount;
use netvod\render\RenderPage;
use netvod\user\User;

class ActionActivateAccount extends Action
{

    public function execute(): string
    {
        return (new RenderActivateAccount())->render();
    }
}
