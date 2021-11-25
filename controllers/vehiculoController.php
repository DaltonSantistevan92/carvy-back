<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'models/vehiculoModel.php';
require_once 'models/clienteModel.php';
require_once 'core/conexion.php';
require_once 'core/params.php';
require_once 'models/clienteVehiculoModel.php';
require_once 'models/ordenModel.php';

class VehiculoController
{

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function eliminar($params){
        $this->cors->corsJson();
        $id = intval($params['id']);

        $dataVehiculo = Vehiculo::find($id);
        $response = [];

        if ($dataVehiculo) {
            $dataVehiculo->estado = 'I';
            $dataVehiculo->save();

            $response = [
                'status' => true,
                'mensaje' => 'El vehiculo se ha sido Eliminada',
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'El vehiculo no existe :(',
            ];
        }
        echo json_encode($response);


    }

    public function buscar($params)
    {
        $this->cors->corsJson();
        $id = intval($params['id']);
        $response = [];

        $dataVehiculo = Vehiculo::find($id);
        $dataVehiculo->marca;

        if ($dataVehiculo) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'vehiculo' => $dataVehiculo,
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existen Datos',
                'vehiculo' => null,
            ];
        }
        echo json_encode($response);
    }

    public function editar(Request $request, $params)
    {
        $this->cors->corsJson();
        $vehRequest = $request->inputPut('vehiculo');
        $idVeh = intval($params['id']);
        $response = [];

        $dataVehiculo = Vehiculo::find($idVeh);

        if ($vehRequest) {
            if ($dataVehiculo) {
                $dataVehiculo->placa = $vehRequest->placa;
                $dataVehiculo->modelo = $vehRequest->modelo;
                $dataVehiculo->color = $vehRequest->color;
                $dataVehiculo->kilometro = $vehRequest->kilometro;
                $dataVehiculo->save();

                $response = [
                    'status' => true,
                    'mensaje' => 'Vehiculo se ha actualizado correctamente !!',
                ];
            } else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo actualizar el vehiculo !!',
                ];
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos !!',
            ];
        }
        echo json_encode($response);
    }

    public function guardar(Request $request)
    {
        $this->cors->corsJson();

        $objVehiculo = $request->input('vehiculo');
        $response = [];

        if ($objVehiculo) {
            $objVehiculo->marca_id = intval($objVehiculo->marca_id);
            $objVehiculo->modelo = ucfirst($objVehiculo->modelo);
            $objVehiculo->color = ucfirst($objVehiculo->color);
            $objVehiculo->placa = strtoupper($objVehiculo->placa);
            $objVehiculo->kilometro = $objVehiculo->kilometro;

            //Empiezas
            $nuevo = new vehiculo();
            $nuevo->marca_id = $objVehiculo->marca_id;
            $nuevo->modelo = $objVehiculo->modelo;
            $nuevo->color = $objVehiculo->color;
            $nuevo->placa = $objVehiculo->placa;
            $nuevo->kilometro = $objVehiculo->kilometro;
            $nuevo->estado = 'A';

            $buscar = Vehiculo::where('placa', $objVehiculo->placa)->get()->first();

            if ($buscar) {
                $response = [
                    'status' => false,
                    'mensaje' => 'La placa ya ingresada ya exite',
                    'vehiculo' => null,
                ];

            } else {
                if ($nuevo->save()) {
                    $response = [
                        'status' => true,
                        'mensaje' => 'Guardando los datos',
                        'vehiculo' => $nuevo,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'vehiculo' => null,
                    ];
                }

            }

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No ha enviado datos',
                'vehiculo' => null,
            ];
        }

        echo json_encode($response);
    }

    public function libre($params)
    {
        $this->cors->corsJson();
        $nuevo_libre = strtoupper($params['libre']);
        $response = [];

        if ($nuevo_libre == 'S' || $nuevo_libre == 'N') {
            $vehiculos = Vehiculo::where('libre', $nuevo_libre)
                ->where('id', '<>', 999)->get();
            $aux = [];

            /*  foreach($vehiculos as $v){
            $i = [
            'vehiculo'=>$v,
            'marca_id'=> $v->marca->id
            ];
            array_push($aux,$i);
            } */
            $response = [
                'status' => true,
                'mensaje' => 'Vehiculos encontrados',
                'vehiculos' => $vehiculos,
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existe',
                'vehiculos' => null,
            ];
        }

        echo json_encode($response);
    }

    public function listar()
    {
        $this->cors->corsJson();
        $vehiculos = Vehiculo::where('estado', 'A')->get();
        $response = [];

        foreach ($vehiculos as $item) {
            $aux = [
                'vehiculo' => $item,
                'marca_id' => $item->marca->id,
            ];
            $response[] = $aux;
        }

        echo json_encode($vehiculos);
    }

    public function clienteVehiculo($params)
    {
        $this->cors->corsJson();
        $idcliente = intval($params['id']);

        $vehiculos = ClienteVehiculo::where('cliente_id', $idcliente)->get();
        $response = [];

        if (count($vehiculos) == 0) {
            $response = [
                'status' => false,
                'mensaje' => 'No tiene vehiculos asignados',
                'vehiculos' => null,
            ];

        } else {
            foreach ($vehiculos as $v) {
                $aux = [
                    'vehiculo' => $v->vehiculo,
                    'marca_id' => $v->vehiculo->marca->id,
                ];

            }
            $response = [
                'status' => true,
                'mensaje' => 'Si ahi vehiculos',
                'vehiculos' => $vehiculos,
            ];

        }
        echo (json_encode($response));

    }

    public function guardarClienteVehiculo(Request $request)
    {
        $this->cors->corsJson();
        $datos = $request->input('datos');
        $response = [];

        if ($datos) {
            $datos->cliente_id = intval($datos->cliente_id);
            $datos->vehiculo_id = intval($datos->vehiculo_id);

            $clienteVehiculo = new ClienteVehiculo;
            $clienteVehiculo->vehiculo_id = $datos->vehiculo_id;
            $clienteVehiculo->cliente_id = $datos->cliente_id;
            $clienteVehiculo->estado = 'A';

            //validar que no se repita vehiculo_id
            $existe = ClienteVehiculo::where('vehiculo_id', $datos->vehiculo_id)->get()->first();

            if ($existe) {
                $response = [
                    'status' => false,
                    'mensaje' => 'El cliente ya tiene asignado ese vehiculo',
                    'vehiculo' => null,
                ];

            } else {
                if ($clienteVehiculo->save()) {
                    //actualizar vehiculo
                    $vehiculo = Vehiculo::find($datos->vehiculo_id);
                    $vehiculo->libre = 'N';
                    $vehiculo->save();

                    $response = [
                        'status' => true,
                        'mensaje' => 'Se ha asignado el vehículo',
                        'vehiculo' => $clienteVehiculo,
                    ];

                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se ha guardado los datos ',
                        'vehiculo' => null,
                    ];
                }

            }

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No ha enviado datos',
                'vehiculo' => null,
            ];
        }

        echo json_encode($response);

    }

    public function eliminarClienteVehiculo($params)
    {

        $this->cors->corsJson();

        $vehiculo_id = intval($params['id_vehiculo']);
        $cliente_id = intval($params['id_cliente']);

        $cliente_vehiculo = ClienteVehiculo::where('vehiculo_id', $vehiculo_id)->get()->first();

        $response = [];
        //var_dump($cliente_vehiculo);    die();

        if ($cliente_vehiculo->delete()) {

            //actualizar vehiculo
            $vehiculo = Vehiculo::find($vehiculo_id);
            $vehiculo->libre = 'S';
            $vehiculo->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha borrado el vehículo',
                'vehiculo' => null,
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => ' no se pudo borrar, intente mas tarde',
                'vehiculo' => null,
            ];

        }
        echo json_encode($response);
        //var_dump($params);
    }

    public function searchVehiculo($params)
    {

        $this->cors->corsJson();
        // var_dump($params);
        $id_cliente = intval($params['id_cliente']);
        $sql = "SELECT vehiculo_id, (select vehiculos.placa from vehiculos where vehiculos.id = cv.vehiculo_id) as placa FROM clientes_vehiculos cv WHERE cv.cliente_id = $id_cliente";
        $array = $this->conexion->database::select($sql);

        //find-> busca x id
        $cliente = Cliente::find($id_cliente);
        $response = [];

        if ($cliente == null) {
            $response = [
                'status' => false,
                'mensaje' => 'El cliente no existe',
                'vehiculos' => null,
            ];

        } else {
            $response = [
                'status' => true,
                'mensaje' => 'El cliente existe',
                'vehiculos' => $array,
            ];

        }

        echo json_encode($response);
    }

    public function buscarVehiculo($params)
    {
        $this->cors->corsJson();
        //recuperar el id
        $id_vehiculo = intval($params['id']);
        //find busca x id
        $vehiculo = Vehiculo::find($id_vehiculo);
        //var_dump($vehiculo);

        $response = [];

        if ($vehiculo == null) {
            $response = [
                'status' => false,
                'mensaje' => 'El vehiculo no existe',
                'vehiculo' => null,
            ];

        } else {
            $response = [
                'status' => true,
                'mensaje' => 'El vehiculo existe',
                'vehiculo' => $vehiculo,
                'marca_id' => $vehiculo->marca->id,
            ];
        }
        echo json_encode($response);

    }

    public function contar()
    {
        $this->cors->corsJson();

        $vehiculos = Vehiculo::where('estado', 'A')->get();
        $response = [];

        if ($vehiculos) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen Vehiculos',
                'Modelo' => 'Vehiculo',
                'cantidad' => $vehiculos->count() - 1,
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Vehiculos',
                'Modelo' => 'Vehiculo',
                'cantidad' => 0,
            ];

        }

        echo json_encode($response);

    }

    public function reparados($params){
        $this->cors->corsJson();

        $inicio = $params['inicio'];
        $fin = $params['fin'];
        $marca_id = intval($params['marca_id']);

        $ordenes = Orden::where('facturado', 'S')
                ->where('fecha', '>=', $inicio)
                ->where('fecha', '<=', $fin)->orderBy('fecha')->get();

        $vehiculos = [];    $response = [];

        if(count($ordenes) > 0){
            foreach($ordenes as $item){
                if($item->vehiculo->marca_id == $marca_id){
                    $nombres = $item->mecanico->persona->nombres.' '.$item->mecanico->persona->apellidos;

                    $aux = [
                        'orden' => $item->codigo,
                        'placa' => $item->vehiculo->placa,
                        'color' => $item->vehiculo->color,
                        'km' => $item->vehiculo->kilometro,
                        'fecha' => $item->fecha,
                        'mecanico' => $nombres
                    ];

                    $vehiculos[] = (object)$aux;
                }
            }

            if(count($vehiculos) > 0){
                $response = [
                    'status' => true,
                    'mensaje' => 'Lista de vehículos',
                    'data' => $vehiculos
                ];
            }else{
                $response = [
                    'status' => false,
                    'mensaje' => 'No hay datos para la consulta',
                    'data' => []
                ];
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para la consulta',
                'data' => []
            ];
        }

        echo json_encode($response);
    }
}
