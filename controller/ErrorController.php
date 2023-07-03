    <?php

    class ErrorController
    {

        private $renderer;
        private $errorModel;

        public function __construct($renderer, $errorModel)
        {
            $this->renderer = $renderer;
            $this->errorModel = $errorModel;
        }

        public function list() {
            $errorCode = $_GET['codError'] ?? null;

            if ($errorCode !== null) {
                $errorData = $this->errorModel->getErrorData($errorCode);
            } else {
                $errorData = [
                    'title' => 'Error desconocido',
                    'message' => 'Ha ocurrido un error desconocido.',
                    'buttonText' => 'Volver a Inicio',
                    'buttonLink' => '/'
                ];
            }

            $this->renderer->render('error', $errorData);
        }

    }