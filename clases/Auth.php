<?php

require_once("conexion/Conexion.php");
require_once("Response.php");

class Auth extends Conexion{

    public function login($data_json){

        $response = new Response();

        $data = json_decode($data_json,true);

        if (!isset($data['usuario']) || !isset($data['password'])) {
            return $response->sendResponse(400);
        }

        $usuario = $data['usuario']; 
        $password = parent::encrypt($data['password']);
        $data = $this->getDataUser($usuario);

        if ($data) {            
            if ($data[0]['password'] == $password) {
                if ($data[0]['status'] == 1) { // Verificar si el usuario esta activo
                    # code...
                } else {
                    return $response->sendResponse(200, "Usuario inactivo");
                }
                
                
            } else {
                return $response->sendResponse(200, "Passwod Invalido");
            }       
        } else {
            return $response->sendResponse(401);
        }
        
    }

    private function getDataUser($email){
        $query = "SELECT id, password, status FROM users WHERE email = '$email'";
        $data = parent::obtenerDatos($query);
        if (isset($data[0]['id'])) {
           return $data;
        }
        return false;
    }
}