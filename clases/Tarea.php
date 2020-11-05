<?php
require_once("conexion/Conexion.php");
require_once("Response.php");

class Tarea extends Conexion{

    private $table = 'tareas';    

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
}