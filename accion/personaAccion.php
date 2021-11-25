<?php

require_once 'app/error.php';

class PersonaAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/persona/listar' && $params) {
                    Route::get('/persona/listar/:id', 'personaController@listar', $params);
                }
                break;

            case 'post':
                if ($ruta == '/persona/save') {
                    Route::post('/persona/save', 'personaController@guardar');
                }
                break;

        }
    }
}
