<?php

require_once 'app/cors.php';
require_once 'core/conexion.php';
require_once 'app/request.php';
require_once 'models/personaModel.php';
require_once 'models/clienteModel.php';
require_once 'models/vehiculoModel.php';
require_once 'controllers/personaController.php';

class ClienteController 
{

    private $cors;
    private $conexion;
    private $idPersController;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
        $this->idPersController = new PersonaController();
    
    }
 
    public function buscar($params){
        $this->cors->corsJson();
        $idClinte = intval($params['id']);
        $response = [];

        $dataCliente = Cliente::find($idClinte);
        $dataCliente->persona;

        if($dataCliente){
        $response = [
            'status' => true,
            'mensaje' => 'Existen Datos',
            'cliente' => $dataCliente
        ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'cliente' => null
            ];

        }
        echo json_encode($response);

    }

    public function guardar(Request $request)
    {

        $this->cors->corsJson();
        $response = [];

        $personaController = new PersonaController();
        $data = $personaController->guardarPersona($request); 
        $objet = (object) $data;

        if ($objet->status) {
            $cliente = new Cliente;

            $cliente->persona_id = $objet->persona->id;
            $cliente->fecha_ingreso = date('Y-m-d');
            $cliente->hora_ingreso = date('h:m:s');
            $cliente->estado = 'A';

            if ($cliente->save()) {
                $response = [
                    'status' => true,
                    'mensaje' => 'Se ha registrado el nuevo cliente',
                    'persona' => $cliente->persona->cedula,
                    'cliente' => $cliente,
                ];
            } else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No se ha podido guardar el cliente',
                    'cliente' => null,
                ];
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => $objet->mensaje,
                'cliente' => null,
            ];
        }

        echo json_encode($response);
    }

    public function listar()
    {
        $this->cors->corsJson();

        $clientes = Cliente::where('estado', 'A')
            ->get();

        $response = [];
        foreach ($clientes as $item) {
            $aux = [
                'cliente' => $item,
                'persona' => $item->persona->id,
            ];
            $response[] = $aux;
        }

        echo json_encode($response);
    }

    public function datatable()
    {
        //$this->cors->corsJson();
        $clientes = Cliente::where('estado', 'A')
            ->get();

        $data = [];
        $i = 1;
        foreach ($clientes as $c) {

            $botones = '<div class="btn-group">
                <button class="btn btn-sm btn-warning" onclick="editar(' . $c->id . ')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminar(' . $c->id . ')">
                <i class="fas fa-trash"></i>
                </button>
            </div>';

            $data[] = [
                0 => $i,
                1 => $c->persona->cedula,
                2 => $c->persona->nombres,
                3 => $c->persona->apellidos,
                4 => $c->persona->telefono,
                5 => $c->persona->correo,
                6 => $botones,
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
 
    public function eliminar($params)
    {
        $this->cors->corsJson();
        $id = intval($params['id']);

        $cliente = Cliente::find($id);
        $response = null;

        if ($cliente) {
            $cliente->estado = 'I';
            $cliente->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha eliminado el cliente',
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'El cliente no existe',
            ];
        }

        echo json_encode($response);
    }
    
    public function searchCliente($params)
    {
        $this->cors->corsJson();
        $texto = ucfirst($params['texto']);
        $response = [];

        $sql = "SELECT c.id, p.cedula, p.nombres, p.apellidos, p.telefono, p.correo FROM personas p
        INNER JOIN clientes c ON c.persona_id = p.id
        WHERE p.estado = 'A' and (p.cedula LIKE '$texto%' OR p.nombres LIKE '%$texto%' OR p.apellidos LIKE '%$texto%')";

        $array = $this->conexion->database::select($sql);

        if ($array) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'clientes' => $array,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existen coincidencias',
                'clientes' => null,
            ];
        }
        echo json_encode($response);
    }

    public function getVehiculo($params)
    {
        $id = intval($params['id']);

        var_dump($params);
    }

    public function buscarCliente($params)
    {
        $this->cors->corsJson();
        $id = intval($params['id']);
        $buscar = Cliente::find($id);
        $response = [];

        if ($buscar) {
            $response = [
                'status' => true,
                'mensaje' => 'Existe',
                'cliente' => $buscar,
                'persona_id' => $buscar->persona->id,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se encuentra el cliente',
                'cliente' => $buscar,
            ];
        }
        echo json_encode($response);
    }

    public function listar_vehiculo()
    {
        //$this->cors->corsJson();
        var_dump('listar-vehiculos');

    }

    public function listarvehiculodatatable()
    {
        $this->cors->corsJson();
        $vehiculos = Vehiculo::where('estado', 'A')->where('id', '<>', 999)
            ->orderBy('placa')
            ->get();

        //echo json_encode($vehiculos);
        $data = [];
        $i = 1;

        foreach ($vehiculos as $ve) {
            $botones = '<div class="btn-group">
                <button class="btn btn-sm btn-warning" onclick="editar(' . $ve->id . ')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminar(' . $ve->id . ')">
                <i class="fas fa-trash"></i>
                </button>
            </div>';

            $span = "";
            if ($ve->libre == 'S') {
                $span = '<span class="badge bg-success" style="font-size: 1.2rem;">' . 'Si' . '</span>';
            } else {
                $span = '<span class="badge bg-danger" style="font-size: 1.2rem;">' . 'No' . '</span>';
            }

            $data[] = [
                0 => $i,
                1 => $ve->placa,
                2 => $ve->marca->nombre,
                3 => $ve->modelo,
                4 => $ve->color,
                5 => $span,
                6 => $botones,
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
        //var_dump($result);

    }

    public function contar(){
        $this->cors->corsJson();
        $clientes = Cliente::where('estado','A')->get();
        $response = [];

        if($clientes){
            $response = [
                'status'  => true,
                'mensaje' => 'Existen clientes',
                'Modelo' => 'Cliente',
                'cantidad' => $clientes->count()
            ];
        }else{
            $response = [
                'status'  => false,
                'mensaje' => 'No existen datos',
                'Modelo' => 'Cliente',
                'cantidad' => 0
            ];
        }
        echo json_encode($response);
    }
     
    public function editar(Request $request, $params){ 
        $this->cors->corsJson();
        $cliRequest = $request->inputPut('cliente');
        $id = intval($params['id']);
        $response = [];

        $cli = Cliente::find($id);
        $cli->persona;
        
        if($cliRequest){
            if($cli->persona){
                $cli->persona->cedula = $cliRequest->cedula;
                $cli->persona->nombres = $cliRequest->nombres;
                $cli->persona->apellidos = $cliRequest->apellidos;
                $cli->persona->telefono = $cliRequest->telefono;
                $cli->persona->correo = $cliRequest->correo;
                $cli->persona->direccion = $cliRequest->direccion;
                $cli->persona->estado = 'A';
                $cli->persona->save();

                $response = [
                    'status' => true,
                    'mensaje' => 'El cliente se ha actualizado',
                    'cliente' => $cli->persona
                ];
            }else{
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo actualizar el cliente !!'
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

   
}
