<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'models/productoModel.php';
require_once 'models/categoriaModel.php';
require_once 'core/conexion.php';
require_once 'core/params.php';

class ProductoController
{

    private $cors;
    private $db;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->db = new Conexion();
    }

    public function buscar($params){
        $this->cors->corsJson();
        $idProducto = intval($params['id']);
        $response = [];

        $dataProducto = Producto::find($idProducto);
        $dataProducto->categoria;

        if($dataProducto){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'producto' => $dataProducto
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'producto' => null
            ];
        }

        echo json_encode($response);
    }

    public function verImg($params){
        $this->cors->corsJson();
        $response = [];
        
        $idProducto = intval($params['img']);
        
        $dataProducto = Producto::find($idProducto);

        if($dataProducto){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Datos',
                'producto' => $dataProducto,
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Datos',
                'producto' => null,
            ];
        }  
        echo json_encode($response);
    }

    public function editarimg(Request $request, $params){
        $this->cors->corsJson();
        $imgResquest = $request->inputPut('imagen');
        $id = intval($params['id']);
        $response = [];

        $dataProducto = Producto::find($id);

        if($imgResquest){
            if($dataProducto){
                $dataProducto->img = $imgResquest->img;
                $dataProducto->save();
                $response = [
                    'status' => true,
                    'mensaje' => 'Imagen actualizada correctamente !!'
                ];
            }else{
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo actualizar la imagen !!'
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

    public function guardar(Request $request) 
    {
        $this->cors->corsJson();
        $producto = $request->input("producto");
        $response = [];

        if ($producto) { 
            $producto->nombre = ucfirst($producto->nombre);

            $buscar = Producto::where('codigo', $producto->codigo)
                ->get()->first();

            if ($buscar) {
                $response = [
                    'status' => false,
                    'mensaje' => 'El código del producto ya existe',
                    'producto' => null,
                ];
            } else {
                $nuevo = new Producto;
                $nuevo->categoria_id = $producto->categoria_id;
                $nuevo->codigo = $producto->codigo;
                $nuevo->nombre = $producto->nombre;
                $nuevo->img = $producto->img;
                $nuevo->descripcion = $producto->descripcion;
                $nuevo->precio_compra = 0.00;
                $nuevo->stock = 0;
                $nuevo->stock_minimo = $producto->stock_minimo;
                $nuevo->stock_maximo = $producto->stock_maximo;
                $nuevo->precio_venta = $producto->precio_venta;
                $nuevo->margen = $producto->precio_venta;
                $nuevo->fecha = $producto->fecha;
                $nuevo->estado = 'A';

                if ($nuevo->save()) {
                    $response = [
                        'status' => true,
                        'mensaje' => 'Producto guardado',
                        'producto' => $nuevo,
                    ];
                } else {
                    $response = [
                        'status' => true,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'producto' => null,
                    ];
                }
            }
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'producto' => null,
            ];
        }

        echo json_encode($response);
    }

    public function subirFichero($file) 
    {
        $this->cors->corsJson();

        $target_path = "resources/productos/";

        $imagen = $file['fichero'];
        $target_path = $target_path . basename($imagen['name']);

        $enlace_actual = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $enlace_actual = str_replace('index.php', '', $enlace_actual);

        $response = [];

        if (move_uploaded_file($imagen['tmp_name'], $target_path)) {
            $response = [
                'status' => true,
                'mensaje' => 'Fichero subido',
                'imagen' => $imagen['name'],
                'direccion' => $enlace_actual . '/' . $target_path,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se pudo guardar el fichero',
                'imagen' => null,
                'direccion' => null,
            ];
        }

        echo json_encode($response);
    } 

    public function listar()
    {
        $this->cors->corsJson();
        $productos = Producto::where('estado', 'A')
            ->orderBy('nombre')
            ->get();

        $response = [];
        foreach ($productos as $p) {
            $response[] = [
                'producto' => $p,
                'categoria_id' => $p->categoria->id,
            ];
        }
        echo json_encode($productos);
    }

    public function dataTable()
    {
        $this->cors->corsJson();

        $productos = Producto::where('estado', 'A')
            ->orWhere('estado', 'I')
            ->orderBy('nombre')
            ->get();

        $data = [];
        $i = 1; 

        foreach ($productos as $p) {
            $url = BASE . 'resources/productos/' . $p->img;
            $estado = $p->estado == 'A' ? '<span class="badge bg-success">Activado</span>' : '<span class="badge bg-danger">Desactivado</span>';
            $icono = $p->estado == 'I' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>';
            $clase = $p->estado == 'I' ? 'btn-success' : 'btn-danger';
            $other = $p->estado == 'A' ? 0 : 1;

            $botones = '<div class="btn-group">
                <button class="btn btn-sm btn-warning" onclick="editar_producto(' . $p->id . ')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-sm btn ' . $clase . '" onclick="eliminar(' . $p->id . ',' . $other . ')">
                    ' . $icono . '
                </button>
                <button id="btn-img" class="btn btn-sm btn-primary" onclick="ver_img(' . $p->id . ')">
                    <i class="far fa-image"></i>
                </button>
            </div>';

            $span = "";
            if ($p->stock < 6) {
                $span = '<span class="badge bg-danger" style="font-size: 1.2rem;">' . $p->stock . '</span>';
            } else
            if ($p->stock >= 6 && $p->stock < 11) {
                $span = '<span class="badge bg-warning" style="font-size: 1.2rem;">' . $p->stock . '</span>';
            } else {
                $span = '<span class="badge bg-success" style="font-size: 1.2rem;">' . $p->stock . '</span>';
            }

            $data[] = [
                0 => $i,
                1 => '<div class="box-img-producto"><img src=' . "$url" . '></div>',
                2 => $p->codigo,
                3 => $p->nombre,
                4 => $p->categoria->categoria,
                5 => number_format($p->precio_compra,2,'.',''), 
                6 => number_format($p->precio_venta,2,'.',''),
                7 => $span,
                8 => $estado,
                9 => $botones,
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
        $estado = $params['estado'];

        $producto = Producto::find($id);
        $response = null;

        if ($producto) {
            $producto->estado = $estado;
            $producto->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha actualizado el estado',
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se encuentra el producto',
            ];
        }

        echo json_encode($response);
    }

    public function search($params)
    {
        $this->cors->corsJson();
        $texto = strtolower($params['texto']);

        $productos = Producto::where('nombre', 'like', $texto . '%')
            ->where('estado', 'A')
            ->orWhere('codigo', '=', $texto)
            ->get();
        $response = [];

        if ($texto == "") {
            $response = [
                'status' => true,
                'mensaje' => 'Todos los registros',
                'productos' => $productos,
            ];
        } else {
            if (count($productos) > 0) {
                $response = [
                    'status' => true,
                    'mensaje' => 'Coincidencias encontradas',
                    'productos' => $productos,
                ];
            } else {
                $response = [
                    'status' => false,
                    'mensaje' => 'No hay registros',
                    'productos' => null,
                ];
            }
        }
        echo json_encode($response);
    }

    public function contar(){
        $this->cors->corsJson();
        $producto = Producto::where('estado','A')->get();
        $response = [];

        if($producto){
            $response = [
                'status'  => true,
                'mensaje' => 'Existen datos',
                'Modelo' => 'producto',
                'cantidad' => $producto->count()
            ];
        }else{
            $response = [
                'status'  => false,
                'mensaje' => 'No existen datos',
                'Modelo' => 'producto',
                'cantidad' => 0
            ];
        }
        echo json_encode($response);
    }

    public function graficaStock(){
        $this->cors->corsJson();

        $productos = Producto::where('estado', 'A')->get();
        $categorias = Categoria::where('estado','A')->get();
        $data = []; $response = [];

        if($categorias){
            foreach($categorias as $c){
                $cats = []; $_cont = 0;

                foreach($productos as $p){
                    if($c->id == $p->categoria->id){
                        $_cont += $p->stock;
                    }
               }

               $cats = [
                   'x' => $c->categoria,
                   'valor' => $_cont
               ];
               array_push($data, $cats);
            }

            $response = [
                'status' => true,
                'mensaje' => 'Hay datos disponibles',
                'data' => $data
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos disponibles',
                'data' => null
            ];
        }
        echo json_encode($response);
    }

    public function editar(Request $request,$params){
        $this->cors->corsJson();
        $proRequest = $request->inputPut('producto');
        $id = intval($params['id']);
        $response = [];

        $prod = Producto::find($id);
        
        if($proRequest){
            if($prod){
                $prod->codigo = $proRequest->codigo;
                $prod->nombre = $proRequest->nombre;
                $prod->img = $prod->img;
                $prod->descripcion = $proRequest->descripcion;
                $prod->stock = $prod->stock;
                $prod->precio_compra = $prod->precio_compra;
                $prod->precio_venta = $proRequest->precio_venta;
                $prod->margen = $prod->margen; 
                $prod->fecha = date('Y-m-d');
                $prod->estado = 'A';
                $prod->save();

                $response = [
                    'status' => true,
                    'mensaje' => 'El producto se ha actualizado',
                    'cliente' => $prod
                ];
            }else{
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo actualizar el producto !!'
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

    public function agotarse($params){

        $this->cors->corsJson();

        $categoria_id = intval($params['categoria_id']);
        $limite = intval($params['limite']);
        $productos = [];

        //Todos
        if($categoria_id == -1){
            $productos = Producto::where('stock', '<=', $limite)->orderBy('nombre')->get();
        }else{
            $productos = Producto::where('categoria_id', $categoria_id)
                            ->where('stock', '<=', $limite)->orderBy('nombre')->get();
        }

        if($productos){
            foreach($productos as $item){
                $item->categoria;
            }
        }else{
            $productos = [];
        }

        echo json_encode($productos);
    }
}
