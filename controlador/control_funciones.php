<?php
include ('../configuracion/conexion.php');
require('../modelo/modelo_funciones.php');


$opcion = $_REQUEST["opcion"];

if($opcion=="eliminar_tipo_sensor"){
    $id_tipo_sensor = $_REQUEST["id_tipo_sensor"];
    eliminar_tipo_sensor($id_tipo_sensor);
}else
if($opcion=="eliminar_sensor"){
    $id_sensor = $_REQUEST["id_sensor"];
    eliminar_sensor($id_sensor);
}else
if($opcion=="eliminar_nodo"){
    $id_nodo = $_REQUEST["id_nodo"];
    eliminar_nodo($id_nodo);
}else 
if($opcion=="crear_nodo"){
    $descripcion = $_REQUEST["descripcion"];
    $tipo = $_REQUEST["tipo"];
    
    crear_nodo($descripcion, $tipo);
}else 
if($opcion=="unir_nodos"){
    $descripcion = $_REQUEST["descripcion"];
    $distancia = $_REQUEST["distancia"];
    $inicio = $_REQUEST["inicio"];
    $fin = $_REQUEST["fin"];
    
    unir_nodos($descripcion, $distancia, $inicio, $fin);
}else 
if($opcion=="cargar_sensores"){
    $nodo_id = $_REQUEST["nodo_id"];
    
    cargar_sensores($nodo_id);
}
else 
if($opcion=="asignar_sensor"){
    $nodo_id = $_REQUEST["nodo_id"];
    $sensor_id = $_REQUEST["sensor_id"];
    
    asignar_sensor($nodo_id,$sensor_id);
}

?>
