<?php

require_once("clases/Auth.php");
require_once("clases/Response.php");

$Response = new Response();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = file_get_contents("php://input");
    $auth = new Auth();
    $data = $auth->login($body);
    print_r(json_encode($data));
}else {
    echo "No permitido";
}