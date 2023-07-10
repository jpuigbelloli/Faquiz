<?php
require_once "third-party/jpgraph/src/jpgraph.php";
require_once "third-party/jpgraph/src/jpgraph_bar.php";
require_once 'third-party/jpgraph/src/jpgraph_pie.php';
require_once 'third-party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class ReporteController{
    private $reporteModel;
    private $renderer;
    private $pdf;

    public function __construct($reporteModel, $renderer, $pdf){
        $this->reporteModel = $reporteModel;
        $this->renderer     = $renderer;
        $this->pdf = $pdf;
    }

    public function list(){

        if(!isset($_SESSION['logueado']) || Usuario::getROL()!=='ADMINISTRADOR'){
            header('Location:/lobby');
            exit();
        }

       $data ['vistaReporte'] = true;
       $data ['logueado'] = true;
       $data ['usuario']['user_logueado'] = $_SESSION['usuario'];
       $this->renderer->render('admin',$data);
    }

    public function generarGraficos()
    {
        if(isset($_POST['filtro']) && $_POST['buscar']){
            $filtro = $_POST['filtro'];
        }else{
            $filtro = 'month';
            Logger::info($filtro);
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

        $data['grafico1'] =  $this->getGraficoBarra($data['jugador'],'cantidadJugadores.png','Cantidad de Jugadores');
        $data['grafico2'] =  $this->getGraficoBarra($data['nuevos'],'cantidadJugadoresNuevos.png','Cantidad de Jugadores Nuevos');
        $data['grafico3'] =$this->getGraficoPie($data['genero'], 'cantidadPorGenero.png', 'Jugadores por genero');
        $data['grafico4'] =$this->getGraficoPie($data['edad'],'cantidadPorGrupoEtario.png','Cantidad por Grupo Etario');
        $data['grafico5'] =$this->getGraficoPie($data['pais'],'cantidadPorPais.png','Cantidad de Jugadores por Pais');
        $data['grafico6'] =$this->getGraficoBarra($data['partidas'],'cantidadPartidas.png','Cantidad de Partidas');
        $data['grafico7'] =$this->getGraficoBarra($data['preg_totales'],'preguntasTotales.png','Preguntas Totales');
        $data['grafico8'] =$this->getGraficoBarra($data['preg_nuevas'],'preguntasNuevas.png','Cantidad de Preguntas nuevas');
        $data['grafico9'] = $this->getGraficoBarra($data['preg_usr'], 'porcentajeUsuario.png','Porcentaje Correctas por Usuario');

        $this->load->view('admin',$data);
    }

    public function mostrarGrafico1(Request $request){
        $filtro = $request->input('filtro');
        $dataGraf1= $this->reporteModel->cantidadDeUsuarios($filtro);
        $this->getGraficoBarra($dataGraf1,'cantidadJugadores.png','Cantidad de Jugadores');
        $rutaGrafico = 'public/graficos/cantidadJugadores.png';

        return response()->json([
            'imageUrl' => $rutaGrafico
        ]);
    }

    public function mostrarGrafico2(Request $request){
        $filtro = $request->input('filtro');

        $dataGraf2= $this->reporteModel->cantidadDeUsuariosNuevos($filtro);
        $this->getGraficoBarra($dataGraf2,'cantidadJugadoresNuevos.png','Cantidad de Jugadores Nuevos');

        $rutaGrafico = 'public/graficos/cantidadJugadoresNuevos.png';

        return response()->json([
            'imageUrl' => $rutaGrafico
        ]);

    }

    public function mostrarGrafico3(Request $request){
        $filtro = $request->input('filtro');
        $dataGraf3= $this->reporteModel->cantidadJugadoresPorGenero($filtro);
        $this->getGraficoPie($dataGraf3, 'cantidadPorGenero.png', 'Jugadores por genero');

        $rutaGrafico = 'public/graficos/cantidadPorGenero.png';

        return response()->json([
            'imageUrl' => $rutaGrafico
        ]);

    }

    public function mostrarGrafico4(Request $request){
        $filtro = $request->input('filtro');
        $dataGraf4= $this->reporteModel->cantidadJugadoresPorEdad($filtro);
        $this->getGraficoPie($dataGraf4,'cantidadPorGrupoEtario.png','Cantidad por Grupo Etario');

        $rutaGrafico = 'public/graficos/cantidadPorGrupoEtario.png';

        return response()->json([
            'imageUrl' => $rutaGrafico
        ]);

    }
    public function mostrarGrafico5(Request $request)
    {
        $filtro = $request->input('filtro');
        $dataGraf5= $this->reporteModel->jugadoresPorPais($filtro);
        $this->getGraficoPie($dataGraf5,'cantidadPorPais.png','Cantidad de Jugadores por Pais');

        $rutaGrafico = 'public/graficos/cantidadPorPais.png';

        return response()->json([
            'imageUrl' => $rutaGrafico
        ]);
    }

    public function mostrarGrafico6(Request $request)
    {
        $filtro = $request->input('filtro');
        $dataGraf6= $this->reporteModel->getCantidadDePartidas($filtro);
        $this->getGraficoBarra($dataGraf6,'cantidadPartidas.png','Cantidad de Partidas');

        $rutaGrafico = 'public/graficos/cantidadPartidas.png';

        return response()->json([
            'imageUrl' => $rutaGrafico
        ]);
    }

    public function mostrarGrafico7(Request $request){
        $filtro = $request->input('filtro');
        $dataGraf7= $this->reporteModel->getCantPreguntasAdmin($filtro);
        $this->getGraficoBarra($dataGraf7,'preguntasTotales.png','Preguntas Totales');

        $rutaGrafico = 'public/graficos/preguntasTotales.png';

        return response()->json([
            'imageUrl' => $rutaGrafico
        ]);

    }
    public function mostrarGrafico8(Request $request){
        $filtro = $request->input('filtro');
        $dataGraf8= $this->reporteModel->getCantNuevas();
        $this->getGraficoBarra($dataGraf8,'preguntasNuevas.png','Cantidad de Preguntas nuevas');


        $rutaGrafico = 'public/graficos/preguntasNuevas.png';

        return response()->json([
            'imageUrl' => $rutaGrafico
        ]);
    }

    public function mostrarGrafico9()
    {
        $datatGraf9 = $this->reporteModel->getRespuestaPorUsuario();
        $this->getGraficoBarra($datatGraf9, 'porcentajeUsuario.png', 'Porcentaje Correctas por Usuario');
        $rutaGrafico = 'public/graficos/porcentajeUsuario.png';

        return response()->json([
            'imageUrl' => $rutaGrafico
        ]);
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
        $img =  $this->getImagenGrafico($graph,$nombreImg);
        $graph->img->Headers();
        $graph->img->Stream();
        return $img;

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
       $img= $this->getImagenGrafico($graph,$nombreImg);

       $graph->img->Headers();
       $graph->img->Stream();
        return $img;
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

    public function generarPDF(){
        $localhost = $_SERVER['HTTP_HOST'];
        $ruta = $_GET['src'];
        $nombrePDF = $_GET['name'];
        $imagen = '<img src="http://'.$localhost.$ruta.'">';
        $this->pdf->output($imagen,$nombrePDF);

    }

}