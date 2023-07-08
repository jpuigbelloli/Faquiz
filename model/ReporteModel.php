<?php

class ReporteModel{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function cantidadDeUsuarios($filtro){
        $sql = "SELECT DATE_FORMAT(DATE(fecha_ingreso),'%d-%m-%y') fecha,COUNT(id_usuario) cantidad,
                CASE 
                    WHEN '$filtro' ='Month' THEN MONTH(fecha_ingreso) 
                    WHEN '$filtro' ='Year' THEN YEAR(fecha_ingreso) 
                    WHEN '$filtro' ='Day'  THEN DAY(fecha_ingreso) 
                    WHEN '$filtro' ='Week' THEN WEEK(fecha_ingreso) 
                END filtro
                 FROM usuario
                WHERE id_rol = 1
                GROUP BY filtro;
                 ";
        return $this->database->query_assoc($sql);
    }

    public function cantidadDeUsuariosNuevos($filtro){
        $sql = "SELECT DATE_FORMAT(DATE(fecha_ingreso),'%d-%m-%y') fecha,COUNT(id_usuario) cantidad,
                CASE 
                    WHEN '$filtro' ='Month' THEN MONTH(fecha_ingreso) 
                    WHEN '$filtro' ='Year' THEN YEAR(fecha_ingreso) 
                    WHEN '$filtro' ='Day'  THEN DAY(fecha_ingreso) 
                    WHEN '$filtro' ='Week' THEN WEEK(fecha_ingreso) 
                END filtro
                 FROM usuario
                WHERE id_rol = 1
                AND fecha_ingreso >= DATE_SUB(CURDATE(), INTERVAL 3 DAY) 
                GROUP BY filtro;
                 ";
        return $this->database->query_assoc($sql);
    }

    public function cantidadJugadoresPorGenero(){
        $sql = "SELECT DATE_FORMAT(DATE (fecha_ingreso),'%d-%m-%y') fecha ,COUNT(*) cantidad, CASE 
                                            WHEN genero = 1 THEN 'Femenino'
                                            WHEN genero = 2 THEN 'Masculino'
                                            ELSE 'Sin Especificar' 
                                          END filtro
                FROM usuario
                GROUP BY filtro;";

       return $this->database->query_assoc($sql);
    }

    public function cantidadJugadoresPorEdad(){
        $sql = "SELECT   COUNT(*) cantidad, CASE 
                                            WHEN YEAR(current_date()) - YEAR(fecha_nac) <18 THEN 'Menor'
                                            WHEN YEAR(current_date()) - YEAR(fecha_nac)>18 && YEAR(current_date()) - YEAR(fecha_nac) <60 THEN 'Medio'
                                            WHEN YEAR(current_date()) - YEAR(fecha_nac) > 60 THEN 'Jubilado' 
                                          END filtro
                FROM usuario
                GROUP BY filtro;";

        return $this->database->query_assoc($sql);
    }

    public function jugadoresPorPais(){
        $sql = "SELECT COUNT(*) cantidad , pais filtro
                FROM usuario
                GROUP by pais;";
        return $this->database->query_assoc($sql);
    }

    public function getCantPreguntasAdmin(){
        $sql = "SELECT COUNT(*) cantidad, C.descripcion filtro 
                FROM pregunta P INNER JOIN
                    categoria C 
                        ON P.id_categoria = C.id_categoria
                GROUP BY filtro
                ";
        return $this->database->query_assoc($sql);
    }

    public function getCantNuevas(){
        $sql = "SELECT COUNT(*) cantidad, C.descripcion filtro
                FROM pregunta_sugerida P  INNER JOIN
                    categoria C
                        ON P.id_categoria = C.id_categoria
                WHERE P.id_estado = 2
                GROUP BY filtro;
                ";
        return $this->database->query_assoc($sql);
    }

    public function getCantidadDePartidas($filtro){
        $sql = "SELECT  DATE_FORMAT(DATE (fecha),'%d-%m-%y') fecha, COUNT(*) cantidad, 
                CASE 
                    WHEN '$filtro' ='Month' THEN MONTH(fecha) 
                    WHEN '$filtro' ='Year' THEN YEAR(fecha) 
                    WHEN '$filtro' ='Day'  THEN DAY(fecha) 
                    WHEN '$filtro' ='Week' THEN WEEK(fecha) 
                END filtro
                FROM partida
                GROUP BY filtro";
        return $this->database->query_assoc($sql);

    }

    public function getJugadorPorPais(){
        $sql = "SELECT COUNT(*) cantidad, pais filtro
                 FROM usuario
                ";
        return $this->database->query_assoc($sql);
    }

    public function getRespuestaPorUsuario(){
        $sql = "SELECT U.user_name cantidad, (SUM(CASE WHEN respuesta = '1' THEN 1 ELSE 0 END) / COUNT(*) * 100) AS filtro 
                FROM estadistica E INNER JOIN 
                    usuario U 
                        ON E.id_usuario = U.id_usuario 
                GROUP BY id_pregunta 
                ORDER BY filtro DESC;";

        return $this->database->query_assoc($sql);
    }
}
