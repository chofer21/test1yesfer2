<?php
function dijkstra($graph_array, $source, $target) {
    $vertices = array();
    $neighbours = array();
    foreach ($graph_array as $edge) {
        array_push($vertices, $edge[0], $edge[1]);
        $neighbours[$edge[0]][] = array("end" => $edge[1], "cost" => $edge[2]);
    }
    $vertices = array_unique($vertices);
 
    foreach ($vertices as $vertex) {
        $dist[$vertex] = INF;
        $previous[$vertex] = NULL;
    }
 
    $dist[$source] = 0;
    $Q = $vertices;
    while (count($Q) > 0) {
 
        // TODO - Find faster way to get minimum
        $min = INF;
        foreach ($Q as $vertex){
            if ($dist[$vertex] < $min) {
                $min = $dist[$vertex];
                $u = $vertex;
            }
        }
 
        $Q = array_diff($Q, array($u));
        if ($dist[$u] == INF or $u == $target) {
            break;
        }
 
        if (isset($neighbours[$u])) {
            foreach ($neighbours[$u] as $arr) {
                $alt = $dist[$u] + $arr["cost"];
                if ($alt < $dist[$arr["end"]]) {
                    $dist[$arr["end"]] = $alt;
                    $previous[$arr["end"]] = $u;
                }
            }
        }
    }
    $path = array();
    $u = $target;
    while (isset($previous[$u])) {
        array_unshift($path, $u);
        $u = $previous[$u];
    }
    array_unshift($path, $u);
    return $path;
}
 
$graph_array = array(
array('a','b', 3),     
array('a','f', 1),     
array('a','t', 2),    
array('b','c', 3),    
array('c','d', 1),    
array('c','h', 1),    
array('d','e', 1),    
array('e','f', 1),    
array('f','g', 3),    
array('g','h', 3),    
array('g','j', 2),    
array('g','l', 1),    
array('h','i', 2),    
array('i','j', 3),    
array('j','k', 1),    
array('k','l', 2),    
array('k','o', 5),    
array('l','m', 2),    
array('m','n', 2),    
array('m','r', 2),    
array('n','o', 2),    
array('n','p', 5),    
array('p','q', 1),    
array('q','r', 2),    
array('q','t', 2),    
array('r','s', 2),    
array('s','t', 2)  

               );
         






$path = dijkstra($graph_array, "e", "b");
 
echo "path is: ".implode(", ", $path)."\n";

function calculo_ruta(){
    
    
    
    
}

?>
