<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/averiasModel.php';

class AveriasController
{
    private $cors;
    private $db;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->db = new Conexion();
    }

    public function listar()
    {
        $this->cors->corsJson();
        $response = [];
        $averiasfallas = Averias::where('estado', 'A')->orderBy('descripcion')->get();

        if($averiasfallas){
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'averias' => $averiasfallas
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No existen datos',
                'averias' => null
            ];
        }
        echo json_encode($response);
        
    }

    public function buscar($params)
    {
        $this->cors->corsJson();
        $idaveriasfallas = intval($params['id']);

        $averia = Averias::find($idaveriasfallas);
        
        $response = [];

        if($averia == null){
            $response = [
                'status' => false,
                'mensaje' => 'La averia no existe',
                'averia' => null,
            ];

        }else{
            $response = [
                'status' => true,
                'mensaje' => 'La averia existe',
                'averia' => $averia,
            ];

        } 

        echo json_encode($response);
    }

    public function getAveriasByOrden($id){

        $sql = "SELECT averias_fallas_id, (SELECT averias_fallas.descripcion FROM averias_fallas WHERE averias_fallas.id = ordentrabajo_averiasfallas.averias_fallas_id) as averia, 
        (SELECT averias_fallas.precio FROM averias_fallas WHERE averias_fallas.id = ordentrabajo_averiasfallas.averias_fallas_id) as precio 
                FROM `ordentrabajo_averiasfallas` WHERE estado = 'A' AND orden_de_trabajo_id = $id";
        $array = $this->db->database::select($sql);
        $response = [];

        if(count($array) > 0){
            $response = $array;
        }

        return $response;
    }
}