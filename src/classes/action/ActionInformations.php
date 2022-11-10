<?php

namespace netvod\action;

class ActionInformations extends Action
{

    public function execute(): string
    {
        return "<p class='informations'>Site créé par : <ul><li>Thomas Robineau</li> <li>Nicolas Russo</li> <li>Hugo Collin</li><li>Emilien Hergott</li></p>";
    }
}