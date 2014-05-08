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
    $delete =" DELETE FROM nodo WHERE id = $id_nodo";
    q($delete);
}

function crear_nodo($descripcion,$tipo){
    $insert="INSERT INTO nodo(descripcion, tipo)
                       VALUES('$descripcion','$tipo'); ";
    q($insert);
    echo mysql_insert_id();
}


function unir_nodos($descripcion, $distancia, $inicio, $fin){
    $insert="INSERT INTO camino(descripcion, distancia, inicio, fin)
                       VALUES('$descripcion','$distancia',$inicio,$fin); ";
    q($insert);
    
}

function cargar_sensores($nodo_id){
        $consulta_tipos_sensores = "SELECT s.id,
                                        s.descripcion,
                                        t.descripcion AS tipo_sensor,
                                        s.mux,
                                        s.posicion,
                                        CASE WHEN ns.id IS NULL THEN 'no' ELSE 'si' END AS asignado,
                                        ns.id AS ns_id,
                                        ns.nodo_id AS nodo_asignado
                                     FROM sensor s
                                        LEFT JOIN nodo_sensor ns ON ns.sensor_id = s.id,
                                        tipo_sensor t
                                     WHERE s.tipo_sensor_id = t.id";
    $con_tipos_sensores = q($consulta_tipos_sensores);
    
    echo json_encode($con_tipos_sensores);
    
}

function asignar_sensor($nodo_id,$sensor_id){
    $insert="INSERT INTO nodo_sensor(nodo_id, sensor_id)
                              VALUES('$nodo_id','$sensor_id'); ";
    q($insert);
    
}

?>
