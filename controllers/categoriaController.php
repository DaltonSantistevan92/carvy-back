<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'models/categoriaModel.php';
require_once 'core/conexion.php';

class CategoriaController
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
        $categorias = Categoria::where('estado', 'A')->orderBy('categoria')->get();
        $response = [];

        if (count($categorias) > 0) {
            $response = $categorias;
        }

        echo json_encode($response);
    }

    public function guardar(Request $request)
    {
        $this->cors->corsJson();
        $cat = $request->input("categoria");

        $nombre = ucfirst($cat->nombre);
        $response = [];

        if ($cat) {
            $nuevo = new Categoria; 
            $existe = Categoria::where('categoria', $nombre)->get()->first();

            if ($existe) {
                $response = [
                    'status' => false,
                    'mensaje' => 'La categoría ya existe',
                    'categoria' => null,
                ];
            } else {
                $nuevo->categoria = $nombre;
                $nuevo->fecha = date('Y-m-d');
                $nuevo->estado = 'A';

                if ($nuevo->save()) {
                    $response = [
                        'status' => true,
                        'mensaje' => 'Guardando los datos',
                        'categoria' => $nuevo,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'categoria' => null,
                    ];
                }
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'categoria' => null,
            ];
        }

        echo json_encode($response);
    }

    public function editar(Request $request, $params){

        $this->cors->corsJson();
        $catRequest = $request->inputPut('categoria');
        $id = intval($params['id']);

        $cat = Categoria::find($id);
        $response = [];

        if($catRequest){
            if($cat){
                $cat->categoria = $catRequest->categoria;
                $cat->save();
    
                $response = [
                    'status' => true,
                    'mensaje' => 'Categoría actualizada correctamente !!'
                ];
            }else{
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo actualizar la categoría !!'
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

        $categoria = Categoria::find($id);
        $response = [];

        if ($categoria) {
            $categoria->estado = 'I';
            $categoria->save();

            $response = [
                'status' => true,
                'mensaje' => 'La categoría ha sido Eliminada',
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'La categoría no existe :(',
            ];
        }

        echo json_encode($response);
    }

    public function paginar($params)
    {
        $this->cors->corsJson();
        $pagina = $params['id'];

        $total_pagina = 5;
        $total = count(Categoria::where('estado', 'A')->get());
        $salto = ($pagina - 1) * $total_pagina;
        $cant_paginas = $total / $total_pagina;

        /* $categorias = $this->db->database::table('categorias')
            ->where('estado', 'A')
            ->orderBy('categoria')
            ->skip($salto)->take($total_pagina)->get(); */

            $categorias = Categoria::where('estado', 'A')
            ->orderBy('categoria')
            ->skip($salto)->take($total_pagina)->get();

        $cant_paginas = ceil($cant_paginas);

        foreach($categorias as $item){
            $item->producto;
        }

        $response = [
            'total_registros' => $total,
            'pagina_actual' => intval($pagina),
            'total_paginas' => $cant_paginas,
            'primera_pagina' => 1,
            'ultima_pagina' => $cant_paginas,
            'categorias' => $categorias,

        ];

        echo json_encode($response);
    }

    public function buscar_categoria_producto($params){
        $this->cors->corsJson();
        $categoria_id = intval($params['id']);

        $categoria = Categoria::find($categoria_id);
        $categoria->producto;
        echo json_encode($categoria);

    }
    

    public function contar(){
        $this->cors->corsJson();
        $categorias = Categoria::where('estado','A')->get();
        $response = [];

        if($categorias){
            $response = [
                'status'  => true,
                'mensaje' => 'Existen datos',
                'Modelo' => 'categorias',
                'cantidad' => $categorias->count()
            ];
        }else{
            $response = [
                'status'  => false,
                'mensaje' => 'No existen datos',
                'Modelo' => 'categoria',
                'cantidad' => 0
            ];
        }
        echo json_encode($response);
    }

    public function grafica_stock_productos(){
        $this->cors->corsJson();


    }

    public function grafica_porcentaje(){

        $this->cors->corsJson();
        $categoria = Categoria::where('estado', 'A')->get();
        
        $labels = [];
        $data = [];
        $dataPorcentaje = [];

        $response = []; 

        foreach($categoria as $item){
            $productos = $item->producto;
            $labels[] = $item->categoria;
            
            $data[] = count($productos);

        }
        $suma = 0;
        for($i=0; $i<count($data); $i++){
            $suma += $data[$i];
        }

        for($i=0; $i<count($data); $i++){
            $aux =  ((100 * $data[$i] ) / $suma);
            $dataPorcentaje[] = round($aux,2);
        }



        $response = [
            'status' => true,
            'mensaje' =>'Existen datos',
            'datos' => [
                'labels' => $labels,
                'data' => $data,
                'porcentaje'=> $dataPorcentaje
            ]
        ];

        echo json_encode($response);


    }

    public function one($params){

        $this->cors->corsJson();

        $id = intval($params['id']);
        $cat = Categoria::find($id);
        $response = [];

        if($cat){
            $response = [
                'status' => true,
                'data' => $cat
            ];
        }else{
            $response = [
                'status' => false,
                'data' => []
            ];
        }
        echo json_encode($response);
    }

}
