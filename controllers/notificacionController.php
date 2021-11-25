<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'models/categoriaModel.php';
require_once 'core/conexion.php';
require_once 'models/productoModel.php';
require_once 'models/notificacionModel.php';
require_once 'app/helper.php';

class NotificacionController
{

    private $cors;
    private $db;
    private $limite_notificaciones = 5;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->db = new Conexion();
    }
    
    public function generar(Request $request){

        $this->cors->corsJson();

        $notificacion = $request->input('notificacion');
        $notificacion->rol_id = intval($notificacion->rol_id);
        $dia_semana = date('w');

        $productos = Producto::where('estado', 'A')->get();
        $cant_noti = 0; $arrayNotif = [];

        foreach($productos as $item){
            if($item->stock <= $item->stock_minimo && $item->stock > 0){
                $cant_noti++;   $code = $item->codigo.'-'.$dia_semana;

                $existe = Notificacion::where('codigo', $code)->get()->first();

                if($existe == null){
                    //Crear la notificaciones
                    $nuevo = new Notificacion();
                    $nuevo->rol_id = $notificacion->rol_id;
                    $nuevo->codigo = $code;
                    $nuevo->titulo = $item->nombre.' por agotarse !!';
                    $nuevo->mensaje = 'El producto tiene un stock actual de '.$item->stock.', requiere abastecer !!';
                    $nuevo->icono = 'fas fa-exclamation-circle';
                    $nuevo->leido = 'N';
                    $nuevo->save();
    
                    $arrayNotif[] = $nuevo;
                }
            }
        }
        
        $notificacionesArray = Notificacion::where('leido', 'N')
            ->where('rol_id', $notificacion->rol_id)
            ->orderBy('id', 'DESC')->take($this->limite_notificaciones)->get();

        $newArrayNoti = [];

        if(count($notificacionesArray) > 0){
            foreach($notificacionesArray as $item){
                $fecha  = substr($item->created_at,0,10);
                $hora = substr($item->created_at,11,18);;
        
                $hace  = Helper::hace_tiempo($fecha, $hora);

                $aux = [
                    'id' => $item->id,
                    'rol_id' => $item->rol_id,
                    'codigo' => $item->codigo,
                    'titulo' => $item->titulo,
                    'mensaje' => $item->mensaje,
                    'icono' => $item->icono,
                    'leido' => $item->leido,
                    'hace' => $hace,
                    'short' => substr($item->titulo, 0, 15).'...'
                ];

                $newArrayNoti[] = (object)$aux;
            }
        }

        $response = [
            'status' => true,
            'mensaje' => 'Notificacciones',
            'notificacion' => [
                'cantidad' => count($notificacionesArray),
                'data' => $newArrayNoti
            ],
            'extra' => 'Notificaciones generadas'
        ];

        echo json_encode($response);
    }

    public function all($params){
        //var_dump($params);
        $this->cors->corsJson();
        $cantidad = intval($params['cantidad']);
        $mes = date('m');
        $response = [];
        $dataNoti = Notificacion::whereMonth('created_at',$mes)->orderBy('id','Desc')->take($cantidad)->get();

        if($dataNoti){
            foreach($dataNoti as $dn){
                $dn->leido = 'S';
                $dn->save();
            }
            $response = [
                'status' => true,
                'mensaje' =>  'Existen datos',
                'data' => $dataNoti
            ];
        }else{
            
            $response = [
                'status' => false,
                'mensaje' =>  ' No existen datos',
                'data' => null
            ];
        }

        echo json_encode($response);
    }
}