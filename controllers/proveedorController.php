<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'models/proveedorModel.php';
require_once 'core/conexion.php';
require_once 'core/params.php';



class ProveedorController
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
        $proveedores = Proveedor::where('estado', 'A')
            ->orderBy('razon_social')->get();

        echo json_encode($proveedores);
    }

    public function guardar(Request $request)
    {
        $this->cors->corsJson();
        $prov = $request->input('proveedor');
        $response = [];

        //var_dump($prov); die();
        if ($prov) {
            $existe = Proveedor::where('ruc', $prov->ruc)->get()->first();

            if ($existe) {
                $response = [
                    'status' => false,
                    'mensaje' => 'El proveedor ya se encuetra registrado',
                    'proveedor' => $existe,
                ];
            } else {
                $proveedor = new Proveedor;
                $proveedor->ruc = $prov->ruc;
                $proveedor->razon_social = $prov->razon_social;
                $proveedor->direccion = $prov->direccion;
                $proveedor->correo = $prov->correo;
                $proveedor->fecha = date('Y-m-d');
                $proveedor->telefono = $prov->telefono;
                $proveedor->estado = 'A';

                if ($proveedor->save()) {
                    $response = [
                        'status' => true,
                        'mensaje' => 'Se ha registrado un nuevo proveedor',
                        'proveedor' => $proveedor,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'proveedor' => null,
                    ];
                }
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'proveedor' => null,
            ];
        }

        echo json_encode($response);
    }

    public function datatable()
    {
        $this->cors->corsJson();

        $proveedores = Proveedor::where('estado', 'A')
            ->orderBy('razon_social')
            ->get();

        $data = [];
        $i = 1;
        foreach ($proveedores as $pr) {

            $botones = '<div class="btn-group">
                <button class="btn btn-sm btn-warning" onclick="editar(' . $pr->id . ')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminar(' . $pr->id . ')">
                <i class="fas fa-trash"></i>
                </button>
            </div>';

            $data[] = [
                0 => $i,
                1 => $pr->ruc,
                2 => $pr->razon_social,
                3 => $pr->direccion,
                4 => $pr->correo,
                5 => $pr->telefono,
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

    public function search($params)  
    {
        $this->cors->corsJson();

        $texto = strtolower($params['texto']);
        $proveedores = Proveedor::where('razon_social', 'like', $texto . '%')
            ->where('estado', 'A')->get();
        $response = [];

        if ($texto == "") {
            $response = [
                'status' => true,
                'mensaje' => 'Todos los registros',
                'proveedores' => $proveedores,
            ];
        } else {
            if (count($proveedores) > 0) {
                $response = [
                    'status' => true,
                    'mensaje' => 'Coincidencias encontradas',
                    'proveedores' => $proveedores,
                ];
            } else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No hay registros',
                    'proveedores' => null,
                ];
            }
        }
        echo json_encode($response);
    }

    public function contar(){
        $this->cors->corsJson();
        $proveedores = Proveedor::where('estado','A')->get();
        $response = [];

        if($proveedores){
            $response = [
                'status'  => true,
                'mensaje' => 'Existen datos',
                'Modelo' => 'proveedores',
                'cantidad' => $proveedores->count()
            ];
        }else{
            $response = [
                'status'  => false,
                'mensaje' => 'No existen datos',
                'Modelo' => 'proveedores',
                'cantidad' => 0
            ];
        }
        echo json_encode($response);
    }

    public function buscar($params){
        $this->cors->corsJson();
        $idPro = intval($params['id']);
        $dataProveedor = Proveedor::find($idPro);

        if($dataProveedor){
        $response = [
            'status' => true,
            'mensaje' => 'Existen Datos',
            'proveedor' => $dataProveedor
        ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'proveedor' => null
            ];

        }
        echo json_encode($response);

    }

    public function editar(Request $request, $params){
        $this->cors->corsJson();
        $provRequest = $request->inputPut('proveedor');
        $idProv = intval($params['id']);
        $response = [];

        $dataProveedor = Proveedor::find($idProv);

        if($provRequest){
            if($dataProveedor){
                $dataProveedor->ruc = $provRequest->ruc;
                $dataProveedor->razon_social = $provRequest->razon_social;
                $dataProveedor->direccion = $provRequest->direccion;
                $dataProveedor->correo = $provRequest->correo;
                $dataProveedor->fecha = date('Y-m-d');
                $dataProveedor->telefono = $provRequest->telefono;
                $dataProveedor->estado = 'A';
                $dataProveedor->save();

                $response = [
                    'status' => true,
                    'mensaje' => 'El proveedor se ha actualizado',
                    'cliente' => $dataProveedor
                ];
            }else{
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo actualizar el proveedor !!'
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

    public function eliminar($params)
    {
        $this->cors->corsJson();
        $id = intval($params['id']);

        $dataProveedor = Proveedor::find($id);
        $response = null;

        if ($dataProveedor) {
            $dataProveedor->estado = 'I';
            $dataProveedor->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha eliminado el Proveedor',
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'El proveedor no existe',
            ];
        }

        echo json_encode($response);
    }


}
