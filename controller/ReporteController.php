<?php
require_once "./third-party/jpgraph/src/jpgraph.php";
require_once "./third-party/jpgraph/src/jpgraph_bar.php";

class ReporteController{
    private $reporteModel;
    private $renderer;

    public function __construct($reporteModel, $renderer){
        $this->reporteModel = $reporteModel;
        $this->renderer     = $renderer;
    }

    public function list(){
        if(isset($_POST['filtro']) && isset($_POST['buscar'])){
            $filtro = $_POST['filtro'];
        }
        $data['jugador'] = $this->reporteModel->cantidadDeUsuarios($filtro);
        $data['nuevos'] = $this->reporteModel->cantidadDeUsuariosNuevos($filtro);
        $data['genero'] = $this->reporteModel->cantidadJugadoresPorGenero();
        $data['edad'] = $this->reporteModel->cantidadJugadoresPorEdad();
        $data['partidas'] = $this->reporteModel->getCantidadDePartidas($filtro);
        $data['preg_totales'] = $this->reporteModel->getCantPreguntasAdmin();
        $data['preg_nuevas'] = $this->reporteModel->getCantNuevas();

        $this->renderer->render('admin',$data);
    }

    public function getGraficoJugadores(){

        if(isset($_POST['filtro'])){
            $filtro = $_POST['filtro'];
        }

        $data['jugador'] = $this->reporteModel->cantidadDeUsuarios($filtro);

        $data1y = array();
        $dataName = array();


        for ($i = 0; $i < count($data); $i++) {
            $data1y[] = $data[$i]["cantidad"];
            $dataName = $data[$i]["filtro"];
        }
        $graph = $this->getGraph();
        $this->getConfigGraph($graph,$dataName);

        $b1plot = $this->getBarPlot($data1y);
        $graph->Add($b1plot);

        $graph->title->Set('Cantidad de Jugadores');

        $graph->Stroke(_IMG_HANDLER);

        $nombreImg = 'public/graficos/cantidadJugadores.png';

        $graph->img->Stream($nombreImg);

        $graph->img->Headers();
        $graph->img->Stream();


    }

    public function getGraph(){
        $graph = new Graph(350, 200, 'auto');
        $graph->SetScale("textlin");
        $theme_class = new UniversalTheme;
        $graph->SetTheme($theme_class);

        return $graph;
    }

    public function getConfigGraph($graph, $dataName){
        $graph->SetBox(false);
        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels($dataName);
        return $graph;
    }

    public function getBarPlot($data1y){
        $b1plot = new BarPlot($data1y);
        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#cc1111");
        $b1plot->value->Show();

        return $b1plot;
    }

}