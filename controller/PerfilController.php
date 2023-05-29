<?php

class PerfilController {
    private $perfilModel;
    private $renderer;

    public function __construct($perfilModel, $renderer) {
        $this->perfilModel = $perfilModel;
        $this->renderer = $renderer;
    }

    public function list() {

        $data["usuario"] = $this->perfilModel->generarArray();
        $this->renderer->render("perfil", $data);
    }

    public function add(){
        die("llame a add");
    }

    public function delete() {
        die('llame a delete');
    }
}