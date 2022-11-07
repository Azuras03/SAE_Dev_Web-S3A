<?php

//Configuration
require_once 'vendor/autoload.php';

use netvod\dispatch\Dispatcher;
use netvod\db\ConnectionFactory;

session_start();
ConnectionFactory::setConfig('./db.config.ini');


//Affichage
$dispatcher = new Dispatcher();
$dispatcher->run();