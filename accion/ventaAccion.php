<?php

require_once 'app/error.php';

class VentaAccion
{
    public function index($metodo_http, $ruta, $params = null)
    {
        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/venta/listar' && $params) {
                    Route::get('/venta/listar/:id', 'ventaController@buscar', $params);
                }else 
                if ($ruta == '/venta/listar') {
                    Route::get('/venta/listar', 'ventaController@listar');
                }else
                if ($ruta == '/venta/datatable' && $params) {
                    Route::get('/venta/datatable/:opcion', 'ventaController@listarTabla', $params);
                }else
                if($ruta == '/venta/total'){
                    Route::get('/venta/total', 'ventaController@total'); 
                }else
                if($ruta == '/venta/frecuentes' && $params){
                    Route::get('/venta/frecuentes/:inicio/:fin/:limit', 'ventaController@comprafrecuentes',$params); 
                }else
                if($ruta == '/venta/mensuales' && $params){
                    Route::get('/venta/mensuales/:inicio/:fin', 'ventaController@ventaMensuales',$params);
                }
                else
                if($ruta == '/venta/mensualesproducto' && $params){
                    Route::get('/venta/mensualesproducto/:inicio/:fin/:producto_id', 'ventaController@ventaMensualesProducto',$params);
                }
                else
                if( $ruta == '/venta/categoria' && $params){
                    Route::get('/venta/categoria/:inicio/:fin/:categoria_id', 'ventaController@ventaMensualCategoria', $params);
                }
                else
                if( $ruta == '/venta/proyeccion' && $params){
                    Route::get('/venta/proyeccion/:year', 'ventaController@proyeccion', $params);
                }
                else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/venta/save') {
                    Route::post('/venta/save', 'ventaController@guardar');
                }else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'delete':
                // if ($params) {
                //     if ($ruta == '/vehiculo/eliminarClienteVehiculo') {
                //         Route::delete('/categoria/eliminarClienteVehiculo/:id_vehiculo/:id_cliente', 'vehiculoController@eliminarClienteVehiculo', $params);
                //     }
                // } else {
                //     ErrorClass::e('400', 'No ha enviado parámetros por la url');
                // }
                break;
        }
    }
}

