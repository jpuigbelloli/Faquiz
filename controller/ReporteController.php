<?php
 require_once "./third-party/jpgraph/src/jpgraph.php";
 require_once "./third-party/jpgraph/src/jpgraph_bar.php";
require_once './third-party/jpgraph/src/jpgraph_pie.php';

class ReporteController{
    private $reporteModel;
    private $renderer;

    public function __construct($reporteModel, $renderer){
        $this->reporteModel = $reporteModel;
        $this->renderer     = $renderer;
    }

    public function list(){

        if(!isset($_SESSION['logueado']) || Usuario::getROL()!=='ADMINISTRADOR'){
            header('Location:/lobby');
            exit();
        }

        if(isset($_POST['filtro']) && isset($_POST['buscar'])){
            $filtro = $_POST['filtro'];
        }
        else {
            $filtro = "Month";
        }

        $data['jugador'] = $this->reporteModel->cantidadDeUsuarios($filtro);
        $data['nuevos'] = $this->reporteModel->cantidadDeUsuariosNuevos($filtro);
        $data['genero'] = $this->reporteModel->cantidadJugadoresPorGenero();
        $data['edad'] = $this->reporteModel->cantidadJugadoresPorEdad();
        $data['pais'] = $this->reporteModel->jugadoresPorPais();
        $data['partidas'] = $this->reporteModel->getCantidadDePartidas($filtro);
        $data['preg_totales'] = $this->reporteModel->getCantPreguntasAdmin($filtro);
        $data['preg_nuevas'] = $this->reporteModel->getCantNuevas($filtro);
        $data['preg_usr']   = $this->reporteModel->getRespuestaPorUsuario();

       $this->getGraficoBarra($data['jugador'],'cantidadJugadores.png','Cantidad de Jugadores');
       $this->getGraficoBarra($data['nuevos'],'cantidadJugadoresNuevos.png','Cantidad de Jugadores Nuevos');
       $this->getGraficoPie($data['genero'], 'cantidadPorGenero.png', 'Jugadores por genero');
       $this->getGraficoPie($data['edad'],'cantidadPorGrupoEtario.png','Cantidad por Grupo Etario');
       $this->getGraficoPie($data['pais'],'cantidadPorPais.png','Cantidad de Jugadores por Pais');
       $this->getGraficoBarra($data['partidas'],'cantidadPartidas.png','Cantidad de Partidas');
       $this->getGraficoBarra($data['preg_totales'],'preguntasTotales.png','Preguntas Totales');
       $this->getGraficoBarra($data['preg_nuevas'],'preguntasNuevas.png','Cantidad de Preguntas nuevas');
       $this->getGraficoBarra($data['preg_usr'], 'porcentajeUsuario.png','Porcentaje Correctas por Usuario');

       $this->renderer->render('admin',$data);
    }

    public function getGraficoBarra($data,$filename,$titulo){

        $data1y = array();
        $dataName = array();

        for ($i = 0; $i < count($data); $i++) {
            $data1y[] = $data[$i]["cantidad"];
            $dataName[] = $data[$i]["filtro"];
        }

        $graph = $this->getGraph();
        $this->getConfigGraph($graph,$dataName);

        $b1plot = $this->getBarPlot($data1y);
        $graph->Add($b1plot);

        $graph->title->Set($titulo);
        $nombreImg = 'public/graficos/'.$filename;
        $this->getImagenGrafico($graph,$nombreImg);
       // $nombreImg = 'public/graficos/'.$filename;
        $graph->Stroke();


    }

    public function getGraficoPie($data,$filename,$titulo){

        $data1y = array();
        $dataName = array();

        for ($i = 0; $i < count($data); $i++) {
            $data1y[] = $data[$i]["cantidad"];
            $dataName[] = $data[$i]["filtro"];
        }
        $graph = new PieGraph(400, 300,'auto');
        $graph->SetScale("textlin");
        $theme_class = new UniversalTheme;
        $graph->SetTheme($theme_class);
        $graph->SetBox(false);
        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels($dataName);

        $pieplot = new PiePlot($data1y);
        $pieplot->SetStartAngle(30);
        $pieplot->SetLegends(array_keys($dataName));
        $pieplot->SetSliceColors(array('red', 'blue', 'green'));
        $pieplot->value->SetFont(FF_ARIAL, FS_NORMAL, 12);
        $pieplot->value->SetColor('black');
        $pieplot->value->Show();

        $graph->Add($pieplot);

        $graph->title->Set($titulo);
        $nombreImg = 'public/graficos/'.$filename;
        $this->getImagenGrafico($graph,$nombreImg);
        $graph->Stroke();
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

    public function getImagenGrafico($graph,$fileName){
        $graph->Stroke(_IMG_HANDLER);
        $image = $graph->img->Stream($fileName);
        return $image;
    }

}