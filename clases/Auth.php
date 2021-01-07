<?php

require_once("conexion/Conexion.php");
require_once("Response.php");
require_once './vendor/autoload.php';

use \Firebase\JWT\JWT;

class Auth extends Conexion{

    const ACTIVO = 1;
    const KEY = "xf123dfgew789";

    public function login($data_json){

        $currentTime = time();
        $limitTime = $currentTime + 3600;

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
                    // Generar token
                    $tokenContent = array(
                        'iat' => $currentTime, // Tiempo que inició el token
                        'exp' => $limitTime, // Tiempo que expirará el token (+1 hora)
                        'userData' => [ // información del usuario
                            'id' => $data[0]['id'],
                            'email' => $data[0]['email'],                           
                            'role' => 'ROLE_USER'
                        ]
                    );

                    //$token = $this->insertToken($data[0]['id']);
                    $token = JWT::encode($tokenContent, self::KEY);
                    if ($token) {
                        $result = $response->response;
                        $result['result'] = [
                            'token' => $token
                        ];
                        return $result;
                    } else {
                        return $response->sendResponse(500);
                    }
                    
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
        $query = "SELECT id, email, password, status FROM users WHERE email = '$email'";
        $data = parent::obtenerDatos($query);
        if (isset($data[0]['id'])) {
           return $data;
        }
        return false;
    }

    private function insertToken($userId){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:m");
        $status = self::ACTIVO;
        $query = "INSERT INTO users_token (user_id,token,status,date) VALUES('$userId','$token','$status','$date')";
        $isCreated = parent::nonQuery($query);
        if ($isCreated) {
            return $token;
        }else {
            return 0;
        }
    }

    public function checkToken($token){
        
        try {
            $dataObject = JWT::decode($token, self::KEY, array('HS256'));
            $dataArray = json_decode(json_encode($dataObject), true);
            $exp = $dataArray['exp'];
            $id = $dataArray['userData']['id'];
            $status = self::ACTIVO;
            $query = "SELECT * FROM users WHERE id = $id AND status = $status ";
    
            $data = parent::obtenerDatos($query);    
    
            if (isset($data[0]['id']) && ($exp > time()) ) {
                return [ 'status'=> true ];
            } 

        } catch (Exception  $e) {
            
            return [ 'status'=> false , 'msg'=> $e->getMessage()];
        }       
         
    }

    public function getObjet($token){
        $dataObject = JWT::decode($token, self::KEY, array('HS256'));
        $dataArray = json_decode(json_encode($dataObject), true);
        return $dataArray['userData']['id'];;
    }
}