<?php
require_once("conexion/Conexion.php");
require_once("Response.php");

class Tarea extends Conexion{

    private $table = 'tareas';   
    private $descripcion = '';
    private $planId = '';

    public function getList($page = 1){
        $init = 0;
        $countByPage = 4;
        if ($page > 1) {
            $init = ($countByPage * ($page - 1)) + 1;
            $countByPage = $countByPage * $page;
        }
        
        $query = "SELECT * FROM " . $this->table . " LIMIT $init, $countByPage";
        
        $data = parent::obtenerDatos($query);
        return $data;
    }

    public function getView($id){
        $query = "SELECT * FROM " . $this->table . " WHERE id =  $id";
        $data = parent::obtenerDatos($query);
        return $data;
    }

    public function post($postBody){
        $response = new Response();
        $data = json_decode($postBody,true);
        if (!isset($data['descripcion']) || !isset($data['plan_id']) ) {
           return $response->sendResponse(400);
        }else{
            $this->descripcion = $data['descripcion'];
            $this->planId = $data['plan_id'];
            $resp = $this->insert();
            
            if ($resp) {
                $result = $response->response;
                $result['result'] = [
                    'id' => $resp
                ];
                return $result;
            } else {
                return $response->sendResponse(500);
            }
            
        }
    }

    private function insert(){        
        $query = "INSERT INTO ". $this->table ." (descripcion, plan_id) VALUES ('$this->descripcion',$this->planId)";
        $resp = parent::nonQueryId($query);       
        return $resp ?? 0 ;
    }
}