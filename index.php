<?php

require_once("clases/conexion/conexion.php");

$conexion = new Conexion();

$sql = "select * from tareas";

print_r($conexion->obtenerDatos($sql));
