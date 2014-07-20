<?php 
include ('../configuracion/conexion_1.php');

//consulta los nodos
$consulta_nodos = "SELECT id as nodo_id, descripcion, tipo
                   FROM nodo
                   WHERE id";
$con_nodos = q($consulta_nodos);

//consulta caminos y distancia
//cuando no es valido se multiplica por un valor grande para que no pase por ahi
$consulta_caminos = "SELECT c.inicio,
                            c.fin,
                            CASE
                               WHEN d.validez = 'no'
                                   THEN round(c.distancia * d.valor * 100)
                               ELSE c.distancia
                            END
                               AS distancia,
                            d.validez
                       FROM camino c, camino_sensor cs, dato_sensor d
                      WHERE     c.id = cs.camino_id
                            AND cs.sensor_id = d.sensor_id
                            AND d.estado = 'ultimo'";
$con_caminos = q($consulta_caminos);

$num_nodos = count($con_nodos);


$nodos_array = array();
$nodos_salidas = array();


foreach($con_nodos as $cn){
    $nodos_array[] = $cn["nodo_id"];
    
    if($cn["tipo"]=="salida"){  
        $nodos_salidas[] = $cn["nodo_id"];  
//array nodos salidas
    }
    
}


$distancias = array();
$intermedios = array();

for($i=0; $i < $num_nodos; $i++){
    for($j=0; $j < $num_nodos; $j++){
	
        $distancias[$i][$j] = 0;    //llenar las diagonales con valores vacios
        $intermedios[$i][$j] = "-"; 
	
    }
}



$pos_inicio = null;
$pos_fin = null;

//LLenar las matrices de intermedios y distancias
$todos_los_caminos = array();
foreach ($con_caminos as $cc) {
    
   $todos_los_caminos[] = $cc["inicio"]."-".$cc["fin"];
    
    $pos_inicio = array_search ( $cc["inicio"] , $nodos_array );
    $pos_fin = array_search ( $cc["fin"] , $nodos_array );
    
    
    $distancias[$pos_inicio][$pos_fin] = $cc["distancia"];
    $distancias[$pos_fin][$pos_inicio] = $cc["distancia"];
    
    $intermedios[$pos_inicio][$pos_fin] = $pos_inicio;
    $intermedios[$pos_fin][$pos_inicio] = $pos_fin;
    
}


$camino = $distancias;


//Floyd Warshall
for($k=0; $k < $num_nodos; $k++){
    //Recorre todos los nodos 

    for($i=0; $i < $num_nodos; $i++){ //Recorre la horizontal
        for($j=0; $j < $num_nodos; $j++){ //recorre la vertical
            
            if($i!=$j && $j != $k && $i != $k){ //Para que no cambien la diagonal
                
                    if($camino[$i][$k] > 0 &&  $camino[$k][$j]>0 ){
    			
                        $val =  $camino[$i][$j];
                        $interm = $intermedios[$i][$j];
                        
                        if($val >= ( $camino[$i][$k] + $camino[$k][$j]) || $val == 0  ){
                            //Compara si es menor el nuevo valor
                            
                            $val =  $camino[$i][$k] + $camino[$k][$j];
                            $interm = $k;
			    
                            //actualiza los intermedios
			    $intermedios[$i][$j] = $intermedios[$k][$j];
			    $camino[$i][$j] = $val;   
                            //Actualiza las distancias
                        }
                    }
                
            }
            
            
            
        }
    }
  
    
}


for($i=0; $i < $num_nodos; $i++){
    for($j=0; $j < $num_nodos; $j++){
        
        if($intermedios[$i][$j] == "-" && $i!=$j){ //Para que no cambie en la diagonal
            $intermedios[$i][$j] = $j;  //Los intermedios que quedaron vacions se llenan con el 
                                        //numeral de la vertical, son directos 
        }
            
    }
}



$resultado = array(); //Array que contiene todas las rutas cortas para cada nodo

for($s = 0; $s < count($nodos_salidas) ; $s++ ){

   $pos_salida = array_search($nodos_salidas[$s], $nodos_array);
   
for($i=0; $i < $num_nodos; $i++){
    
    $camino_actual = array();
    
    $linea_inicio = $i;
    
    if( $linea_inicio  != $pos_salida){

            $reverse = true;
    
	    
                $pos_actual = $pos_salida;
                
                $camino_actual[] = $pos_salida; //Agrega la posicion del final al camino
                
                
                $resultado[ $i ][ $pos_salida ]["distancia"] = $camino[$pos_salida][$linea_inicio];//Agrega distancia actual al array que contiene todos los datos
          
          
	    while($intermedios[$linea_inicio][$pos_actual] != $pos_actual && $intermedios[$linea_inicio][$pos_actual] != $linea_inicio){

		$camino_actual[] = $intermedios[$linea_inicio][$pos_actual]; //Agrega los nodos por donde pasa el camino
		$pos_actual = $intermedios[$linea_inicio][$pos_actual];  

	    }
	  
            $camino_actual[] = $linea_inicio; //Agrega la posicion de salida al camino
	    
            if($reverse){ //Como a veces se intercambia el final con el inicio no es necesario hacer el reverse al array
              $camino_actual = array_reverse($camino_actual);
            }
            
	    
              $camino_actual_array = array();
              for($w = 0; $w < ( count($camino_actual) - 1); $w++){
                  //Agregar los caminos que se deben encender si la ruta es 101-102-103 , agrega caminos 101-102 y 102-103
                  $camino_actual_array[] = $nodos_array[ $camino_actual[$w] ]."-".$nodos_array[ $camino_actual[ $w + 1] ];
              }
              
              
	        $resultado[ $i ][ $pos_salida ]["ruta"] = $camino_actual_array;

              
    }

}

}


