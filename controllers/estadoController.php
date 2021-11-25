<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'models/ordenModel.php';
require_once 'models/clienteModel.php';
require_once 'models/estadoModel.php';
require_once 'core/conexion.php';
require_once 'core/params.php';

class EstadoController
{

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function listar()
    {
        $this->cors->corsJson();
        $estado = Estado::where('estado', 'A')
            ->orderBy('detalle')->get();
        $response = [];

        /* if($estado){
        $response = [
        'status' => true,
        'mensaje' => 'Existen datos',
        'estado' => $estado
        ];
        }else{
        $response = [
        'status' => false,
        'mensaje' => 'No existen datos',
        'estado' => null
        ];
        } */

        echo json_encode($response);
    }

    public function buscar($params)
    {
        $this->cors->corsJson();
        $idestado = intval($params['id']);

        $estado = Estado::find($idestado);

        $response = [];

        if ($estado == null) {
            $response = [
                'status' => false,
                'mensaje' => 'No Existen datos',
                'estado' => null,
            ];
        } else {
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'estado' => $estado,
            ];
        }

        echo json_encode($response);
    }

}
