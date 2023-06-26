<?php

class RevisarPreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    //trae las que estan en estado pendiente de revision
    public function getPreguntasSugeridasARevisar(){
        $sql = "SELECT PS.id_pregunta_sugerida AS id,PS.pregunta,C.descripcion as categoria,PS.respuesta1,PS.respuesta2,PS.respuesta3,PS.respuesta4,PS.correcta
                FROM pregunta_sugerida PS
                JOIN categoria C
                ON PS.id_categoria = C.id_categoria
                WHERE id_estado = 1";
        return $this->database->query_assoc($sql);
    }

    public function aprobarPregunta($id_pregunta_sugerida){
        $sql = "UPDATE pregunta_sugerida
                SET id_estado = 2
                WHERE id_pregunta_sugerida = '$id_pregunta_sugerida'";

        return $this->database->query($sql);
    }

    public function rechazarPregunta($id_pregunta_sugerida){
        $sql = "UPDATE pregunta_sugerida
                SET id_estado = 3
                WHERE id_pregunta_sugerida = '$id_pregunta_sugerida'";

        return $this->database->query($sql);
    }

    public function agregarPregunta($categoria,$pregunta){
            $sql = "INSERT INTO pregunta(id_categoria,pregunta,agregada)
                    VALUES ('$categoria','$pregunta',true)";

            return $this->database->query_ultimo_id($sql);
    }

    public function agregarRespuesta($preguntaID,$respuesta,$correcta){
        $sql = "INSERT INTO respuesta(id_pregunta,respuesta,correcta)
                VALUES ('$preguntaID','$respuesta','$correcta')";

        return $this->database->query($sql);
    }

    public function getPreguntasReportadasARevisar(){
        $sql = "SELECT pr.id,pr.id_pregunta_reportada, p.pregunta, c.descripcion AS categoria, r.id_respuesta, r.respuesta, r.correcta, m.descripcion AS motivo
                FROM pregunta_reportada pr
                JOIN pregunta p ON pr.id_pregunta_reportada = p.id_pregunta
                JOIN categoria c ON p.id_categoria = c.id_categoria
                JOIN respuesta r ON p.id_pregunta = r.id_pregunta
                JOIN motivo m ON pr.id_motivo = m.id_motivo
                WHERE PR.revisada = 0";
        $resultado = $this->database->query_assoc($sql);

        // Conversion
        $convertedArray = array();
        foreach ($resultado as $pregunta) {
            $key = $pregunta["pregunta"] . $pregunta["categoria"];
            if (!isset($convertedArray[$key])) {
                $convertedArray[$key] = array(
                    "id" => $pregunta["id"],
                    "id_pregunta_reportada" => $pregunta["id_pregunta_reportada"],
                    "pregunta" => $pregunta["pregunta"],
                    "categoria" => $pregunta["categoria"],
                    "motivo" => $pregunta["motivo"],
                    "respuestas" => array(), // Initialize an empty array for respuestas
                    "correcta" => ""
                );
            }

            $respuesta = array(
                "id_respuesta" => $pregunta["id_respuesta"],
                "respuesta" => $pregunta["respuesta"]
            );

            $convertedArray[$key]["respuestas"][] = $respuesta; // Add respuestas to the respective pregunta

            if ($pregunta["correcta"] === "1") {
                $convertedArray[$key]["correcta"] = $pregunta["respuesta"];
            }
        }

        return array_values($convertedArray);
    }

    public function updateEstadoRevisado($id){
        $sql = "UPDATE pregunta_reportada PR
                SET PR.revisada = true
                WHERE PR.id = '$id'";
        $this->database->query($sql);
    }
    public function updatePregunta($id_pregunta_reportada,$pregunta,$categoria){
        $sql = "UPDATE pregunta P
                SET P.pregunta = '$pregunta', P.id_categoria = '$categoria'
                WHERE P.id_pregunta = '$id_pregunta_reportada'";
        $this->database->query($sql);
    }

    public function updateRespuestas($id_respuesta,$respuesta,$esCorrecta){
        $sql = "UPDATE respuesta R
                SET R.respuesta = '$respuesta', R.correcta = '$esCorrecta'
                WHERE R.id_respuesta = '$id_respuesta'";
        $this->database->query($sql);
    }

    public function deshabilitarPregunta($id_pregunta_reportada){
        $sql = "UPDATE pregunta P
                SET P.agregada = false
                WHERE P.id_pregunta = '$id_pregunta_reportada'";
        $this->database->query($sql);
    }
}