<?php

class LaBandaController {

    private $renderer;

    public function __construct($renderer) {
        $this->renderer = $renderer;
    }

    public function list() {
        $a = array("a");
        $this->renderer->render('labanda', $a);
    }
}