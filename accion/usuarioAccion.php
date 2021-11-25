<?php

require_once 'app/error.php';

class UsuarioAccion
{

    public function __construct()
    {
        //echo "Soy la clase accionUsuario<br>";
    }

    //Configurar rutas y controllers
    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/usuario') {
                    //echo "Dentro de /usuario";
                    Route::get('/usuario/listar', 'usuarioController@listar');
                } else
                if ($ruta == '/usuario/listar') {
                    Route::get('/usuario/listar', 'usuarioController@listar');
                } else
                if ($ruta == '/usuario/listar' && $params) {
                    //d($params); die();
                    Route::get('/usuario/listar/:id', 'usuarioController@getUsuario', $params);
                } else
                if ($ruta == '/usuario/datatable') {
                    Route::get('/usuario/datatable', 'usuarioController@dataTable');
                } else
                if ($ruta == '/usuario/contar') {
                    Route::get('/usuario/contar', 'usuarioController@contar');
                } else {
                    //$error = new Error();
                    ErrorClass::e(404, "La ruta no existe");
                }
                break;

            case 'post':
                if ($ruta == '/usuario/login') {
                    Route::post('/usuario/login', 'usuarioController@login');
                } else
                if ($ruta == '/usuario/save') {
                    Route::post('/usuario/save', 'usuarioController@guardar');
                } else
                if ($ruta == '/usuario/fichero') {
                    Route::post('/usuario/fichero', 'usuarioController@subirFichero', true);
                } else {
                    ErrorClass::e(404, "La ruta no existe");
                }
                break;

            case 'put';
                if ($params) {
                    if ($ruta == '/usuario/editar') {
                        Route::put('/usuario/editar/:id', 'usuarioController@editar', $params);
                    } else {
                        ErrorClass::e('400', 'No ha enviado parámetros por la url');
                    }
                }
                break;

            case 'delete':
                break;
        }
    }
}
