<?php
include ('../configuracion/conexion_1.php');

$consulta_sensores = "SELECT id, mux, posicion
			FROM sensor";
		      //ORDER BY mux, posicion";
$con_sensores = q($consulta_sensores);

$datos_sensores = "";
foreach($con_sensores as $cs){
    $datos_sensores .= "op1_";
    $datos_sensores .= "id=".$cs["id"]."_";
    $datos_sensores .= "mux=".$cs["mux"]."_";
    $datos_sensores .= "pos=".$cs["posicion"];
    $datos_sensores .= "_YYY_"; //Separador
}

echo substr( $datos_sensores, 0, -5);

