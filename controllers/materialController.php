<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'models/materialModel.php';
require_once 'core/conexion.php';

class MaterialController
{

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    /* public function listar()
    {
        $this->cors->corsJson();
        $categorias = Categoria::where('estado', 'A')
            ->orderBy('categoria')
            ->get();
        $response = [];

        if (count($categorias) > 0) {
            $response = $categorias;
        }

        echo json_encode($response);
    } */

    public function guardar(Request $request)
    {
        $this->cors->corsJson();
        $objmaterial = $request->input("material");
        $objorden = $request->input("orden");

        $response = [];

        //var_dump($objmaterial); die();
        //data={"material":[{"orden_id":"40","producto_id":{},"comprado":"N","cantidad":{}}]}

        if(count($objmaterial) > 0){
            foreach($objmaterial as $item){
                $item->orden_id = intval($item->orden_id);
                $item->cantidad = intval($item->cantidad);
                $item->producto_id = intval($item->producto_id);

                //empieza
                $nuevoMaterial = new Material;
                $nuevoMaterial->orden_id = $item->orden_id;
                $nuevoMaterial->producto_id = $item->producto_id;
                $nuevoMaterial->comprado = 'N';
                $nuevoMaterial->cantidad = $item->cantidad; 
                $nuevoMaterial->fecha_registro = date('Y-m-d');
                
                $nuevoMaterial->save();   
                
                $response = [
                    'status' => true,
                    'mensaje' => 'se guardo correctamente',
                ];
                
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No se guardo'
            ];
        }

        echo json_encode($response);
    }

    public function getMateriales($id_orden){
        $materiales = Material::where('orden_id', $id_orden)->get();

        return $materiales;
    }

}
