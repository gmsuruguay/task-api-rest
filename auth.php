<?php

require_once("clases/Auth.php");
require_once("clases/Response.php");

$response = new Response();
$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Se reciben datos
    $body = file_get_contents("php://input");

    // Comprobación de datos   
    $data = $auth->login($body);

    // Respuesta
    header('Content-Type: aplication/json');
    if (isset($data['result']['error_id'])) {
        $code = $data['result']['error_id'];
        http_response_code($code);
    } else {
        http_response_code(200);
    }
    
    echo json_encode($data);
}else {
    header('Content-Type: aplication/json');
    $data = $response->sendResponse(405);   
    http_response_code($data['result']['error_id']);
    echo json_encode($data);
}