<?php
require_once ('third-party/jpgraph/src/jpgraph.php');
require_once ('third-party/jpgraph/src/jpgraph_pie.php');
require_once ('third-party/jpgraph/src/jpgraph_bar.php');
require_once ('third-party/jpgraph/src/jpgraph_line.php');

class GeneradorGrafico
{

    public function __construct()
    {

    }
    public function grafico()
    {
        $data = array(10, 8, 6, 7, 3, 2);

        // Crear una instancia del objeto Graph
        $graph = new Graph(400, 300, 'auto');
        $graph->SetScale('textlin');

        // Establecer el título del gráfico
        $graph->title->Set('Ejemplo de Gráfico de Barras');

        // Crear una instancia del objeto BarPlot
        $barplot = new BarPlot($data);

        // Establecer el color de las barras
        $barplot->SetFillColor('blue');

        // Añadir el objeto BarPlot al gráfico
        $graph->Add($barplot);

        // Mostrar los valores en la parte superior de las barras
        $barplot->value->Show();

        // Establecer las etiquetas para el eje x
        $graph->xaxis->SetTickLabels(array('A', 'B', 'C', 'D', 'E', 'F'));

        // Guardar la imagen en un archivo
        $file = 'public/graficos/grafico.png';
        $graph->Stroke($file);

        // Devolver la ruta del archivo
        return $file;
    }



}