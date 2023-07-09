<?php
require_once "./third-party/jpgraph/src/jpgraph.php";
require_once "./third-party/jpgraph/src/jpgraph_bar.php";
require_once './third-party/jpgraph/src/jpgraph_pie.php';
require_once './third-party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

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
       $data ['vistaReporte'] = true;
       $this->renderer->render('admin',$data);
    }

    public function generarGraficos()
    {
        if(isset($_POST['filtro']) && isset($_POST['buscar'])){
            $filtro = $_POST['filtro'];
        } else{
            $filtro = 'Month';
        }

        $data['jugador']= $this->reporteModel->cantidadDeUsuarios($filtro);
        $data['nuevos']= $this->reporteModel->cantidadDeUsuariosNuevos($filtro);
        $data['genero']= $this->reporteModel->cantidadJugadoresPorGenero($filtro);
        $data['edad']= $this->reporteModel->cantidadJugadoresPorEdad($filtro);
        $data['pais']= $this->reporteModel->jugadoresPorPais($filtro);
        $data['partidas']= $this->reporteModel->getCantidadDePartidas($filtro);
        $data['preg_totales']= $this->reporteModel->getCantPreguntasAdmin($filtro);
        $data['preg_nuevas']= $this->reporteModel->getCantNuevas($filtro);
        $data['preg_usr']= $this->reporteModel->getRespuestaPorUsuario();

        $this->getGraficoBarra($data['jugador'],'cantidadJugadores.png','Cantidad de Jugadores');
        $this->getGraficoBarra($data['nuevos'],'cantidadJugadoresNuevos.png','Cantidad de Jugadores Nuevos');
        $this->getGraficoPie($data['genero'], 'cantidadPorGenero.png', 'Jugadores por genero');
        $this->getGraficoPie($data['edad'],'cantidadPorGrupoEtario.png','Cantidad por Grupo Etario');
        $this->getGraficoPie($data['pais'],'cantidadPorPais.png','Cantidad de Jugadores por Pais');
        $this->getGraficoBarra($data['partidas'],'cantidadPartidas.png','Cantidad de Partidas');
        $this->getGraficoBarra($data['preg_totales'],'preguntasTotales.png','Preguntas Totales');
        $this->getGraficoBarra($data['preg_nuevas'],'preguntasNuevas.png','Cantidad de Preguntas nuevas');
        $this->getGraficoBarra($data['preg_usr'], 'porcentajeUsuario.png','Porcentaje Correctas por Usuario');


    }

    public function mostrarGrafico1(){
        if(isset($_POST['filtro']) && isset($_POST['buscar'])){
            $filtro = $_POST['filtro'];
        } else{
            $filtro = 'Year';
        }
        $dataGraf1= $this->reporteModel->cantidadDeUsuarios($filtro);
        $this->getGraficoBarra($dataGraf1,'cantidadJugadores.png','Cantidad de Jugadores');
    }
    public function mostrarGrafico2(){
        if(isset($_POST['filtro']) && isset($_POST['buscar'])){
            $filtro = $_POST['filtro'];
        } else{
            $filtro = 'Year';
        }
        $dataGraf2= $this->reporteModel->cantidadDeUsuariosNuevos($filtro);
        $this->getGraficoBarra($dataGraf2,'cantidadJugadoresNuevos.png','Cantidad de Jugadores Nuevos');

    }

    public function mostrarGrafico3(){
        if(isset($_POST['filtro']) && isset($_POST['buscar'])){
            $filtro = $_POST['filtro'];
        } else{
            $filtro = 'Year';
        }
        $dataGraf3= $this->reporteModel->cantidadJugadoresPorGenero();
        $this->getGraficoPie($dataGraf3, 'cantidadPorGenero.png', 'Jugadores por genero');

    }

    public function mostrarGrafico4(){
        if(isset($_POST['filtro']) && isset($_POST['buscar'])){
            $filtro = $_POST['filtro'];
        } else{
            $filtro = 'Year';
        }
        $dataGraf4= $this->reporteModel->cantidadJugadoresPorEdad();
        $this->getGraficoPie($dataGraf4,'cantidadPorGrupoEtario.png','Cantidad por Grupo Etario');

    }
    public function mostrarGrafico5()
    {
        if (isset($_POST['filtro']) && isset($_POST['buscar'])) {
            $filtro = $_POST['filtro'];
        } else {
            $filtro = 'Year';
        }
        $dataGraf5= $this->reporteModel->jugadoresPorPais();
        $this->getGraficoPie($dataGraf5,'cantidadPorPais.png','Cantidad de Jugadores por Pais');

    }

    public function mostrarGrafico6()
    {
        if (isset($_POST['filtro']) && isset($_POST['buscar'])) {
            $filtro = $_POST['filtro'];
        } else {
            $filtro = 'Year';
        }
        $dataGraf6= $this->reporteModel->getCantidadDePartidas($filtro);
        $this->getGraficoBarra($dataGraf6,'cantidadPartidas.png','Cantidad de Partidas');

    }

    public function mostrarGrafico7(){
        if (isset($_POST['filtro']) && isset($_POST['buscar'])) {
            $filtro = $_POST['filtro'];
        } else {
            $filtro = 'Year';
        }
        $dataGraf7= $this->reporteModel->getCantPreguntasAdmin($filtro);
        $this->getGraficoBarra($dataGraf7,'preguntasTotales.png','Preguntas Totales');

    }
    public function mostrarGrafico8(){
        if (isset($_POST['filtro']) && isset($_POST['buscar'])) {
            $filtro = $_POST['filtro'];
        } else {
            $filtro = 'Year';
        }
        $dataGraf8= $this->reporteModel->getCantNuevas();
        $this->getGraficoBarra($dataGraf8,'preguntasNuevas.png','Cantidad de Preguntas nuevas');
    }

    public function mostrarGrafico9()
    {
        if (isset($_POST['filtro']) && isset($_POST['buscar'])) {
            $filtro = $_POST['filtro'];
        } else {
            $filtro = 'Year';
        }
        $datatGraf9 = $this->reporteModel->getRespuestaPorUsuario();
        $this->getGraficoBarra($datatGraf9, 'porcentajeUsuario.png', 'Porcentaje Correctas por Usuario');
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
        return $this->getImagenGrafico($graph,$nombreImg);

    }

    public function getGraficoPie($data,$filename,$titulo){

        $data1y = array();
        $dataName = array();

        for ($i = 0; $i < count($data); $i++) {
            $data1y[] = $data[$i]["cantidad"];
            $dataName[] = $data[$i]["filtro1"];
        }
        $graph = new PieGraph(350,250);
        $graph->SetScale("textlin");
        $theme_class = new UniversalTheme;
        $graph->SetTheme($theme_class);
        $graph->SetBox(true);
        $graph->xaxis->SetTickLabels($dataName);

        $pieplot = new PiePlot($data1y);
        $pieplot->SetStartAngle(30);
        $pieplot->SetLegends($dataName);
        $graph->xaxis->SetTickLabels($dataName);
        $pieplot->SetSliceColors(array('red', 'blue', 'green'));
        $pieplot->value->SetFont(FF_ARIAL, FS_NORMAL, 12);
        $pieplot->value->SetColor('black');
        $pieplot->value->Show();

        $graph->Add($pieplot);

        $graph->title->Set($titulo);
        $nombreImg = 'public/graficos/'.$filename;
       return $this->getImagenGrafico($graph,$nombreImg);

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

    public function generar_pdf()
    {
        $imagenNombre = $_POST['imagenNombre'];
        $imagenPath = 'public/graficos/' . $imagenNombre . '.png';

        $dompdf = new Dompdf();
        $dompdf->loadHtml('<img src="' . $imagenPath . '">');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream($imagenNombre.".pdf" , ['Attachment' => 0]);

        // Devolver una respuesta al cliente
        header('Content-Type: application/pdf');
        header ('Content-Disposition:inline; filename ='.$imagenNombre.'.pdf');
        $dompdf->output();
        //echo json_encode(['success' => true]);
        //header('Location: ' . $pdfPath);
        //exit();
    }






}