$rutas_cortas = array();
$fin = array();

// Escoger la salida mas corta para cada nodo comparando las rutas hacia todas las salidas posibles
foreach($resultado as $n =>$nodo_r){
    
    if(  !in_array($nodos_array[$n], $nodos_salidas) ){
        
        $menor = 999999999999999999999;
        $ruta_real = array();
	
        foreach ($nodo_r as $sal_nodo){
	    
            if($sal_nodo["distancia"]< $menor){
                 $menor = $sal_nodo["distancia"];
                 $ruta_real =  $sal_nodo["ruta"];
            }
	    
        }
        
        $rutas_cortas[$n]["distancia"] = $menor;
        $rutas_cortas[$n]["ruta"] = $ruta_real;
        
        foreach($ruta_real as $rr){
            if( !in_array( substr ($rr, strpos($rr, "-") + 1  )."-".substr( $rr, 0, strpos($rr, "-") ) , $fin) ){
                $fin[] = $rr;
            }
        }
	
    }    
    
}

asort($fin);
$fin = array_unique($fin);


$caminos_faltantes = $todos_los_caminos;

foreach($fin as $f){
    $reves =  substr ($f, strpos($f, "-") + 1  )."-".substr( $f, 0, strpos($f, "-") ) ;
    
    if(  in_array($reves, $caminos_faltantes)  ){
        unset( $caminos_faltantes[ array_search($reves, $caminos_faltantes) ]  ); 
        
    }else if(in_array($f, $caminos_faltantes)){
        unset( $caminos_faltantes[ array_search($f, $caminos_faltantes) ]  ); 
    }
    
}



foreach($caminos_faltantes as $cf){
     
    $ini_n = substr( $cf, 0, strpos($cf, "-") ) ;
    $fin_n = substr ($cf, strpos($cf, "-") + 1  );
    $pos_ini = array_search($ini_n, $nodos_array);
    $pos_fin = array_search($fin_n, $nodos_array);
    
    $dist_ini = $rutas_cortas[ $pos_ini ]["distancia"];
    $dist_fin = $rutas_cortas[ $pos_fin ]["distancia"];
    

    if($dist_ini > $dist_fin ){
        $fin[] = "$ini_n-$fin_n"."";
    }else if($dist_ini < $dist_fin){
        $fin[] = "$fin_n-$ini_n"."";
    }else if( $dist_ini == $dist_fin ){ // valido los dos
        $fin[] = "$ini_n-$fin_n"."";
        $fin[] = "$fin_n-$ini_n"."";        
    }    
}


$where_leds = "";
foreach($fin as  $cam){
    $cam_actual = explode("-", $cam);
    $c_ini = $cam_actual[0];
    $c_fin = $cam_actual[1];
    
    $where_leds .= " (c.inicio='$c_ini' AND c.fin='$c_fin' AND cl.sentido='normal') OR "
		 . " (c.inicio='$c_fin' AND c.fin='$c_ini' AND cl.sentido='invertido') OR"; 
}

$consulta_leds = "SELECT l.id, l.mux, l.posicion
		    FROM camino c, camino_led cl, led l
		  WHERE	    c.id = cl.camino_id AND cl.led_id = l.id
			AND ( ".substr($where_leds,0,-2)." )
		 ORDER BY  l.posicion ";
$con_leds = q($consulta_leds);

$datos_leds = "";

$mux_data = array();

$todos_los_mux = q( "select distinct mux from led" );
//Todos los mux y puertos se inician con codigo 0
foreach( $todos_los_mux as $tm){
    $mux_data[$tm["mux"]]["A"][1] = 0; 
    $mux_data[$tm["mux"]]["B"][1] = 0; 
}

foreach($con_leds as $cl){
    $puerto = "A";
    $posicion = $cl["posicion"];
    if( $cl["posicion"] > 8 ){
        $puerto = "B";
        $posicion = $posicion - 8 ; 
    }
    
    $mux_data[ $cl["mux"] ][ $puerto ][$posicion] = $posicion;
    
}

$cadena = "";

foreach ( $mux_data as $km=>$puertos){ //Recorrer cada mux
 
    foreach($puertos as $kp=>$pt){ //Recorrer cada puerto
        
        $cadena .= "op2_mled=$km"
                  ."_puerto=$kp";
        //mled multiplexor del led
        $bin_actual = "";
        for($x = 1 ; $x<= 8; $x++){ //Crear binario
        
            if($pt[$x] == $x ){
                 $bin_actual .= "1";
            }else{
                $bin_actual .= "0";
            }
            
        }
        $dec_actual = bindec($bin_actual); //Convierte a decimal 
        $cadena .=  "_cod=$dec_actual"; //codigo
        $cadena .=  "_YYY_";
        
    }
    

}
echo "".substr($cadena, 0 , -5); //Cadena de datos

