<?php

class ErrorController{
    private $renderer;
    public function __construct($renderer)
    {
        $this->renderer = $renderer;
    }

    public function list() {
        $a = array("a");
        $this->renderer->render('error', $a);
    }


}