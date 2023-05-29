<?php

class PerfilModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getData() {
        return $this->database->query('SELECT * FROM usuario');
    }
}