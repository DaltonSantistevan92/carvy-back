<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'app/helper.php';
require_once 'core/conexion.php';
require_once 'core/params.php';
require_once 'controllers/ordenaveriasfallasController.php';
require_once 'controllers/averiasController.php';
require_once 'controllers/materialController.php';
require_once 'models/ordenModel.php';
require_once 'models/clienteModel.php';
require_once 'models/estadoModel.php';
require_once 'models/mecanicoModel.php';
require_once 'models/materialModel.php';
require_once 'models/actividadModel.php';

class OrdenController
{

    private $cors;
    private $conexion;
    private $limit_key = 9;


    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function listar()
    {
        $this->cors->corsJson();
        $orden = Orden::where('estado', 'A')
            ->orderBy('id', 'DESC')->get();

        $response = [];

        foreach ($orden as $or) {
            $aux = [
                'orden' => $or,
                'cliente_id' => $or->cliente->persona->id,
                'vehiculo_id' => $or->vehiculo->marca->id,
                'usuario_id' => $or->usuario->persona->id,
                'mecanico_id ' => $or->mecanico->persona->id,
                'estado_orden_id' => $or->estado_orden->id,
            ];
            $response[] = $aux;
        }

        echo json_encode($response);
    }

    public function buscar($params)
    {
        $this->cors->corsJson();
        $idorden = intval($params['id']);

        $orden = Orden::find($idorden);
        $averiaController = new AveriasController;

        $response = [];

        if ($orden == null) {
            $response = [
                'status' => false,
                'mensaje' => 'No Existen datos',
                'orden' => null,
            ];

        } else {

            $averias = $averiaController->getAveriasByOrden($idorden);
            $monto = 0;

            foreach($orden->material as $m){
                $m->producto;
                $monto += $m->producto->precio_venta;
            }

            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'monto' => $monto,
                'orden' => $orden,
                'averias' => $averias,
                'cliente_id' => $orden->cliente->persona->id,
                'vehiculo_id' => $orden->vehiculo->marca->id,
                'usuario_id' => $orden->usuario->persona->id,
                'mecanico_id' => $orden->mecanico->persona->id,
                'estado_orden_id' => $orden->estado_orden->id,
                'materiales' => $orden->material        //Regresa un array de datos en la propiedad material
            ];
        }

