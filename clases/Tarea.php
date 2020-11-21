<?php
require_once("conexion/Conexion.php");
require_once("Response.php");

class Tarea extends Conexion{

    private $table = 'tareas';   
    private $descripcion = '';
    private $planId = '';
    private $id;

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

    public function put($postBody){
        $response = new Response();
        $data = json_decode($postBody,true);
        if (!isset($data['id'])) {
           return $response->sendResponse(400);
        }else{
            $this->id = $data['id'];
            $this->descripcion = $data['descripcion'];
            $this->planId = $data['plan_id'];
            $resp = $this->update();
            
            if ($resp) {
                $result = $response->response;
                $result['result'] = [
                    'data' => $data
                ];
                return $result;
            } else {
                return $response->sendResponse(500);
            }
            
        }
    }

    public function delete($postBody){
        $response = new Response();
        $data = json_decode($postBody,true);
        if (!isset($data['id'])) {
           return $response->sendResponse(400);
        }else{
            $this->id = $data['id'];            
            $resp = $this->deleteTask();
            
            if ($resp) {
                $result = $response->response;
                $result['result'] = [
                    'id' => $this->id
                ];
                return $result;
            } else {
                return $response->sendResponse(500);
            }
            
        }
    }

    private function update(){        
        $query = "UPDATE ".$this->table." SET descripcion = '$this->descripcion', plan_id = $this->planId WHERE id = $this->id";
        $resp = parent::nonQuery($query);
        
        return ($resp > 0) ? true : false;
    }

    

    private function deleteTask(){        
        $query = "DELETE FROM " . $this->table . " WHERE id =  $this->id";
        $resp = parent::nonQuery($query);        
        return ($resp > 0) ? true : false;
    }


}