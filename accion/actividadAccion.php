<?php

require_once 'app/error.php';

class ActividadAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {

            case 'get':
                if ($ruta == '/actividad/listar' && $params) {
                    Route::get('/actividad/listar/:id', 'actividadController@listar', $params);
                }else
                if($ruta == '/actividad/last_porcentaje'){
                    Route::get('/actividad/last_porcentaje/:id_orden', 'actividadController@ultimo_porcentaje', $params);
                }
                else
                if($ruta == '/actividad/reciente' && $params){
                    Route::get('/actividad/reciente/:cantidad', 'actividadController@reciente', $params);
                }
                else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
                break;

            case 'post':
                if ($ruta == '/actividad/save') {
                    Route::post('/actividad/save', 'actividadController@guardar');
                }else
                if ($ruta == '/actividad/save2'){
                    Route::post('/actividad/save2', 'actividadController@guardar2');
                }
                break; 

            case 'delete':
                
                break;
        }
    }
}
