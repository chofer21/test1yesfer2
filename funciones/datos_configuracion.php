<?php

include ('../configuracion/conexion_1.php');

$consulta_tiempos = "SELECT descripcion, valor
                     FROM configuracion
                     WHERE descripcion LIKE 'tiempo%'";
$con_tiempos = q( $consulta_tiempos );

$tiempos = "";
foreach( $con_tiempos as $ct ){
    
    $tiempos .= $ct["descripcion"]."=".$ct["valor"]."_YYY_";
    
}

echo ($tiempos);