        echo json_encode($response);
    }

    public function orden_cliente(){
        $this->cors->corsJson();

        $response = [];

        $atendidas = Orden::where('estado','A')
                    ->where('estado_orden_id','1')
                    ->where('facturado','N') 
                    ->get();

        foreach($atendidas as $aten){
            $aux = [
                'orden' => $aten,
                'cliente_id' => $aten->cliente->persona->id,
                'vehiculo_id' => $aten->vehiculo->marca->id,
                'usuario_id' => $aten->usuario->persona->id,
                'mecanico_id ' => $aten->mecanico->persona->id,
                'estado_orden_id' => $aten->estado_orden->id,
            ];
            $response[] = $aux;

        }

        if($atendidas){
            $response = [
                'status' => true,
                'mensaje' => 'existen datos',
                'atendidas'=>$response
            ];
        }else{
            $response =[
                'status' => false,
                'mensaje' => 'no existen datos'
            ];
        } 
        echo json_encode($response);
    }


    public function guardar(Request $request)
    {
        $this->cors->corsJson();
        $dataorden = $request->input('orden');
        $ordenAveriasFallas = $request->input('averias');

        $helper = new Helper();
        $codigo = $helper->generate_key($this->limit_key);

        $response = []; 

        if ($dataorden) {
            $dataorden->cliente_id = intval($dataorden->cliente_id);
            $dataorden->vehiculo_id = intval($dataorden->vehiculo_id);
            $dataorden->usuario_id = intval($dataorden->usuario_id);
            $dataorden->mecanico_id = intval($dataorden->mecanico_id);
            $dataorden->descripcion = ucfirst($dataorden->descripcion);
            $dataorden->suma = floatval($dataorden->suma);

            //Empieza
            $nuevo = new orden();
            $nuevo->cliente_id = $dataorden->cliente_id;
            $nuevo->vehiculo_id = $dataorden->vehiculo_id;
            $nuevo->usuario_id = $dataorden->usuario_id;
            $nuevo->mecanico_id = $dataorden->mecanico_id;
            $nuevo->fecha = date('Y-m-d');
            $nuevo->hora = date('H:m:s');
            $nuevo->descripcion = $dataorden->descripcion;
            $nuevo->suma = $dataorden->suma;
            $nuevo->fecha_trabajo = $dataorden->fecha_trabajo;
            $nuevo->hora_inicio = $dataorden->hora_inicio;
            $nuevo->fecha_trabajo_salida = $dataorden->fecha_trabajo_salida;
            $nuevo->hora_salida = $dataorden->hora_salida;
            $nuevo->estado_orden_id = 3;
            $nuevo->estado = 'A';
            $nuevo->codigo = $codigo;
            $nuevo->observacion = '';
            $nuevo->facturado = 'N';

            $existe = Orden::where('codigo', $codigo)->get()->first();

            if ($existe) {
                $response = [
                    'status' => false,
                    'mensaje' => 'La orden ya existe',
                    'orden' => null,
                    'ordenaveriasfallas' => null,
                ];

            } else {
                if ($nuevo->save()) {

                    //Guardar ordenAveriasFallas

                    $ordenAveriasFallasController = new OrdenAveriasFallasController();
                    $extra = $ordenAveriasFallasController->guardar($nuevo->id, $ordenAveriasFallas);

                    //var_dump($extra); die();
                    $response = [
                        'status' => true,
                        'mensaje' => 'Guardando los datos',
                        'orden' => $nuevo,
                        'ordenaveriasfallas' => $extra,
                    ];

                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'orden' => null,
                        'ordenaveriasfallas' => null,
                    ];

                }

            }

            //var_dump($existe);

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'La orden ya existe',
                'orden' => null,
                'ordenaveriasfallas' => null,
            ];

        }

        //var_dump($response);  die();
        echo json_encode($response);
    }

    public function listarhoy()
    {
        $this->cors->corsJson();
        $fecha = date('Y-m-d');

        $pendiente = Orden::where('fecha', $fecha)->get();
        $response = [];

        foreach ($pendiente as $pen) {
            $aux = [
                'orden' => $pen,
                'cliente_id' => $pen->cliente->persona->id,
                'vehiculo_id' => $pen->vehiculo->marca->id,
                'usuario_id' => $pen->usuario->persona->id,
                'mecanico_id ' => $pen->mecanico->persona->id,
                'estado_orden_id' => $pen->estado_orden->id,
            ];
            $response[] = $aux;
        }

        //var_dump($pendiente);
        echo json_encode($response);
    }

    public function visualizar($params)
    {
        $this->cors->corsJson();
        $opcion = $params['opcion'];
        $estado = intval($params['estado']);

        $response = $this->ordenesByEstado($estado, $opcion);
        echo json_encode($response);
    }

    private function ordenesByEstado($estado_orden_id, $opcion)
    {
        $hoy = date('Y-m-d');
        $pend = 3;
        $existe = '';
        $response = [];
        $datos = [];
        $averiaController = new AveriasController;
        $matCtrl = new MaterialController;

        if ($opcion == '1') { //hoy
            $pendientes = Orden::where('estado_orden_id', $estado_orden_id)
                ->where('fecha', $hoy)->orderBy('id', 'DESC')->get();

            $existe = (count($pendientes) > 0) ? '1' : '0';
        } else
        if ($opcion == '2') { //ayer
            $ayer = date("Y-m-d", strtotime($hoy . "- 1 days"));

            $pendientes = Orden::where('estado_orden_id', $estado_orden_id)
                ->where('fecha', $ayer)->orderBy('id', 'DESC')->get();

            $existe = (count($pendientes) > 0) ? '1' : '0';
        } else
        if ($opcion == '3') { //Ultimos 5 dias
            $last5days = date("Y-m-d", strtotime($hoy . "- 5 days"));

            $pendientes = Orden::where('estado_orden_id', $estado_orden_id)
                ->where('fecha', '>=', $last5days)
                ->where('fecha', '<=', $hoy)->orderBy('id', 'DESC')->get();

            $existe = (count($pendientes) > 0) ? '1' : '0';
        } else
        if ($opcion == '4') { //ultimo 7 dias
            $last7days = date("Y-m-d", strtotime($hoy . "- 7 days"));

            $pendientes = Orden::where('estado_orden_id', $estado_orden_id)
                ->where('fecha', '>=', $last7days)
                ->where('fecha', '<=', $hoy)->orderBy('id', 'DESC')->get();

            $existe = (count($pendientes) > 0) ? '1' : '0';
        } else {
            $existe = '3';
        }

        if ($existe == '1') {
            foreach ($pendientes as $pen) {
                $averias = $averiaController->getAveriasByOrden($pen->id);
                $materiales =  $matCtrl->getMateriales($pen->id);

                $aux = [
                    'orden' => $pen,
                    'cliente_id' => $pen->cliente->persona->id,
                    'vehiculo_id' => $pen->vehiculo->marca->id,
                    'usuario_id' => $pen->usuario->persona->id,
                    'mecanico_id ' => $pen->mecanico->persona->id,
                    'estado_orden_id' => $pen->estado_orden->id,
                    'averias' => $averias,
                    'materiales' =>$materiales
                ];
                $datos[] = $aux;
            }

            $response = [
                'status' => true,
                'mensaje' => 'Existen ordenes',
                'ordenes' => $datos,
            ];
        } else
        if ($existe == '0') {
            $response = [
                'status' => false,
                'mensaje' => 'No existen datos para la consulta realizadas',
                'ordenes' => null,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'El parametro ingresado no es válido',
                'ordenes' => null,
            ];
        }

        return $response;
    }

    public function dashboard_mecanico($params)
    {
        $this->cors->corsJson();
        $id_persona = intval($params['id_persona']);

        $response = [];

        $mecanico = Mecanico::where('estado', 'A')
            ->where('persona_id', $id_persona)->get()->first();

        if ($mecanico) {
            $mecanico_id = $mecanico->id;
            //pendientes
            $pendientes = Orden::where('estado', 'A')
                ->where('estado_orden_id', 3)
                ->where('mecanico_id', $mecanico_id)->get();

            $cp = $pendientes ? $pendientes->count() : 0;

            //procesos
            $procesos = Orden::where('estado', 'A')
                ->where('estado_orden_id', 4)
                ->where('mecanico_id', $mecanico_id)->get();

            $cpr = $procesos ? $procesos->count() : 0;

            //atendidos
            $atentidos = Orden::where('estado', 'A')
                ->where('estado_orden_id', 1)
                ->where('mecanico_id', $mecanico_id)->get();

            $ca = $atentidos ? $atentidos->count() : 0;

            $response = [
                'status' => true,
                'mensaje' => 'si exite el mecanico',
                'cantidad' => [
                    'pendiente' => $cp,
                    'procesos' => $cpr,
                    'atendidos' => $ca,
                ],
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No exite el Mecanico',
                'cantidad' => [
                    'pendiente' => 0,
                    'procesos' => 0,
                    'atendidos' => 0,
                ],
            ];
        }
        echo json_encode($response);
    }

    public function estado($params)
    {
        $this->cors->corsJson();
        $id_persona = intval($params['id_persona']);
        $id_estado = intval($params['estado_id']);
        $matCtrl = new MaterialController;

        $response = [];

        $mecanico = Mecanico::where('estado', 'A')
            ->where('persona_id', $id_persona)->get()->first();

        if ($mecanico) {
            $mecanico_id = $mecanico->id;
            //pendientes

            $pendientes = Orden::where('estado', 'A')
                ->where('estado_orden_id', $id_estado)
                ->where('mecanico_id', $mecanico_id)->orderBy('id', 'DESC')->get();
 
            $averiaController = new AveriasController;
            foreach ($pendientes as $pen) {
                $averias = $averiaController->getAveriasByOrden($pen->id);
                $materiales =  $matCtrl->getMateriales($pen->id);
                $lasActivity = Actividad::where('orden_id', $pen->id)->orderBy('id','desc')->get()->first();

                $aux = [
                    'orden' => $pen,
                    'cliente_id' => $pen->cliente->persona->id,
                    'vehiculo_id' => $pen->vehiculo->marca->id,
                    'mecanico_id ' => $pen->mecanico->persona->id,
                    'estado_orden_id' => $pen->estado_orden->id,
                    'averias' => $averias,
                    'materiales' => $materiales,
                    'ultima_actividad' => $lasActivity
                ];
                $response[] = $aux;
            }

        }
        echo json_encode($response);

    }

    private function _ordenes($mes, $estado){
        $ordenes = Orden::where('estado', 'A')
                ->where('estado_orden_id', $estado)
                ->whereMonth('created_at',$mes)->get();

        return $ordenes;
    }

    public function count_estado(){
        $this->cors->corsJson();

        $pendiente = 3; $proceso = 4;   $atendido = 1;
        $estados = [$pendiente, $proceso, $atendido];
        $mes = date('m');
        $nombreMes = Helper::mes($mes);

        $ordenes = $this->_ordenes($mes, $pendiente);

        $ordenes2 = $this->_ordenes($mes, $proceso);
        
        $ordenes3 = $this->_ordenes($mes, $atendido);

        $cant1 = ($ordenes->count()) ? $ordenes->count() : 0;
        $cant2 = ($ordenes2->count()) ? $ordenes2->count() : 0;
        $cant3 = ($ordenes3->count()) ? $ordenes3->count() : 0;

        $response = [
            'status' => true,
            'cantidad' => [
                'pendientes' => $cant1,
                'procesos' => $cant2,
                'atendidos' => $cant3
            ],
            'mes' => $nombreMes
        ];

        echo json_encode($response);
    }

    public function grafica_estados(){
        $this->cors->corsJson();

        $pendiente = 3; $proceso = 4;   $atendido = 1; $cancelados = 2;
        $mes = date('m');
        $nombreMes = Helper::mes($mes);

        $total = Orden::where('estado', 'A')->whereMonth('created_at',$mes)->get()->count();

        $ordenes = $this->_ordenes($mes, $pendiente);
        $ordenes2 = $this->_ordenes($mes, $proceso);
        $ordenes3 = $this->_ordenes($mes, $atendido);
        $ordenes4 = $this->_ordenes($mes, $cancelados);

        $cant1 = ($ordenes->count()) ? $ordenes->count() : 0;
        $cant2 = ($ordenes2->count()) ? $ordenes2->count() : 0;
        $cant3 = ($ordenes3->count()) ? $ordenes3->count() : 0;
        $cant4 = ($ordenes4->count()) ? $ordenes4->count() : 0;


        //Calculo del porcentaje
        $p1 = round(($cant1 * 100)/ $total, 2);   //pendientes
        $p2 = round(($cant2 * 100)/ $total,2);   //procesos
        $p3 = round(($cant3 * 100)/ $total,2);   //atendidos
        $p4 = round(($cant4 * 100)/ $total,2);   //cancelados

        $labels = ['Pendientes', 'Procesos', 'Atendidos', 'Cancelados'];
        $data = [$p1, $p2, $p3, $p4];

        $response = [
            'status' => true,
            'total_ordenes' => $total,
            'porcentaje' => [
                'labels' => $labels,
                'data' => $data
            ],
            'mes' => $nombreMes
        ];

        echo json_encode($response);

    }

    public function ordenes_recientes(){
        $this->cors->corsJson();

        $recientes = Orden::where('estado', 'A')
                            ->where('estado_orden_id', '<>', 2)
                            ->orderBy('id', 'DESC')
                            ->get();

        foreach($recientes as $r)
            $r->estado_orden;
            
        echo json_encode($recientes);
    }

    public function actualizar_orden($params)
    {
        $this->cors->corsJson();
        $id_orden = intval($params['id_orden']);
        $id_estado = intval($params['estado_id']);
        $estado_mecanico = ucfirst($params['estado_mecanico']);
        $mensajes = '';

        $orden = Orden::find($id_orden);

        $response = [];
        if ($orden) {
            $mecanico = Mecanico::find($orden->mecanico_id);

            $orden->estado_orden_id = $id_estado;
            $orden->save();

            if ($estado_mecanico == 'D' || $estado_mecanico == 'O') {
                $mecanico->status = $estado_mecanico;
                $mecanico->save();
            }

            switch ($id_estado) {
                case 1:
                    $mensajes = 'La orden ha sido atendida';        break;
                case 2:
                    $mensajes = 'La orden ha sido cancelada';       break;
                case 3:
                    $mensajes = 'La orden se encuentra pendiente';  break;
                case 4:
                    $mensajes = 'La orden se encuentra en proceso'; break;
            }

            $response = [
                'status' => true,
                'mensaje' => $mensajes,
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se puede actualizar la orden',
            ];
        }
        echo json_encode($response);
    }

    /* put */
    public function updateObservacion(Request $request, $params)
    {
        $this->cors->corsJson();

        $response = [];
        $ordenData = $request->inputPut('orden');
        $id = intval($params['id']);

        $orden = Orden::find($id);

        if ($orden) {
            $orden->observacion = $ordenData->observacion;
            $orden->save();

            $response = [
                'status' => true,
                'mensaje' => 'Orden actualizada !!',
                'orden' => $orden
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se pudo actualizar la orden, intente nuevamente !!',
            ];
        }
        echo json_encode($response);
    }

    public function repuesto(Request $request)
    {
        $this->cors->corsJson();
        $response = [];

        $data_repuesto = $request->input('repuesto');
        $data_orden = $request->input('orden');

        foreach($data_repuesto as $dr){

            $nuevo_material = new Material();
            $nuevo_material->orden_id = intval($data_orden->id);
            $nuevo_material->producto_id = intval($dr->producto_id);
            $nuevo_material->comprado = 'N';
            $nuevo_material->cantidad = intval($dr->cantidad);
            $nuevo_material->fecha_registro = date('Y-m-d');

            $nuevo_material->save();  
        }
        $response = [
            'status' => true,
            'mensaje' => 'Se ah guardado el repuesto'
        ];
        echo json_encode($response);
    }
}
