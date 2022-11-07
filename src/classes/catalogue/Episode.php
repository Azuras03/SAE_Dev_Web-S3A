<?php

namespace netvod\catalogue;

class Episode
{
    public int $id;
    public int $numero;
    public string $titre;
    public string $resume;
    public string $file;
    public int $duree;
    public int $serie_id;

    public function __construct(int $id, int $numero, string $titre, string $resume, string $file, int $duree, int $serie_id)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->titre = $titre;
        $this->resume = $resume;
        $this->file = $file;
        $this->duree = $duree;
        $this->serie_id = $serie_id;
    }



}