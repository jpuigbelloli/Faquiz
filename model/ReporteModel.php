<?php

class ReporteModel{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function cantidadDeUsuarios($filtro){
        $sql = "SELECT COUNT(id_usuario) cantidad,
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
        $sql = "SELECT COUNT(id_usuario) cantidad,
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

    public function cantidadJugadoresPorGenero($filtro){
        $sql = "SELECT COUNT(id_usuario) cantidad, 
                CASE 
                                            WHEN genero = 1 THEN 'Femenino'
                                            WHEN genero = 2 THEN 'Masculino'
                                            ELSE 'Sin Especificar' 
                END filtro1,
                CASE 
                    WHEN '$filtro' ='Month' THEN MONTH(fecha_ingreso) 
                    WHEN '$filtro' ='Year' THEN YEAR(fecha_ingreso) 
                    WHEN '$filtro' ='Day'  THEN DAY(fecha_ingreso) 
                    WHEN '$filtro' ='Week' THEN WEEK(fecha_ingreso) 
                END filtro
            
                FROM usuario
                GROUP BY filtro1, filtro;
                ";

       return $this->database->query_assoc($sql);
    }

    public function cantidadJugadoresPorEdad($filtro){
        $sql = "SELECT   COUNT(id_usuario) cantidad, 
                CASE 
                    WHEN YEAR(current_date()) - YEAR(fecha_nac) <18 THEN 'Menor'
                    WHEN YEAR(current_date()) - YEAR(fecha_nac)>18 && YEAR(current_date()) - YEAR(fecha_nac) <60 THEN 'Medio'
                    WHEN YEAR(current_date()) - YEAR(fecha_nac) > 60 THEN 'Jubilado' 
                END filtro1,
                CASE 
                    WHEN '$filtro' ='Month' THEN MONTH(fecha_ingreso) 
                    WHEN '$filtro' ='Year' THEN YEAR(fecha_ingreso) 
                    WHEN '$filtro' ='Day'  THEN DAY(fecha_ingreso) 
                    WHEN '$filtro' ='Week' THEN WEEK(fecha_ingreso) 
                END filtro
                FROM usuario
                GROUP BY filtro1,filtro;";

        return $this->database->query_assoc($sql);
    }

    public function jugadoresPorPais($filtro){
        $sql = "SELECT COUNT(id_usuario) cantidad , pais filtro1,
                CASE 
                    WHEN '$filtro' ='Month' THEN MONTH(fecha_ingreso) 
                    WHEN '$filtro' ='Year' THEN YEAR(fecha_ingreso) 
                    WHEN '$filtro' ='Day'  THEN DAY(fecha_ingreso) 
                    WHEN '$filtro' ='Week' THEN WEEK(fecha_ingreso) 
                END filtro
                FROM usuario
                GROUP by filtro1,filtro;";
        return $this->database->query_assoc($sql);
    }
    public function getCantPreguntasAdmin($filtro){
        $sql = "SELECT COUNT(id_pregunta) cantidad,
                CASE 
                    WHEN $filtro ='Month'THEN MONTH(fecha_pregunta) 
                    WHEN $filtro ='Year' THEN YEAR(fecha_pregunta) 
                    WHEN $filtro ='Day'  THEN DAY(fecha_pregunta) 
                    WHEN $filtro ='Week' THEN WEEK(fecha_pregunta) 
                END filtro
                FROM pregunta 
                GROUP BY filtro";
        return $this->database->query_fetch_assoc($sql);
    }

    public function getCantNuevas($filtro){
        $sql = "SELECT COUNT(id_pregunta_sugerida) cantidad,
                CASE 
                    WHEN $filtro ='Month' THEN MONTH(fecha_pregunta) 
                    WHEN $filtro ='Year' THEN YEAR(fecha_pregunta) 
                    WHEN $filtro ='Day'  THEN DAY(fecha_pregunta) 
                    WHEN $filtro ='Week' THEN WEEK(fecha_pregunta) 
                END filtro
                FROM pregunta_sugerida 
                WHERE id_estado = 1
                GROUP BY filtro;
                ";
        return $this->database->query_assoc($sql);
    }

    public function getCantidadDePartidas($filtro){
        $sql = "SELECT COUNT(id_partida) cantidad, 
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
    public function getRespuestaPorUsuario(){
        $sql = "SELECT U.user_name filtro, ROUND((SUM(CASE WHEN respuesta = '1' THEN 1 ELSE 0 END) / COUNT(*) * 100)) cantidad 
                FROM estadistica E INNER JOIN 
                    usuario U 
                        ON E.id_usuario = U.id_usuario 
                GROUP BY filtro
                ORDER BY cantidad DESC;";
        return $this->database->query_assoc($sql);
    }
}
