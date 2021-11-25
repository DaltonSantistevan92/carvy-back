<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/marcaModel.php';

class MarcaController
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
        $response = [];

        $datamarcas = Marca::where('estado', 'A')->orderBy('nombre')->get();

        if($datamarcas){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'marca' => $datamarcas
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'marca' => $datamarcas
            ];

        }
        echo json_encode($response);
    }

    public function datatable()
    {
        $this->cors->corsJson();

        $marcas = Marca::where('estado', 'A')->orderBy('nombre')->get();
        $data = [];
        $i = 1;
        foreach ($marcas  as $m) {

            $botones = '<div class="text-center">
                <button class="btn btn-sm btn-warning" onclick="editar(' . $m->id . ')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminar(' . $m->id . ')">
                <i class="fas fa-trash"></i>
                </button>
            </div>';

            $data[] = [
                0 => $i,
                1 => $m->nombre,
                2 => $m->fecha,
                3 => $botones,
            ];
            $i++;
        }
        $result = [
            'sEcho' => 1,
            'iTotalRecords' => count($data),
            'iTotalDisplayRecords' => count($data),
            'aaData' => $data,
        ];
        echo json_encode($result);

    }

    public function save(Request $request){
        $this->cors->corsJson();
        $marcaRequest = $request->input('marca');

        $nombre = ucfirst($marcaRequest->nombre);
        $response = [];
        
        if($marcaRequest){
            $nuevo = new Marca();
            $existe = Marca::where('nombre',$nombre)->get()->first();

            if($existe){
                $response = [
                    'status' => false,
                    'mensaje' => 'La marca ya existe',
                    'marca' => null,
                ];
            }else{
                $nuevo->nombre = $nombre;
                $nuevo->descripcion = $nuevo->descripcion;
                $nuevo->fecha = date('Y-m-d');
                $nuevo->estado = 'A';

                if($nuevo->save()){
                    $response = [
                        'status' => true,
                        'mensaje' => 'Guardando los datos',
                        'marca' => $nuevo,
                    ];

                }else{
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'marca' => null,
                    ];
                }
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'marca' => null,
            ]; 
        }
        echo json_encode($response);

    }

    public function searchMarca($params){
        $this->cors->corsJson();
        $texto = ucfirst($params['texto']);
        $response = [];

        $sql = "SELECT m.id,m.nombre FROM marcas m WHERE m.estado = 'A' and (m.nombre LIKE '%$texto%')";
        $array = $this->conexion->database::select($sql);

        if ($array) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'marca' => $array,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existen coincidencias',
                'marca' => null,
            ];
        }
        echo json_encode($response);
    }

    public function editar(Request $request, $params){
        $this->cors->corsJson();

        $marRequest = $request->inputPut('marca');
        $idMarca = intval($params['id']);

        $dataMarca = Marca::find($idMarca);

        if($marRequest){
            if($dataMarca){
                $dataMarca->nombre = $marRequest->nombre;
                $dataMarca->descripcion =  $dataMarca->descripcion;
                $dataMarca->save();
                $response = [
                    'status' => true,
                    'mensaje' => 'Marca se ha actualizado correctamente !!'
                ];
            }else{
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo actualizar la marca !!'
                ];
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos !!'
            ];
        }
        echo json_encode($response);
    }

    public function buscar($params){
        $this->cors->corsJson();
        $response = [];
        $idMar = intval($params['id']);
        $dataMar = Marca::find($idMar);

        if($dataMar){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'marca' => $dataMar
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'marca' => null
            ];
        }
        echo json_encode($response);
    }

    public function eliminar($params){
        $this->cors->corsJson();
        $id = intval($params['id']);

        $marca = Marca::find($id);
        $response = [];

        if($marca){
            $marca->estado = 'I';
            $marca->save();
            $response = [
                'status' => true,
                'mensaje' => 'La marca ha sido Eliminada',
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'La marca no existe :(',
            ]; 
        }
        echo json_encode($response);
    }

    public function todos(){
        $response = [];

        $datamarcas = Marca::where('estado', 'A')
                ->where('id', '<>', 999)->orderBy('nombre')->get();

        if($datamarcas){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'marca' => $datamarcas
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'marca' => $datamarcas
            ];

        }
        echo json_encode($response);
    }
}
