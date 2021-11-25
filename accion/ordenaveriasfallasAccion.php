<?php

require_once 'app/error.php';

class OrdenAveriasFallasAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {

            case 'get':
                if ($ruta == '/ordenAveriasFallas/listar') {
                    Route::get('/ordenAveriasFallas/listar', 'ordenaveriasfallasController@listar');
                }
                else
                if($ruta == '/ordenAveriasFallas/listar'){
                    Route::get('/ordenAveriasFallas/listar', 'ordenaveriasfallasController@listar');
                }
                break;

            case 'post':
               
                break;

            case 'delete':
               
                break;
        }
    }
}
