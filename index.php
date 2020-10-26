<?php

require_once("clases/conexion/Conexion.php");

$conexion = new Conexion();

$sql = "select * from tareas";

print_r($conexion->obtenerDatos($sql));
