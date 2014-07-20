<?php
include ('../configuracion/conexion_1.php');
require('../modelo/modelo_funciones.php');


$opcion = $_REQUEST["opcion"];

if($opcion=="eliminar_tipo_sensor"){
    $id_tipo_sensor = $_REQUEST["id_tipo_sensor"];
    eliminar_tipo_sensor($id_tipo_sensor);
}else
if($opcion=="eliminar_sensor"){
    $id_sensor = $_REQUEST["id_senso"];
    qr("delete from sensor where id = ".$id_sensor."");
    qr("delete from camino_sensor WHERE sensor_id = ".$id_sensor."");
}else
if($opcion=="eliminar_nodo"){
    $id_nodo = $_REQUEST["id_nodo"];
    
    $ce = getCaminosEliminar($id_nodo); 
    eliminar_nodo($id_nodo);    
       
    echo json_encode($ce);
    
}else 
if($opcion=="crear_nodo"){
    $descripcion = $_REQUEST["descripcion"];
    $tipo = $_REQUEST["tipo"];
    $pos_x = $_REQUEST["pos_x"];
    $pos_y = $_REQUEST["pos_y"];
    $tabla = $_REQUEST['tabla'];
    
    crear_nodo($descripcion, $tipo,$pos_x, $pos_y,$tabla);
}else 
if($opcion=="unir_nodos"){
    $descripcion = $_REQUEST["descripcion"];
    $distancia = $_REQUEST["distancia"];
    $inicio = $_REQUEST["inicio"];
    $fin = $_REQUEST["fin"];
    $tabla = $_REQUEST['tabla'];
    
    unir_nodos($descripcion, $distancia, $inicio, $fin,$tabla);
}else 
if($opcion=="cargar_sensores"){
    $camino_id = $_REQUEST["camino_id"];    
    cargar_sensores($camino_id);
}else 
if($opcion=="cargar_leds"){
    $camino_id = $_REQUEST["camino_id"];
    
    cargar_leds($camino_id);
}
else 
if($opcion=="asignar_sensor"){
    $camino_id = $_REQUEST["camino_id"];
    $sensor_id = $_REQUEST["sensor_id"];
    $tabla = $_REQUEST['tabla'];
    
    asignar_sensor($camino_id,$sensor_id,$tabla);
    echo  ($camino_id ." -- ".$sensor_id);
}else if($opcion == 'asignar_led'){
    
    $camino_id = $_REQUEST['camino_id'];
    $sensor_id = $_REQUEST['sensor_id'];
    $sentido = $_REQUEST['sentido'];
    $tabla = $_REQUEST['tabla'];
    
    q("insert into camino_led (
        camino_id
        ,led_id
        ,sentido
        ,nivel
      ) VALUES (
         ".$camino_id." -- camino_id - IN int(11)
        ,".$sensor_id." -- led_id - IN int(11)
        ,'".$sentido."' -- sentido - IN varchar(20)
        ,".$tabla."    
      )");
    
}else if($opcion == 'retirar_sensor'){
    
    $camino_id = $_REQUEST['camino_id'];
    $sensor_id = $_REQUEST['sensor_id'];
    
    deleteSensor($camino_id, $sensor_id);
    
}else if($opcion == 'retirar_led'){
    
    $camino_id = $_REQUEST['camino_id'];
    $sensor_id = $_REQUEST['sensor_id'];
    
    deleteLed($camino_id, $sensor_id);
    
}else if($opcion == 'eliminar_led'){
    
    $id_led = $_REQUEST['id_led'];
    qr("delete from led where id=".$id_led."");
    qr("delete from camino_led where led_id = ".$id_led."");
    
}else if($opcion == 'agregar_salida'){
    
    $nodo_id = $_REQUEST['nodo_id'];
    $tipo = $_REQUEST['tipo'];
    q("update nodo set tipo = '".$tipo."' where id = ".$nodo_id."");
    
}else if($opcion == 'eliminar_tiempo'){
    
    $id = $_REQUEST['id_led'];
    qr("delete from configuracion where id = ".$id."");
    
}

?>
