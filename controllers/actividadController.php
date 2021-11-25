<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/actividadModel.php';
require_once 'models/ordenModel.php';

class ActividadController
{
    private $cors;
    private $db; 

    public function __construct()
    {
        $this->cors = new Cors();
        $this->db = new Conexion();
    }

    public function guardar(Request $request){
        
        $this->cors->corsJson();
        $response = [];
        $dataActividad = $request->input('actividad');
        

        if($dataActividad){
            $dataActividad->orden_id = intval($dataActividad->orden_id);
            $dataActividad->detalle = $dataActividad->detalle;
            $dataActividad->progreso = intval($dataActividad->progreso);
            $dataActividad->total = intval($dataActividad->total);
            $dataActividad->faltante = ucfirst($dataActividad->faltante);

            $nuevo = new Actividad();
            $nuevo->orden_id = $dataActividad->orden_id;
            $nuevo->detalle = ucfirst($dataActividad->detalle);
            $nuevo->progreso = $dataActividad->progreso;
            $nuevo->total = $dataActividad->total;
            $nuevo->faltante = $dataActividad->faltante;
            $nuevo->fecha = date('Y-m-d');

            $nuevo->save();

            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
            ];
        }
        echo json_encode($response);
    }

    public function guardar2(Request $request){
        $this->cors->corsJson();
        $response = [];
        $dataActividad = $request->input('actividad');

        if($dataActividad){
            $dataActividad->orden_id = intval($dataActividad->orden_id);
            $dataActividad->detalle = ucfirst($dataActividad->detalle);
            $dataActividad->progreso = intval($dataActividad->progreso);

            $last = Actividad::where('orden_id', $dataActividad->orden_id)->orderBy('id', 'DESC')->get()->first();
            $progressTemp = $last->total + $dataActividad->progreso;

            if($last){
                if($progressTemp <= 100){
                    if($last->total > 100){
                        $response = [
                            'status' => false,
                            'mensaje' => 'EL progreso excede el 100%, verifique en la tabla'
                        ];
                    }else
                    if($last->total < 100){    //menor a 100%
                        $nuevo = new Actividad();
        
                        $nuevo->orden_id = $dataActividad->orden_id;
                        $nuevo->detalle = $dataActividad->detalle;
        
                        $aux_progreso = $last->total + $dataActividad->progreso;
                        $nuevo->fecha = date('Y-m-d');
    
                        $nuevo->progreso = $dataActividad->progreso;
                        $nuevo->total  = $aux_progreso;
                        $nuevo->faltante = (100 - $aux_progreso);
    
                        $nuevo->save();
    
                        $response = [
                            'status' => true,
                            'mensaje' => 'Actividad guardada correctamente'
                        ];
                    }if($last->total == 100){
                        $response = [
                            'status' => false,
                            'mensaje' => 'Las actividades de la orden están al 100%'
                        ]; 
                    }
                }else{
                    $response = [
                        'status' => false,
                        'mensaje' => 'Las actividad no puede exceder el 100%'
                    ]; 
                }
            }
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No ha enviado datos'
            ];
        }
        echo json_encode($response);
    }

    public function ultimo_porcentaje($params){
        
        $this->cors->corsJson();
        $id_orden = intval($params['id_orden']);

        $last = Actividad::where('orden_id', $id_orden)->orderBy('id', 'DESC')->get()->first();
        $last->orden;
        echo json_encode($last);
    }

    public function listar($params)
    {
        $this->cors->corsJson();
        $orden_id = intval($params['id']);

        $actividades = Actividad::where('orden_id', $orden_id)->get();
        $response = [];

        if($actividades){
            $response  = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'actividades' => $actividades
            ];
        }else{
            $response  = [
                'status' => false,
                'mensaje' => 'No existen datos',
                'actividades' => []
            ];
        }
        echo json_encode($response);
    }

    public function reciente($params){
        
        $this->cors->corsJson();

        $cant = intval($params['cantidad']);
        $ordenes = Orden::where('estado', 'A')
            ->where('facturado', 'N')->orderBy('id', 'DESC')->take($cant)->get();

        $ordenArray = [];

        foreach($ordenes as $o){
            $o->estado_orden;

            $actividades = Actividad::where('orden_id', $o->id)->orderBy('id', 'DESC')->get()->first();
            
            if($actividades != null){
                $ordenArray[] = $actividades;

            }
        }

        foreach($ordenArray as $item){
            $item->orden;
            $item->orden->estado_orden;
        }

        echo json_encode($ordenArray);
    }
}