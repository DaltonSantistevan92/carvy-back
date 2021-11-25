<?php

require_once 'models/permisoModel.php';
require_once 'models/menuModel.php';
require_once 'app/cors.php';

class PermisoController
{

    private $cors;

    public function __construct()
    {
        $this->cors = new Cors();
    }

    public function index()
    {
        echo "Metodo index de permisoController<br>";
    }


    public function newPermiso($params)
    {
        $this->cors->corsJson();
        $id_rol = intval($params['id']);

        $accesos = Permiso::where('rol_id', $id_rol)->get();
        $response = [];

        if (count($accesos) > 0) {
            $menus_padres = [];
            $menus_hijos = [];
            $menusPadresOrdenadosAccesos = [];
            $menuFinal = [];

            $bdMenusPadres = Menu::where('id_seccion', 0)
                ->where('estado', 'A')
                ->orderBy('pos')->get();

            //Separar menus padres de hijos que tienen acceso
            foreach ($accesos as $item) {
                $aux = [
                    'id' => $item->menu->id,
                    'nombre' => $item->menu->menu,
                    'icono' => $item->menu->icono,
                    'url' => $item->menu->url,
                    'id_seccion' => $item->menu->id_seccion,
                    // 'menus_hijos' => $menus_hijos
                ];

                if ($item->menu->id_seccion == 0) {
                    $menus_padres[] = $aux;
                } else {
                    $menus_hijos[] = $aux;
                }
            }

            //Ordenar los menus padres solo con acceso
            foreach ($bdMenusPadres as $ordenados) {
                foreach ($menus_padres as $desorden) {
                    if ($ordenados->id === $desorden['id']) {
                        $menusPadresOrdenadosAccesos[] = (object) $desorden;
                    }

                }
            }

            foreach ($menusPadresOrdenadosAccesos as $padre) {
                $menus_hijos_ordenados = Menu::where('estado', 'A')
                    ->where('id_seccion', $padre->id)
                    ->orderBy('pos')->get();

                $hijos_ordenados = [];

                $auxFinal['id'] = $padre->id;
                $auxFinal['nombre'] = $padre->nombre;
                $auxFinal['icono'] = $padre->icono;
                $auxFinal['url'] = $padre->url;

                if (count($menus_hijos_ordenados) > 0) {
                    foreach ($menus_hijos_ordenados as $ordenado) {
                        foreach ($menus_hijos as $desorden) {
                            if ($desorden['id'] === $ordenado->id) {
                                $hijos_ordenados[] = (object) $desorden;
                            }
                        }
                    }

                    $auxFinal['menus_hijos'] = $hijos_ordenados;
                } else {
                    $auxFinal['menus_hijos'] = [];
                }

                $menuFinal[] = $auxFinal;
            }
 
            // die();
            $response = [
                'status' => true,
                'mensaje' => 'Hay información',
                'menus' => $menuFinal,
            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No hay menus para el rol',
                'menus' => [],
            ];
        }

        echo json_encode($response['menus']);
    }

    public function menu()
    {

        $this->cors->corsJson();
        $response = [];

        $menus = $this->getMenus(0);

        if ($menus) {
            $response = [
                'status' => true,
                'menu_padre' => $menus,
            ];
        } else {
            $response = [
                'status' => true,
                'menu_padre' => [],
            ];
        }

        echo json_encode($response);
    }

    private function getMenus($id_seccion)
    {
        $menus = Menu::where('estado', 'A')->where('id_seccion', $id_seccion)->get();

        if ($menus) {
            return $menus;
        } else {
            return false;
        }
    }

    public function lista(){

        $this->cors->corsJson();
        $menus = $this->getMenus('0');
        $data = [];

        for($i = 0; $i < count($menus); $i++){
            $hijos = $this->getMenus($menus[$i]->id);
            $padre = $menus[$i];

            $object = (object)[
                'padre' => $padre,
                'hijos' => $hijos
            ];
            $data[] = $object;
        }

        echo json_encode($data);
    }

    public function getPermisoRol($params){

        $this->cors->corsJson();

        $rol_id = intval($params['rol']);
        $permisos = Permiso::where('estado', 'A')->where('rol_id', $rol_id)->get();

        echo json_encode($permisos);
    }

    public function otorgar(Request $request){
        $data = $request->input('permiso');
        $data->rol_id = intval($data->rol_id);
        $data->menu_id = intval($data->menu_id);
        
        if($data->permiso == 'N'){
            $data->permiso_id = intval($data->permiso_id);

            $permiso = Permiso::find($data->permiso_id);
            $response = [];

            $permiso->delete();
            $response = [
                'status' => true,
                'mensaje' => 'Se ha eliminado el permiso :('
            ];
        }else{
            //Hacer un insert
            $nuevo = new Permiso;
            $nuevo->rol_id = $data->rol_id;
            $nuevo->menu_id = $data->menu_id;
            $nuevo->acceso = 'S';
            $nuevo->estado = 'A';
            $nuevo->save();

            $response = [
                'status' => true,
                'mensaje' => 'Se ha otorgado el permiso :)'
            ];
        }

        echo json_encode($response);
    }
    
    
}
