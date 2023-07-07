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
                GROUP BY DATE(fecha_ingreso);
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
                GROUP BY DATE(fecha_ingreso);
                 ";
        return $this->database->query_assoc($sql);
    }

    public function cantidadJugadoresPorGenero(){
        $sql = "SELECT DATE_FORMAT(DATE (fecha_ingreso),'%d-%m-%y') fecha ,COUNT(*) cantidad, CASE 
                                            WHEN genero = 1 THEN 'Femenino'
                                            WHEN genero = 2 THEN 'Masculino'
                                            ELSE 'Sin Especificar' 
                                          END genero
                FROM usuario
                GROUP BY genero;";

       return $this->database->query_assoc($sql);
    }

    public function cantidadJugadoresPorEdad(){
        $sql = "SELECT   COUNT(*) cantidad, CASE 
                                            WHEN YEAR(current_date()) - YEAR(fecha_nac) <18 THEN 'Menor'
                                            WHEN YEAR(current_date()) - YEAR(fecha_nac)>18 && YEAR(current_date()) - YEAR(fecha_nac) <60 THEN 'Medio'
                                            WHEN YEAR(current_date()) - YEAR(fecha_nac) > 60 THEN 'Jubilado' 
                                          END grupoEtario
                FROM usuario
                GROUP BY grupoEtario;";

        return $this->database->query_assoc($sql);
    }

    public function getCantPreguntasAdmin(){
        $sql = "SELECT COUNT(id_pregunta) cant_preguntas  
                FROM pregunta;
                ";
        return $this->database->query($sql);
    }

    public function getCantNuevas(){
        $sql = "SELECT COUNT(id_pregunta) cant_preguntas
                FROM pregunta_sugerida
                WHERE id_estado = 1;
                ";
    }

    public function getCantidadDePartidas($filtro){
        $sql = "SELECT  DATE_FORMAT(DATE (fecha),'%d-%m-%y') fecha, COUNT(*) cant_partidas, 
                CASE 
                    WHEN '$filtro' ='Month' THEN MONTH(fecha) 
                    WHEN '$filtro' ='Year' THEN YEAR(fecha) 
                    WHEN '$filtro' ='Day'  THEN DAY(fecha) 
                    WHEN '$filtro' ='Week' THEN WEEK(fecha) 
                END filtro
                FROM partida
                GROUP BY fecha";
        return $this->database->query($sql);

    }

}