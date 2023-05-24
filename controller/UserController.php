<?php

class UserController {

    private $renderer;

    public function __construct($renderer) {
        $this->renderer = $renderer;
    }

    public function list() {
        $a = array("a");
        $this->renderer->render('registro', $a);
    }

    public function addUser(){

        $this->renderer->render('registro', );
    }
}