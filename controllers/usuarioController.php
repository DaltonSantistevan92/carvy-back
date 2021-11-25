<?php 

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'app/error.php';
require_once 'app/helper.php';
require_once 'models/usuarioModel.php';
require_once 'models/personaModel.php';
require_once 'models/mecanicoModel.php';
require_once 'controllers/personaController.php';

class UsuarioController
{

    private $usuario;
    private $cors;
    private $personaController;

    public function __construct()
    {
        // echo "Soy el controlador usuario";
        //$this->usuario = new UsuarioModel();
        $this->cors = new Cors();
        $this->db = new Conexion();
        $this->personaController = new PersonaController();
    }

    public function index()
    {
        echo "Soy el metodo por default index<br>";
    }

    public function listar()
    {
        //echo "Soy el metodo listar";
        $this->cors->corsJson();

        $usuarios = Usuario::all();
        $response = [];

        for ($i = 0; $i < count($usuarios); $i++) {
            $item = [
                'usuario' => $usuarios[$i],
                'persona' => $usuarios[$i]->persona,
                'rol' => $usuarios[$i]->rol,
            ];
            $response[] = $item;
        }

        echo json_encode($usuarios);
    }

    public function getUsuario($params)
    {
        $id = $params['id'];

        $this->cors->corsJson();
        $usuario = Usuario::find($id);

        if ($usuario) {
            $response = [
                'usuario' => $usuario,
                'persona' => $usuario->persona,
                'rol' => $usuario->rol,
            ];
            echo json_encode($usuario);
        } else {
            ErrorClass::e(404, 'El usuario no existe');
        }
    }

    public function login(Request $request)
    {
        $data = $request->input('credenciales');

        $entrada = $data->entrada;
        $clave = $data->clave;
        $encriptar = hash('sha256', $clave);

        $this->cors->corsJson();
        $response = [];

        if ((!isset($entrada) || $entrada == "") || (!isset($clave) || $clave == "")) {
            $response = [
                'estatus' => false,
                'mensaje' => 'Falta datos',
            ];
        } else {
            $usuario = Usuario::where('usuario', $entrada)->get()->first();
            $persona = Persona::where('correo', $entrada)->get()->first();

            if ($usuario || $persona) {
                $us = $usuario;

                if ($persona) {
                    $us = $persona->usuario[0];
                }

                //Segun con la verificacion de credenciales
                if ($encriptar == $us->clave) {
                    $persona = Persona::find($us->persona->id);

                    $per = $us->persona->nombres . " " . $us->persona->apellidos;
                    $rol = $us->rol->cargo;

                    $response = [
                        'status' => true,
                        'mensaje' => 'Acceso al sistema',
                        'rol' => $rol,
                        'persona' => $per,
                        'usuario' => $us,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'La contraseña es incorrecta',
                    ];
                }
            } else {
                $response = [
                    'estatus' => false,
                    'mensaje' => 'El correo o usuario no existe',
                ];
            }
        }

        echo json_encode($response);
    }

