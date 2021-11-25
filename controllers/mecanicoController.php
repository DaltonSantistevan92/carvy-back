<?php
require_once 'app/app.php';
require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/mecanicoModel.php';
require_once 'models/actividadModel.php';
require_once 'models/estadoModel.php';
require_once 'models/mecanicoModel.php';

class MecanicoController
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
        $mecanicos = Mecanico::where('estado', 'A')->get();

        $response = [];

        foreach($mecanicos as $item)
        {
            $aux = [
                'mecanico' => $item,
                'persona_id' => $item->persona->id,
                'foto' => $item->persona->usuario
            ];
            $response[] = $aux;

        }

        if(count($mecanicos)>0){
            $response = [
                'status' => true,
                'mensaje' => 'El mecanico exite',
                'mecanico' => $mecanicos
            ];

        }else{
            $response = [
                'status' => false,
                'mensaje' => 'El mecanico  no exite',
                'mecanico' => null,
            ];
        }

        //var_dump($mecanicos);
        echo json_encode($response);
    }
    
    public function buscar($params)
    {
        $this->cors->corsJson();
        $idvehiculo = intval($params['id']);
        $mecanico = Mecanico::find($idvehiculo);

        $response = [];

        if($mecanico == null){
            $response = [
                'status' => false,
                'mensaje' => 'El mecanico no existe',
                'mecanico' => null,
            ];

        }else{
            $response = [
                'status' => true,
                'mensaje' => 'El mecanico existe',
                'mecanico' => $mecanico,
                'persona_id' => $mecanico->persona->id
            ];

        }

        //var_dump($mecanico);
        echo json_encode($response);
    }

    public function search($params){
        $this->cors->corsJson();

        $texto = $params['texto'];
        $sql = "SELECT m.id, p.cedula, p.nombres, p.apellidos, p.telefono, p.correo,  m.status,
        (SELECT usuarios.img FROM usuarios WHERE usuarios.persona_id = p.id) as img
         FROM personas p INNER JOIN mecanicos m ON m.persona_id = p.id WHERE p.estado = 'A'
         and (p.cedula LIKE '%$texto' OR p.nombres LIKE '%$texto%' OR p.apellidos LIKE '%$texto%')";

        $array = $this->conexion->database::select($sql);

        if ($array) {
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'mecanicos' => $array,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No existen coincidencias',
                'mecanicos' => null,
            ];
        }

        echo json_encode($response);
    }
    
    public function reporteOrden($params){
        $this->cors->corsJson();

        $id_mecanico = intval($params['mecanico_id']);
        $inicio = $params['inicio'];
        $fin = $params['fin'];

        if($id_mecanico == -1){
            $dataOrdenes =Orden::where('fecha','>=',$inicio)
                                ->where('fecha','<=',$fin)
                                ->orderBy('estado_orden_id','Desc')
                                ->get();

        }else{
            $dataOrdenes =Orden::where('fecha','>=',$inicio)
                                ->where('fecha','<=',$fin)
                                ->where('mecanico_id',$id_mecanico)
                                ->orderBy('estado_orden_id','Desc')
                                ->get();

        }

        $data = [];

        foreach($dataOrdenes as $do){
            $activitis = Actividad::where('orden_id',$do->id)
                            ->orderBy('id','Desc')
                            ->take(1)->get()->first();
    
                            
            if($activitis != null){

                $aux = [
                    'id' => $do->id,
                    'codigo' => $do->codigo,
                    'actividad' => $activitis,
                    'estado' => $do->estado_orden->detalle,
                    'estado_id' => $do->estado_orden_id,
                    'mecanico' => $do->mecanico->persona,
                    'mecanico_id' => $do->mecanico->id
                ]; 
                
            }else{
                $aux = [
                    'id' => $do->id,
                    'codigo' => $do->codigo,
                    'actividad' => ['detalle' => 'Ninguna', 'total' => 0],
                    'estado' => $do->estado_orden->detalle,
                    'estado_id' => $do->estado_orden_id,
                    'mecanico' => $do->mecanico->persona,
                    'mecanico_id' => $do->mecanico->id
                ]; 
            }
            $data[]= (object)$aux;
        }

        $estado = Estado::where('estado','A')->orderBy('detalle','Desc')->get();
        $mecanicos = Mecanico::where('estado', 'A')->get();

        $labels = [];   $dataOrden = [];    $response = [];
        $labelsMecanico = [];   $dataMecanico = [];

        foreach($estado as $es){
            $labels[] = $es->detalle;
            $c = 0;

            foreach($data as $item){
                if($es->id == $item->estado_id)     $c++;
            }
            $dataOrden[] = $c;  $c = 0;
        }

        $mec = 0;
        foreach($mecanicos as $m){
            foreach($data as $item){
                if($item->mecanico_id == $m->id)    $mec++;
            }

            $nombres = $m->persona->nombres.' '.$m->persona->apellidos;
            $labelsMecanico[] = $nombres;
            $dataMecanico[] = $mec;
            $mec = 0;
        }

        $response = [
            'lista' => $data,
            'orden' => [
                'labels' => $labels,
                'data' => $dataOrden
            ],
            'mecanico' => [
                'labels' => $labelsMecanico,
                'data' => $dataMecanico
            ]
        ];

        echo json_encode($response);
    }
}