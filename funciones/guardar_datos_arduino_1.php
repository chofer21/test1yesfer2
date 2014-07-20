<?php
include ('../configuracion/conexion_1.php');

$data = $_REQUEST["data"] ;

$insert = "INSERT INTO 1prueba(valor) VALUES ('$data');";
q($insert);

