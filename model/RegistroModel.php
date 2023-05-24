<?php

class RegistroModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function addUser(){
        return $this->database->query('INSERT INTO ');
    }

}