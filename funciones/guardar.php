<?php
include ('../configuracion/conexion.php');

$opcion = $_REQUEST["opcion"];
if($opcion=="nuevo_tipo_sensor"){

    $descripcion = $_REQUEST["descripcion"];
    $rangoMax = $_REQUEST["rangoMax"];
    $rangoMin = $_REQUEST["rangoMin"];
    
    q("INSERT INTO tipo_sensor( descripcion, rangoMax, rangoMin) 
                        VALUES('$descripcion',$rangoMax,$rangoMin);");
    
    echo "<script>
                window.location.href ='../vistas/index.php';
        </script>";
    
}

?>
