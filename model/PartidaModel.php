<?php

class PartidaModel{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getNivelJugador($id){
        $sql = "SELECT id_usuario,respuesta
                FROM estadistica
                WHERE id_usuario = '$id'";
        $resultado = $this->database->query_assoc($sql);

        $correctas = 0;
        $total = 0;

        foreach ($resultado as $result){
            if ($result['respuesta'] === '1'){
                $correctas++;
            }
            $total++;
        }
        //esto quiere decir que el jugador no tiene nivel porque nunca jugó
        if($total === 0){
            return null;
        }
        return $correctas / $total * 100;
    }

    public function getIDPreguntaSegunNivelJugador($id){
        $nivelJugador = $this->getNivelJugador($id);

        Logger::info('Mi nivel es '.$nivelJugador);
        if($nivelJugador >=0 && $nivelJugador<=33 || $nivelJugador === null){
            $sql = "SELECT P.id_pregunta, e.porcentaje
                    FROM pregunta P
                    LEFT JOIN (
                        SELECT id_pregunta,
                               (SUM(CASE WHEN respuesta = '1' THEN 1 ELSE 0 END) / COUNT(*) * 100) AS porcentaje
                        FROM estadistica
                        GROUP BY id_pregunta) e 
                        ON P.id_pregunta = e.id_pregunta
                        WHERE P.id_pregunta NOT IN( SELECT id_pregunta FROM estadistica E
                                            WHERE E.respuesta = '1' AND E.id_usuario = '$id')
                        AND P.agregada = 1
                    HAVING porcentaje >=66 AND porcentaje <=100 OR porcentaje IS NULL";
            Logger::info('ME DIERON PREGUNTA FACIL');
        } else if($nivelJugador >33 && $nivelJugador <=66){
            $sql = "SELECT P.id_pregunta, e.porcentaje
                    FROM pregunta P
                    LEFT JOIN (
                        SELECT id_pregunta,
                               (SUM(CASE WHEN respuesta = '1' THEN 1 ELSE 0 END) / COUNT(*) * 100) AS porcentaje
                        FROM estadistica
                        GROUP BY id_pregunta) e 
                        ON P.id_pregunta = e.id_pregunta
                        WHERE P.id_pregunta NOT IN( SELECT id_pregunta FROM estadistica E
                                            WHERE E.respuesta = '1' AND E.id_usuario = '$id')
                        AND P.agregada = 1
                    HAVING porcentaje >=33 AND porcentaje <66 OR porcentaje IS NULL";
            Logger::info('ME DIERON PREGUNTA MEDIA');
        } else {
            $sql = "SELECT P.id_pregunta, e.porcentaje
                    FROM pregunta P
                    LEFT JOIN (
                        SELECT id_pregunta,
                               (SUM(CASE WHEN respuesta = '1' THEN 1 ELSE 0 END) / COUNT(*) * 100) AS porcentaje
                        FROM estadistica
                        GROUP BY id_pregunta) e 
                        ON P.id_pregunta = e.id_pregunta
                        WHERE P.id_pregunta NOT IN( SELECT id_pregunta FROM estadistica E
                                            WHERE E.respuesta = '1' AND E.id_usuario = '$id')
                    HAVING porcentaje >=0 AND porcentaje <33 OR porcentaje IS NULL";
            Logger::info('ME DIERON PREGUNTA DIFICIL');
        }
        $resultado = $this->database->query_assoc($sql);
        $randomKey= array_rand($resultado);
        $idPreguntaRandom = $resultado[$randomKey];

        return $idPreguntaRandom['id_pregunta'];
    }

    //obtiene las preguntas que el usuario no respondio y las que fueron agregadas
    public function obtenerPregunta($id){

        $id_pregunta = $this->getIDPreguntaSegunNivelJugador($id);
        $sql = "SELECT P.id_pregunta AS id_pregunta, P.pregunta AS pregunta, C.descripcion AS categoria FROM pregunta P
                JOIN categoria C
                ON P.id_categoria = C.id_categoria
                WHERE P.id_pregunta = '$id_pregunta'";

        return $this->database->query_assoc($sql);
    }

    public function obtenerRespuestas($id){
        $sql = "SELECT ROW_NUMBER() OVER (ORDER BY respuesta) AS numero ,R.respuesta AS respuesta
                FROM respuesta R
                WHERE R.id_pregunta = '$id'";

        $data = $this->database->query_assoc($sql);
        //Randomizado del orden de las respuestas
        shuffle($data);
        return $data;
    }

    public function esCorrecta($respuesta,$id_pregunta){
        $sql = "SELECT R.correcta FROM respuesta R
                WHERE R.respuesta = '$respuesta'
                AND R.id_pregunta = '$id_pregunta'";
        //se podria sacar el AND
        return $this->database->query_assoc($sql);
    }

    public function agregarPartida($userId,$puntaje){
         $sql = "INSERT INTO partida (id_usuario, puntaje) VALUES 
                                       ('$userId','$puntaje');";
         return$this->database->query($sql);
    }
    public function guardarPreguntaCorrectaOIncorrecta($id_pregunta,$respuesta,$userId){
        $sql = "INSERT INTO `estadistica` (`id_pregunta`, `respuesta`,`id_usuario`) 
                VALUES ('$id_pregunta', '$respuesta','$userId')";

        $this->database->query($sql);
    }

    public function getUserId($username){
        $sql = "SELECT U.id_usuario AS id
                FROM usuario U
                WHERE U.user_name = '$username'";
        return $this->database->query_assoc($sql);
    }

    public function guardarPartida($id,$puntaje){
        $sql = "INSERT INTO partida (id_usuario,puntaje)
                VALUES('$id','$puntaje')";

        $this->database->query($sql);
    }

    public function reportarPregunta($id_pregunta,$motivo,$id_usuario){
        $sql = "INSERT INTO pregunta_reportada(id_pregunta_reportada,id_motivo,id_usuario)
                VALUES ('$id_pregunta','$motivo','$id_usuario')";
        $this->database->query($sql);
    }


    /*
     * SELECT p.id_pregunta, e.porcentaje
                    FROM pregunta p
                    LEFT JOIN (
                        SELECT id_pregunta,
                               (SUM(CASE WHEN respuesta = '1' THEN 1 ELSE 0 END) / COUNT(*) * 100) AS porcentaje
                        FROM estadistica
                        GROUP BY id_pregunta) e
                        ON p.id_pregunta = e.id_pregunta
                        WHERE P.id_pregunta NOT IN( SELECT id_pregunta FROM estadistica E
                                            WHERE E.respuesta = '1' AND E.id_usuario = 5)
                    HAVING porcentaje >=66 AND porcentaje <=100 OR porcentaje IS NULL
ORDER BY `p`.`id_pregunta` ASC*/

}