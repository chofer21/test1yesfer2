<?php

function eliminar_tipo_sensor($id_tipo_sensor){
    $validar = "SELECT count(id) AS numero
                FROM sensor
               WHERE tipo_sensor_id = $id_tipo_sensor";
    $val = q($validar);
    $numero = $val[0]["numero"];
    
    if( $numero > 0 ){
        echo "Debe eliminar sensores con este tipo  hay: ".$numero;
    }else{
    }
    
        $delete =" DELETE FROM tipo_sensor WHERE id = $id_tipo_sensor";
        q($delete);
   
}

function eliminar_sensor($id_sensor){
    $delete =" DELETE FROM sensor WHERE id = $id_sensor";
    q($delete);
}

function eliminar_nodo($id_nodo){
    q("DELETE FROM nodo WHERE id = $id_nodo");
    
    $caminos = q("select * from camino where fin = ".$id_nodo." or inicio = ".$id_nodo."");
    foreach($caminos as $c){
        
        q("delete from camino where id = ".$c['id']."");
        q("delete from camino_sensor where camino_id = ".$c['id']."");
        q("delete from camino_led where camino_id = ".$c['id']."");
        
    }
    //q($delete);
    
}

function getCaminosEliminar($id_nodo){
    
    $datos = q("select id from camino where inicio = ".$id_nodo." or fin = ".$id_nodo."");
    return $datos;
    
}

function crear_nodo($descripcion,$tipo,$pos_x, $pos_y,$tabla){
    $insert="INSERT INTO nodo(descripcion, tipo , pos_x, pos_y,nivel)
                       VALUES('$descripcion','$tipo', $pos_x, $pos_y,$tabla); ";
    q($insert);
    echo mysql_insert_id();
}


function unir_nodos($descripcion, $distancia, $inicio, $fin, $tabla){
    $insert="INSERT INTO camino(descripcion, distancia, inicio, fin, nivel)
                       VALUES('$descripcion','$distancia',$inicio,$fin,$tabla); ";
    q($insert);
    echo mysql_insert_id();
    
}

function cargar_leds($camino_id){
    
    $datos = q("SELECT s.id,
       s.descripcion,
       s.mux,
       s.posicion,
       CASE WHEN cs.id IS NULL THEN 'no' ELSE 'si' END AS asignado,
       cs.id AS cs_id,
       cs.sentido,
       cs.camino_id AS camino_asignado
  FROM led s LEFT JOIN camino_led cs ON cs.led_id = s.id");
    
    $datosCamino = q("select inicio, fin from camino where id = ".$camino_id."");
    
    $arrayJson = Array("datos" => $datos, "datosCamino" => $datosCamino);
    
    echo json_encode($arrayJson);
    
}

function cargar_sensores($camino_id){
        $consulta_tipos_sensores = "SELECT  s.id,
                                            s.descripcion,
                                            t.descripcion AS tipo_sensor,
                                            s.mux,
                                            s.posicion,
                                            CASE WHEN cs.id IS NULL THEN 'no' ELSE 'si' END AS asignado,
                                            cs.id AS cs_id,
                                            cs.camino_id AS camino_asignado
                                       FROM sensor s
                                            LEFT JOIN camino_sensor cs ON cs.sensor_id = s.id,
                                            tipo_sensor t
                                      WHERE s.tipo_sensor_id = t.id";
    $con_tipos_sensores = q($consulta_tipos_sensores);
    
    echo json_encode($con_tipos_sensores);
    
}

function asignar_sensor($camino_id,$sensor_id,$tabla){
    $insert="INSERT INTO camino_sensor(camino_id, sensor_id,nivel)
                              VALUES('$camino_id','$sensor_id',".$tabla."); ";
    q($insert);
    
}

function deleteSensor($camino_id, $sensor_id){
    
    q("delete from camino_sensor where camino_id = ".$camino_id." and sensor_id = ".$sensor_id."");
    
}

function deleteLed($camino_id, $sensor_id){
    
    q("delete from camino_led where camino_id = ".$camino_id." and led_id = ".$sensor_id."");
    
}




?>
