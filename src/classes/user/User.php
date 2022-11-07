<?php

namespace netvod\user;

use netvod\exception\NonEditablePropertyException;

class User
{

    public string $id;
    public string $email;
    public string $password;
    public array $infos = [];

    public function __construct(string $id, string $email, string $password, array $infos = [])
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->infos = $infos;
    }

    public function __set(string $at, mixed $value): void
    {
        if (property_exists($this, $at)) {
            $this->$at = $value;
        } else {
            throw new NonEditablePropertyException($at);
        }
    }

    public function afficherInfos(): string
    {
        $retour = "";
        foreach ($this->infos as $key => $value) {
            $retour .= $key . ' : ' . $value . '\n';
        }
        return $retour;
    }

}