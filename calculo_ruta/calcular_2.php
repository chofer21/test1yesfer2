<style>
    .matrix td{
        height: 25px;
        width: 25px;
        text-align: center;
        vertical-align: middle
    }
    .matrix th{
        height: 25px;
        width: 25px;
        text-align: center;
        vertical-align: middle;
        background-color: #39b3d7
    }
    .matrix{
        border-collapse: collapse
    }
    .diagonal{
        background-color: #808080
    }
</style>

<?php
include ('../configuracion/conexion_1.php');

$consulta_nodos = "SELECT id as nodo_id, descripcion, tipo
                   FROM nodo
                   WHERE id";
$con_nodos = q($consulta_nodos);

$consulta_caminos = "SELECT inicio,fin,distancia
                     FROM camino";
$con_caminos = q($consulta_caminos);

$num_nodos = count($con_nodos);


$nodos_array = array();
$nodos_salidas = array();

foreach($con_nodos as $cn){
    $nodos_array[] = $cn["nodo_id"];
    if($cn["tipo"]=="salida"){
        $nodos_salidas[] = $cn["nodo_id"];
    }
    
}

echo "<pre>";print_r($nodos_array); echo "</pre>";
echo "<pre>";print_r($nodos_salidas); echo "</pre>";

$distancias = array();
$intermedios = array();

for($i=0; $i < $num_nodos; $i++){
    for($j=0; $j < $num_nodos; $j++){
        $distancias[$i][$j] = 0;
        $intermedios[$i][$j] = "-";
        
    }
}

for($i=0; $i < $num_nodos; $i++){
    echo $nodos_array[$i]." p$i <br/>";
}

$pos_inicio = null;
$pos_fin = null;


$todos_los_caminos = array();
foreach ($con_caminos as $cc) {
    
    $todos_los_caminos_distancias[$cc["inicio"]."-".$cc["fin"]] = $cc["distancia"];
    $todos_los_caminos[] = $cc["inicio"]."-".$cc["fin"];
    
    $pos_inicio = array_search ( $cc["inicio"] , $nodos_array );
    $pos_fin = array_search ( $cc["fin"] , $nodos_array );
    
    
    
    $distancias[$pos_inicio][$pos_fin] = $cc["distancia"];
    $distancias[$pos_fin][$pos_inicio] = $cc["distancia"];
    
}
        
echo "<h3>Distancias</h3>";

