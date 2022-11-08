<?php

namespace netvod\action;

abstract class Action {

    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;
    protected ?string $id_serie = null;

    public function __construct(){

        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
        $this->id_serie = $_GET['id'] ?? null;
    }

    abstract public function execute() : string;

}