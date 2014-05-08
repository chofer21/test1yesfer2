<style>
    .matrix th{
	background-color: #28a4c9
    }
    .matrix td{
	height: 25px;
	width: 25px;
	text-align: center;
	vertical-align: middle
    }
    .matrix{
	border-collapse:collapse
    }
    .diagonal{
	background-color: #777
    }
</style>
<?php
include ('../configuracion/conexion.php');

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

foreach ($con_caminos as $cc) {
    $pos_inicio = array_search ( $cc["inicio"] , $nodos_array );
    $pos_fin = array_search ( $cc["fin"] , $nodos_array );
    
    $distancias[$pos_inicio][$pos_fin] = $cc["distancia"];
    $distancias[$pos_fin][$pos_inicio] = $cc["distancia"];
    
}

echo "<hr>";
echo "<h3>Distancias</h3>";
echo "<table  class='matrix' border='1'>";
    for($i=0; $i < $num_nodos; $i++){
	if($i==0){
	    echo "<tr><td class='diagonal'>X</td>"; for($j=0; $j < $num_nodos; $j++){ echo "<th>$j</th>"; } echo "</tr>";
	}
	echo "<tr>";
	for($j=0; $j < $num_nodos; $j++){
	    if($j==0){echo "<th>$i</th>";}
	    $cl = "";
	    if($i==$j){ $cl = "class='diagonal'";}
	    echo "<td $cl >";
		echo $distancias[$i][$j];
	    echo "</td>";
	}
	
	echo "</tr>";
	
    }
echo "</table>";
echo "<hr/>";
        

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


echo "<h3>intermedios</h3>";

echo "<table class='matrix' border='1'>";
    for($i=0; $i < $num_nodos; $i++){
	if($i==0){
	    echo "<tr><td class='diagonal'>X</td>"; for($j=0; $j < $num_nodos; $j++){ echo "<th>$j</th>"; } echo "</tr>";
	}
	echo "<tr>";
	for($j=0; $j < $num_nodos; $j++){
	    if($j==0){echo "<th>$i</th>";}
	    $cl = "";
	    if($i==$j){ $cl = "class='diagonal'";}
	    echo "<td $cl >";
		echo $intermedios[$i][$j];
	    echo "</td>";
	}
	
	echo "</tr>";
	
    }
echo "</table>";


for($i=0; $i < $num_nodos; $i++){
    for($j=0; $j < $num_nodos; $j++){
        
        if($intermedios[$i][$j] == "-" && $i!=$j){
            $intermedios[$i][$j] = $j;
        }
            
    }
}

echo "<hr>";
echo "<table><tr><td>";

echo "<h3>Caminos</h3>";

echo "<table  class='matrix' border='1'>";
    for($i=0; $i < $num_nodos; $i++){
	if($i==0){
	    echo "<tr><td class='diagonal'>X</td>"; for($j=0; $j < $num_nodos; $j++){ echo "<th>$j</th>"; } echo "</tr>";
	}
	echo "<tr>";
	for($j=0; $j < $num_nodos; $j++){
	    if($j==0){echo "<th>$i</th>";}
	    $cl = "";
	    if($i==$j){ $cl = "class='diagonal'";}
	    echo "<td $cl >";
		echo $camino[$i][$j];
	    echo "</td>";
	}
	
	echo "</tr>";
	
    }
echo "</table>";

echo "</td><td>";

echo "<h3>intermedios</h3>";

echo "<table class='matrix' border='1'>";
    for($i=0; $i < $num_nodos; $i++){
	if($i==0){
	    echo "<tr><td class='diagonal'>X</td>"; for($j=0; $j < $num_nodos; $j++){ echo "<th>$j</th>"; } echo "</tr>";
	}
	echo "<tr>";
	for($j=0; $j < $num_nodos; $j++){
	    if($j==0){echo "<th>$i</th>";}
	    $cl = "";
	    if($i==$j){ $cl = "class='diagonal'";}
	    echo "<td $cl >";
		echo $intermedios[$i][$j];
	    echo "</td>";
	}
	
	echo "</tr>";
	
    }
echo "</table>";


echo "</td></tr></table>";
echo "<hr/>";





$camino_final = array();

//cambiar por s = 0
for($s = 1; $s < count($nodos_salidas) ; $s++ ){

    $pos_salida = array_search($nodos_salidas[$s], $nodos_array);
    echo " id_salida ".$nodos_salidas[$s]. " pos $pos_salida <br />";
        
for($i=0; $i < $num_nodos; $i++){
    
    if( $i  != $pos_salida){
        
          $pos_actual = $pos_salida;
          echo "camino ".$nodos_array[i]." $i <br/>";
          
          while($intermedios[$i][$pos_actual] != $pos_actual){
              
              $camino_final[$s][$i][] = $intermedios[$i][$pos_actual];
              echo $intermedios[$i][$pos_actual]. " - ";
              
              $pos_actual = $intermedios[$i][$pos_actual];
          }
              
              echo $intermedios[$i][$pos_actual]. " <br /> ";
              $camino_final[$s][$i][] = $intermedios[$i][$pos_actual];
              echo "---";
    }
}

}


for($s = 1; $s < count($nodos_salidas) ; $s++ ){
    for($i=0; $i < $num_nodos; $i++){
        echo "Camino desde $i ";
        echo "<br />";
        if($camino_final[$s][$i]){
            
            foreach($camino_final[$s][$i] as $cf){
                
                echo "$cf -";
                
            }
        
        }
        
        echo "<br />";
        echo "Fin ".$nodos_salidas[$s];
        echo "<hr />";
    }
}



?>
