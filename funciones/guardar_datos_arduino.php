<?php
include ('../configuracion/conexion_1.php');

$todos_los_sensores = explode("_YYY_", $_REQUEST["data"]); 

//data es el nombre con el que se mandan los datos desde java.
//Con el explode separamos la cadena segun el separador utilizado en Arduino, en este caso _YYYY_.
//Ahora tenemos un array llamado $todos los sensores donde en cada posición tiene el mux, la posición y el dato
// pero tenemos que separarlo .
        
//Ahora consultamos todos los sensores del sistema , con su mux,posicion y rango.

$consulta_sensores = "SELECT    s.id,
                                s.mux,
                                s.posicion,
                                t.rango_max,
                                t.rango_min
                           FROM sensor s, tipo_sensor t
                          WHERE t.id = s.tipo_sensor_id";
$con_sensores = q($consulta_sensores);

$sensores_array = array();
foreach ($con_sensores as $cs) {
    $sensores_array[ $cs["mux"]."-".$cs["posicion"] ]["id"] = $cs["id"];
    $sensores_array[ $cs["mux"]."-".$cs["posicion"] ]["rango_max"] = $cs["rango_max"];
    $sensores_array[ $cs["mux"]."-".$cs["posicion"] ]["rango_min"] = $cs["rango_min"];
}
//Ahora tenemos otro array donde las keys son mux-posicion y tenemos el id, rango max y rango min


$informe = "normal";

foreach($todos_los_sensores as $ts){
    if($ts){ //Si la posicion del array tiene datos
        
        $datos_array = explode("_", $ts ); //Separamos los datos con el separador X-
        
            // tendremos un array con los datos del sensor de la posicion actual
            // pos 1 mux='dato_mux' 
            // pos 2 posicion='dato_posicion'
            // pos 3 dato='dato_sensor'
        
        $datos = array();       
        foreach($datos_array as $da){ 
            $da = explode("=",$da);   //Separamos por el parentesis
            $datos[ $da[0] ] = $da[1]; // $datos [ mux ] = 'dato_mux';
                                       // $datos [ posicion ] = 'dato_posicion';
                                       // $datos [ dato ] = 'dato_sensor';
        }
        
        $valor = (5 * $datos["dato"] * 100 ) / 1024;
        //El dato lo tenemos como lo entrego el sensor, entonces con esta formula se pasa a grados celsius
        
        $sensor_actual = $sensores_array[$datos["mux"]."-".$datos["posicion"]];
        //Obtenemos el sensor actual
        
        $id = $sensor_actual["id"];
        $rango_max = $sensor_actual["rango_max"];
        $rango_min = $sensor_actual["rango_min"];

        $validez = "";
        if( $rango_max >= $valor && $valor >= $rango_min ){
            $validez = "si";
        }else{
            $validez = "no";
            $informe = "alarma";
        }
        //Si el valor esta dentro del rango aceptado entonces es valido
        
        $insert_values .= " ( $id, now(), ".$valor.",'$validez', 'ultimo'),";
        //Creamos la cadena para guardar los datos, con estado ultimo para los datos mas recientes
        
    }
    
}

echo $informe;

$update = "UPDATE dato_sensor SET estado ='pasado'";
q($update);
//Los datos antiguos le colocamos estado pasado, solo para llevar un historial

$insert = "INSERT INTO dato_sensor(sensor_id, datetime,    valor,    validez, estado) 
                             VALUES ".  substr($insert_values, 0, -1);
q($insert);        
//Guardamos los datos mas recientes