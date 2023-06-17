<?php

class Partida
{
    private $puntaje;

    public function __construct()
    {
        $this->puntaje = 0;
    }

    public function getPuntaje(){
        return $this->puntaje;
    }
}