    public function dataTable()
    {
        $this->cors->corsJson();

        $usuarios = Usuario::where('estado', 'A')
            ->orderBy('usuario')
            ->get();

        $data = [];
        $i = 1;

        foreach ($usuarios as $u) {
            $url = BASE . 'resources/usuarios/' . $u->img;
            //$estado = $u->estado == 'A' '<span class="badge bg-success">Activado</span>'?
            $icono = $u->estado == 'I' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-trash"></i>';
            $clase = $u->estado == 'I' ? 'btn-success' : 'btn-danger';
            $other = $u->estado == 'A' ? 0 : 1;

            $botones = '<div class="btn-group">
            <button class="btn btn-warning" onclick="editar_usuario(' . $u->id . ')">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn ' . $clase . '" onclick="eliminar(' . $u->id . ',' . $other . ')">
                ' . $icono . '
            </button>
        </div>';

            $data[] = [
                0 => $i,
                1 => '<div class="box-img-usuario"><img src=' . "$url" . '></div>',
                2 => $u->persona->nombres,
                3 => $u->persona->apellidos,
                4 => $u->usuario,
                5 => $u->rol->cargo,
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
    }

    public function guardar(Request $request)
    {

        $this->cors->corsJson();
        $user = $request->input('usuario');
        $response = [];

        $this->cors->corsJson();

        if (!isset($user) || $user == null) {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'usuario' => null,
            ];
        } else {
            $resPersona = $this->personaController->guardarPersona($request);

            //var_dump($user); die();

            $id_pers = $resPersona['persona']->id;

            $clave = $user->clave;
            $encriptar = hash('sha256', $clave);
            $user->rol_id = intval($user->rol_id);

            $usuario = new Usuario;

            $usuario->persona_id = $id_pers;
            $usuario->rol_id = $user->rol_id;
            $usuario->usuario = $user->usuario;
            $usuario->img = $user->img;
            $usuario->clave = $encriptar;
            $usuario->conf_clave = $encriptar;
            $usuario->estado = 'A';

            //buscar en usuarios el id_persona si existe y validar
            $exis_user = Usuario::where('persona_id', $id_pers)->get()->first();

            if ($exis_user) {
                $response = [
                    'status' => false,
                    'mensaje' => 'El usuario ya se encuentra registrado',
                    'usuario' => null,
                ];
            } else {
                if ($usuario->save()) {
                    //Verificar si hay un rol mecanico
                    if($usuario->rol_id == 3){
                        //Crear un mecanico y guardar
                        $mecanico = new Mecanico;
                        $mecanico->persona_id = $id_pers;
                        $mecanico->fecha_registro= date('Y-m-d');
                        $mecanico->estado= 'A';
                        $mecanico->status= 'D';
                        $mecanico->save();

                    } 

                    $response = [
                        'status' => true,
                        'mensaje' => 'Se ha guardado el usuario',
                        'usuario' => $usuario,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar :C',
                        'usuario' => null,
                    ];
                }
            }

        }

        echo json_encode($response);
    }

    public function subirFichero($file)
    {
        $this->cors->corsJson();
        $img = $file['fichero'];
        $path = 'resources/usuarios/';

        $response = Helper::save_file($img, $path);
        echo json_encode($response);
    }

    public function contar(){
        $this->cors->corsJson();
        $usuarios = Usuario::where('estado','A')->get();
        $response = [];

        if($usuarios){
            $response = [
                'status' => true,
                'mensaje' => 'Existen Usuario',
                'Modelo' => 'Usuario',
                'cantidad' => $usuarios->count()
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No Existen Usuario',
                'Modelo' => 'Usuario',
                'cantidad' => 0
            ];

        }
        echo json_encode($response);
    }

    public function editar(Request $request, $params){
        $this->cors->corsJson();
        $usuaRequest =  $request->inputPut('usuario');
        $id = intval($params['id']);
        $response = [];

        $usua = Usuario::find($id);
        $usua->persona;
        $usua->rol;

        if($usuaRequest){
            if($usua->persona){
                $usua->persona->cedula = $usuaRequest->cedula;
                $usua->persona->nombres = $usuaRequest->nombres;
                $usua->persona->apellidos = $usuaRequest->apellidos;
                $usua->persona->telefono = $usuaRequest->telefono;
                $usua->persona->correo = $usuaRequest->correo;
                $usua->persona->direccion = $usuaRequest->direccion;
                $usua->persona->estado = 'A';

               /*  $usua->persona->save(); */

                $response = [
                    'status' => true,
                    'mensaje' => 'El usuario se ha actualizado',
                    'cliente' => $usua->persona
                ];

            }else{
                $response = [
                    'status' => false,
                    'mensaje' => 'No se pudo actualizar el usuario !!'
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
