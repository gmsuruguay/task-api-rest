<?php

class Conexion{

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    public function __construct()
    {
        $datosConexion = $this->datosConexion();
        
        foreach ($datosConexion as $value) {

            $this->server = $value["server"];
            $this->user = $value["user"];
            $this->password = $value["password"];
            $this->database = $value["database"];
            $this->port = $value["port"];
        }

        $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);

        if ($this->conexion->connect_errno) {
           echo "problemas con la conexión";
           die;
        }
    }

    private function datosConexion(){

        $direccion = dirname(__FILE__);
        $jsonData = file_get_contents($direccion ."/". "config.json");

        return json_decode($jsonData,true);
    }

    private function convertirUtf8(){
        // implementar
    }

    public function obtenerDatos($sql){

        $results = $this->conexion->query($sql);
        
        $resultArray = [];
        foreach ($results as $key) {
            $resultArray[] = $key;
        }

        return $resultArray;

    }

    public function nonQuery($sql){
        $results = $this->conexion->query($sql);
        return $this->conexion->affected_rows;
    }

    public function nonQueryId($sql){
        $results = $this->conexion->query($sql);
        $filas = $this->conexion->affected_rows;
        if ($filas > 1) {
            return $this->conexion->insert_id;
        } else {
           return 0;
        }
        
    }

    protected function encrypt($pass){
        return md5($pass);
    }

}