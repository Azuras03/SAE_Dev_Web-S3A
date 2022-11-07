<?php

namespace netvod\dispatch;

class Dispatch
{

    public ?string $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? null;
    }

    public function run(): void{


        //TODO


    }

}