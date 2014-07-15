<?php
include ('../configuracion/conexion_1.php');

$opcion = $_REQUEST["opcion"];
if($opcion=="nuevo_tipo_sensor"){

    $descripcion = $_REQUEST["descripcion"];
    $rango_max = $_REQUEST["rango_max"];
    $rango_min= $_REQUEST["rango_min"];
    
    q("INSERT INTO tipo_sensor( descripcion, rango_max, rango_min) 
                        VALUES('$descripcion',$rango_max,$rango_min);");
    
    echo "<script>
                window.location.href ='../vistas/nuevo_tipo_sensor.php';
        </script>";
    
}else 
if($opcion=="nuevo_sensor"){

    $descripcion = $_REQUEST["descripcion"];
    $tipo_sensor_id = $_REQUEST["tipo_sensor_id"];
    $mux = $_REQUEST["mux"];
    $posicion = $_REQUEST["posicion"];
    
    q("INSERT INTO sensor( descripcion, tipo_sensor_id, mux, posicion) 
                   VALUES('$descripcion',$tipo_sensor_id,$mux, $posicion); ");
    
    echo "<script>
                window.location.href ='../vistas/nuevo_sensor.php';
        </script>";
    
}else 
if($opcion=="nuevo_nodo"){

    $descripcion = $_REQUEST["descripcion"];
    $tipo_nodo = $_REQUEST["tipo_nodo"];
    
    q("INSERT INTO nodo(descripcion,tipo) 
                      VALUES('$descripcion','$tipo_nodo'); ");
    
    echo "<script>
                window.location.href ='../vistas/nuevo_nodo.php';
        </script>";
    
}   else 
if($opcion=="nuevo_camino"){

    $descripcion = $_REQUEST["descripcion"];
    $distancia = $_REQUEST["distancia"];
    $inicio = $_REQUEST["inicio"];
    $fin = $_REQUEST["fin"];
    
    
    q("INSERT INTO camino(descripcion,distancia,inicio,fin) 
                   VALUES('$descripcion','$distancia',$inicio, $fin); ");
    
    echo "<script>
                window.location.href ='../vistas/nuevo_camino.php';
        </script>";
    
}else if($opcion == 'nuevo_led'){
    
    $des = $_REQUEST['descripcion'];
    $mux = $_REQUEST['mux'];
    $pos = $_REQUEST['posicion'];
    
    q("insert into led (
        descripcion
        ,mux
        ,posicion
      ) VALUES (
         '".$des."' -- descripcion - IN varchar(11)
        ,".$mux."   -- mux - IN int(11)
        ,".$pos."   -- posicion - IN int(11)
      )");

         echo "<script>
                window.location.href ='../vistas/nuevo_led.php';
        </script>";
    
}   
    

    

?>