echo "<table class='matrix' border='1'>";
for($i=0; $i < $num_nodos; $i++){
            if($i==0){
                echo "<tr><td class='diagonal'>X</td>";
                    for($j=0; $j < $num_nodos; $j++){
                        echo "<th>".$nodos_array[$j]."</th>";
                    }
                echo "</tr>";
            }
    for($j=0; $j < $num_nodos; $j++){
        if($j==0){
            echo "<th>".$nodos_array[$i]."</th>";
        }
        $class_d = "";
        if($i==$j){ $class_d = " class='diagonal'"; }
        echo "<td $class_d>";
            echo $distancias[$i][$j];
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";




    

$camino = $distancias;

for($k=0; $k < $num_nodos; $k++){

    for($i=0; $i < $num_nodos; $i++){
        for($j=0; $j < $num_nodos; $j++){
            
            if($i!=$j){
                
                    if($camino[$i][$k] > 0 &&  $camino[$k][$j]>0 ){
                     
                        $val =  $camino[$i][$j];
                        $interm = $intermedios[$i][$j];
                        
                        if($val > ( $camino[$i][$k] + $camino[$k][$j]) || $val == 0  ){
                            $val =  $camino[$i][$k] + $camino[$k][$j];
                            $interm = $k;
                        }
                        
                        $intermedios[$i][$j] = $interm;
                        $camino[$i][$j] = $val;   
                        
                    }
                
            }
            
        }
    }
}






for($i=0; $i < $num_nodos; $i++){
    for($j=0; $j < $num_nodos; $j++){
        
        if($intermedios[$i][$j] == "-" && $i!=$j){
            $intermedios[$i][$j] = $j;
        }
            
    }
}



echo "<table>";
echo "<tr> <td>";


echo "<h3>Caminos</h3>";

echo "<table class='matrix' border='1'>";
for($i=0; $i < $num_nodos; $i++){
            if($i==0){
                echo "<tr><td class='diagonal'>X</td>";
                    for($j=0; $j < $num_nodos; $j++){
                        echo "<th>".$nodos_array[$j]."</th>";
                    }
                echo "</tr>";
            }
    for($j=0; $j < $num_nodos; $j++){
        if($j==0){
            echo "<th>".$nodos_array[$i]."</th>";
        }
        $class_d = "";
        if($i==$j){ $class_d = " class='diagonal'"; }
        echo "<td $class_d>";
            echo $camino[$i][$j];
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";

echo "</td><td>";


echo "<h3>Intermedios</h3>";

echo "<table class='matrix' border='1'>";
for($i=0; $i < $num_nodos; $i++){
            if($i==0){
                echo "<tr><td class='diagonal'>X</td>";
                    for($j=0; $j < $num_nodos; $j++){
                        echo "<th>".$nodos_array[$j]."</th>";
                    }
                echo "</tr>";
            }
    for($j=0; $j < $num_nodos; $j++){
        if($j==0){
            echo "<th>".$nodos_array[$i]."</th>";
        }
        $class_d = "";
        if($i==$j){ $class_d = " class='diagonal'"; }
        echo "<td $class_d>";
            echo $nodos_array[ $intermedios[$i][$j] ];
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";


echo "</td></tr>";

echo "</table>";

$resultado = array();

$caminos_led = array();

//cambiar por s = 0
for($s = 0; $s < count($nodos_salidas) ; $s++ ){

    $pos_salida = array_search($nodos_salidas[$s], $nodos_array);
    echo "<h4> Salida ".$nodos_salidas[$s]."</h4>";
        
for($i=0; $i < $num_nodos; $i++){
    $camino_actual = array();
    
    $linea_actual = $i;
    
    if( $linea_actual  != $pos_salida){

            $reverse = true;
        
            if($linea_actual>$pos_salida){
                
              $pos_actual = $linea_actual;
              $linea_actual = $pos_salida;
              
                echo "camino ".$nodos_array[$pos_actual]." dist total: ".$camino[$pos_actual][$linea_actual]." <br/>";
                $camino_actual[] = $pos_actual;
              
               echo $nodos_array[$pos_actual]." - ";
               
               $reverse = false;
               
               
               $resultado[ $i ][ $linea_actual ]["distancia"] = $camino[$pos_actual][$linea_actual];
               
               
            }else{
               $pos_actual = $pos_salida;
                echo $nodos_salidas[$pos_actual]." - ";
                
                echo "camino ".$nodos_array[$linea_actual]." dist total: ".$camino[$pos_salida][$linea_actual]." <br/>";
                $camino_actual[] = $pos_salida;
                
                
                $resultado[ $i ][ $pos_salida ]["distancia"] = $camino[$pos_salida][$linea_actual];
               
                
            }
          
          
          while($intermedios[$linea_actual][$pos_actual] != $pos_actual){
              
              echo $nodos_array[  $intermedios[$linea_actual][$pos_actual]  ]. " -* ";
              
              $camino_actual[] = $intermedios[$linea_actual][$pos_actual];
              
              $pos_actual = $intermedios[$linea_actual][$pos_actual];
          }
         
          
          
              $camino_actual[] = $linea_actual;
              echo $nodos_array[ $linea_actual ]. " <br /> ";
    
            if($reverse){
              $camino_actual = array_reverse($camino_actual);
            }
            
            
              foreach($camino_actual as $ca){
                  echo $nodos_array[$ca]. "  - ";
              }
             
              
              
              
              
              
              
              echo " <br /> ";
              $ca = $camino_actual;
              $camino_actual_array = array();
              for($w = 0; $w < ( count($ca) - 1); $w++){
                  echo $nodos_array[ $ca[$w] ]."-".$nodos_array[ $ca[ $w + 1] ]." __ ";
                  
                  $caminos_led[] = $nodos_array[ $ca[$w] ]."-".$nodos_array[ $ca[ $w + 1] ];
                  $camino_actual_array[] = $nodos_array[ $ca[$w] ]."-".$nodos_array[ $ca[ $w + 1] ];
              }
              
              
               if($linea_actual>$pos_salida){
                    $resultado[ $i ][ $linea_actual ]["ruta"] = $camino_actual_array;
                }else{
                    
                    $resultado[ $i ][ $pos_salida ]["ruta"] = $camino_actual_array;
                }
              
              
              echo "<hr />";
              
    }

    
}

}

echo "<hr />";

asort($caminos_led);
$caminos_led = array_unique($caminos_led);

foreach($caminos_led as $cl){
    echo $cl."<br>";
}

echo "<hr />";


function salida_mayor(){
    
}



echo "<pre>";
print_r($resultado);
echo "</pre>";

$rutas_cortas = array();
$fin = array();

foreach($resultado as $n =>$nodo_r){
    if(  !in_array($nodos_array[$n], $nodos_salidas) ){
        
    
    echo "<br>";
        $menor = 999999999;
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

echo "<h2>wee</h2>";
echo "<pre>";
print_r($todos_los_caminos);
echo "</pre>";

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
    echo "$cf ?? ";
    
    $ini_n = substr( $cf, 0, strpos($cf, "-") ) ;
    $fin_n = substr ($cf, strpos($cf, "-") + 1  );
    $pos_ini = array_search($ini_n, $nodos_array);
    $pos_fin = array_search($fin_n, $nodos_array);
    
    $dist_ini = $rutas_cortas[ $pos_ini ]["distancia"];
    $dist_fin = $rutas_cortas[ $pos_fin ]["distancia"];
    
    //echo " di $dist_ini df $dist_fin , ";
    if($dist_ini > $dist_fin ){
        $fin[] = "$ini_n-$fin_n"."X";;
        echo "$ini_n -- $fin_n"."X";;
    }else if($dist_ini < $dist_fin){
        $fin[] = "$fin_n-$ini_n"."X";;
        echo "$fin_n -- $ini_n"."X";;
    }else if( $dist_ini == $dist_fin ){
        $fin[] = "$ini_n-$fin_n"."X";
        $fin[] = "$fin_n-$ini_n"."X";
        echo "$ini_n -- $fin_n >>>> $fin_n -- $ini_n";
        
    }
    
    echo "<br>";
    
}

echo "<pre>";
print_r( $rutas_cortas );
echo "</pre>";


echo "<h2>FIIIIN</h2>";
echo "<pre>";
print_r($fin);
echo "</pre>";


?>
