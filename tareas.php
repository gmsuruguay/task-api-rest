<?php

require_once("clases/Tarea.php");
require_once("clases/Response.php");

$response = new Response();
$tarea = new Tarea();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        header('Content-Type: aplication/json');
        http_response_code(200);
        if(isset($_GET['page'])){
            $page = $_GET['page'];
            $data = $tarea->getList($page);
            echo json_encode($data);
        }elseif(isset($_GET['id'])){
            $id = $_GET['id'];
            $data = $tarea->getView($id);
            echo json_encode($data);
        }
        break;
    case 'POST':
        $postBody = file_get_contents('php://input');
        $data = $tarea->post($postBody);
        // Respuesta
        header('Content-Type: aplication/json');
        if (isset($data['result']['error_id'])) {
            $code = $data['result']['error_id'];
            http_response_code($code);
        } else {
            http_response_code(200);
        }
        
        echo json_encode($data);
        break;
    case 'PUT':
        $postBody = file_get_contents('php://input');
        $data = $tarea->put($postBody);
        // Respuesta
        header('Content-Type: aplication/json');
        if (isset($data['result']['error_id'])) {
            $code = $data['result']['error_id'];
            http_response_code($code);
        } else {
            http_response_code(200);
        }
        
        echo json_encode($data);
        break; 
    case 'DELETE':
        $headers = getallheaders();
       
        if (isset($headers['token']) && isset($headers['id'])) {
           $send = [
               'token' => $headers['token'],
               'id' => $headers['id']
           ];
           $postBody = json_encode($send);
        } else {
            $postBody = file_get_contents('php://input');
        }       
        
       
        $data = $tarea->delete($postBody);
        // Respuesta
        header('Content-Type: aplication/json');
        if (isset($data['result']['error_id'])) {
            $code = $data['result']['error_id'];
            http_response_code($code);
        } else {
            http_response_code(200);
        }
        
        echo json_encode($data);
        break;   
    default:
        header('Content-Type: aplication/json');
        $data = $response->sendResponse(405);   
        http_response_code($data['result']['error_id']);
        echo json_encode($data);
        break;
}