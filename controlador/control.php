<?php 
error_reporting(0);

$opcion  = $_REQUEST["opcion"];

if($opcion=="sensoresAgregados"){
    // id_puerto | id_puerto
    echo "1-000_2-001_3-010";
    
    
    
}else if($opcion == "calcularRuta"){
    calculo_ruta();
}

?>
