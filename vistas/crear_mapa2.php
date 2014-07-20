<?php
include ('../configuracion/conexion_1.php');

$con_tabla = q("select id,descripcion,pos_x,pos_y from nodo");
$tab = array();
foreach($con_tabla as $ct){
    $tab[ $ct["pos_x"] ][ $ct["pos_y"] ]["id"] = $ct["id"];
    $tab[ $ct["pos_x"] ][ $ct["pos_y"] ]["descripcion"] = $ct["descripcion"];
}


$color_nodo = "rgb(255, 0, 0)";
$color_camino = "cyan";
$color_salida = "green";


echo "<table border='1' id='table1'>";
for($ti=1;$ti<=20;$ti++){
    echo "<tr>";
        for($di=1;$di<=20;$di++){
            $attrs = "";
            if($tab[$di][$ti]){
                $attrs = " nodo = '".  $tab[$di][$ti]["descripcion"]."' ";
                $attrs .=" nodo_id = '".$tab[$di][$ti]["id"]."' ";
                $attrs .=" style='background-color:".$color_nodo."' ";
            }
            echo "<td $attrs col='$di' row='$ti' title='".$tab[$di][$ti]["id"]."'>"; 
            echo"</td>";
        }
    echo "</tr>";
}
echo "</table>";

echo "<div style='position:relative;top:-320px;left:500px'>";
    echo "<div id='div_cargar_sensores_asignados'>SSSS";
    echo "</div>";

    echo "<div id='div_cargar_sensores'>XXXX";
    echo "</div>";

    
$consulta_caminos = "SELECT id, inicio, fin 
	             FROM camino";
$con_caminos = q($consulta_caminos);

    $script_array_caminos = "";
foreach ($con_caminos as $ar){
    $script_array_caminos .= "array_caminos.push('".$ar["inicio"]."-".$ar["fin"]."'); \n";
}

?>

<button id='crear_nodos'>
     Crear nodos
</button>

<button id='unir_puntos'>
    Unir puntos
</button>


<button id='asignar_sensores'>
    Asignar sensores
</button>


</div>
<style>
    #ingresar_distancia{
        position:fixed;
        top:100px;
        left:100px
        
    }
    
    #table1{
        border-collapse: collapse;
        /*width: 100%;*/
        font-size: 0.8em;
        
    }
    #table1 td{
        height: 17px;
        width: 21px;
    }
</style>

<script src="../js/jquery210.js" ></script>
<script>
var array_caminos = [];
<?php echo $script_array_caminos; ?>
cl(array_caminos);
//acciones botones
var crear = false;    
var unir = false;  
var asignar = false;   



var cont_nodo = 1;
var ini_col = "";
var ini_row = "";
var fin_col = "";
var fin_row = "";

$("td").click(function(){
   var col = $(this).attr("col");
   var row = $(this).attr("row");
      
if(crear){
   if( $(this).css("background-color") === "<?php echo $color_nodo; ?>" ){
       $(this).css("background-color","white");
       eliminar_nodo( $(this).attr("nodo_id") );
       
   }else{
       var $t = $(this);
       $t.css("background-color","<?php echo $color_nodo; ?>");
       $t.attr("nodo","nodo" + cont_nodo);
       crear_nodo("nodo" + cont_nodo, $t.attr("col"), $t.attr("row") );
       
       cont_nodo++;
   }

}else
if(unir){
    
if( $(this).css("background-color") === "<?php echo $color_nodo; ?>" ){  
    if(ini_col==''){
        ini_col = col;
        ini_row = row;
    }else 
    if(ini_row != row || ini_col != col ){ //validar que no dio click en el mismo punto
	
	
	
	fin_col = col;
        fin_row = row;
   
        cl( ini_col + " " + fin_col);
        cl( ini_row + " " + fin_row);
	
	
	var nodo_ini = $("td[col="+ ini_col +"][row="+ini_row+"]").attr("nodo_id");  
        var nodo_fin = $("td[col="+ fin_col +"][row="+fin_row+"]").attr("nodo_id");  
	
	var ini,fin = 0;
	if(ini_col===fin_col){
		    ini = ini_row;
		    fin = fin_row;
	}else if(ini_row == fin_row){
                var ini = ini_col;
                var fin = fin_col;
	}

	if(parseInt(ini)>parseInt(fin)){
	    var aux = fin;
		fin = ini;
		ini = aux;
	}    
	ini = parseInt(ini) + 1;
	fin = parseInt(fin) - 1;
	
	    
	if( array_caminos.indexOf(nodo_ini + "-" + nodo_fin) != '-1' || array_caminos.indexOf(nodo_fin + "-" + nodo_ini) != '-1' ){
	    alert("Camino ya esta creado");
	}else{ 
	    var distancia = ingresar_distancia(fin-ini);
	    var camino_id = unir_nodos(nodo_ini,nodo_fin,distancia);
            cl(camino_id);
	    array_caminos.push(nodo_ini+"-"+nodo_fin);
	    pintar_camino(camino_id, ini_col,fin_col, ini_row,fin_row);
	    
	}
	
	
        
        
        ini_col = '';
        ini_row = '';
        fin_col = '';
        fin_row = '';
    }else{
	    cl("Click en el mismo punto no es valido!!");
    }
    
    
}
   
   
}else 
if(asignar){
    var camino_id = $(this).attr("camino_id");
    
    if(camino_id){   cargar_sensores(camino_id);  }
    
}


});




function pintar_camino(camino_id, ini_col,fin_col, ini_row,fin_row){

var ini_c = +ini_col; 
var fin_c = +fin_col;
   if(fin_c < ini_c ){
       ini_c = fin_col;
       fin_c = ini_col;
   }
if(ini_c !== fin_c){   
    ini_c = +ini_c + 1;   
    fin_c = +fin_c - 1;   
}
var ini_r = +ini_row; 
var fin_r = +fin_row;
   if(fin_r < ini_r ){
       ini_r = fin_row;
       fin_r = ini_row;
   }
if(ini_r !== fin_r){   
    ini_r = +ini_r + 1;   
    fin_r = +fin_r - 1;   
}    
   
cl("ini_c " + ini_c + " fin_c" + fin_c);
cl("ini_r " + ini_r + " fin_r" + fin_r);


if( ini_c == fin_c ){    
    for(var i = +ini_r ;  i <= +fin_r; i++){
	$("td[col="+ ini_c +"][row="+i+"]").css("background-color","<?php echo $color_camino; ?>"); 
        $("td[col="+ ini_c +"][row="+i+"]").attr("camino_id", camino_id );
    }
}else if(ini_r == fin_r){   
    for(var i = +ini_c ;  i <= +fin_c; i++){
	$("td[col="+ i +"][row="+ ini_r +"]").css("background-color","<?php echo $color_camino; ?>"); 
	$("td[col="+ i +"][row="+ ini_r +"]").attr("camino_id", camino_id );
    }
}else{
 
 //DIAGONAL

}    
    





}

$("#crear_nodos").click(function(){
   if(crear){
        crear = false;
        $(this).text("Crear nodos");
   }else{
	cancelar_otras_acciones('crear');
        crear = true;
        $(this).text("No crear");
   }
    
});


$("#unir_puntos").click(function(){
   if(unir){
        unir = false;
        $(this).text("Unir");
	
   }else{
	cancelar_otras_acciones('unir');
	
        unir = true;
        $(this).text("No Unir");
   }
    
});

$("#asignar_sensores").click(function(){
    if(asignar){
         asignar = false;
         $(this).text("asignar");
         $("#div_cargar_sensores").html("");
    }else{
	 cancelar_otras_acciones('asignar');
         asignar = true;
         $(this).text("No asignar");
         
    } 
});

$(document).on("click",".b_asignar_sensor",function(){
    var camino_id = $(this).attr("camino_id");
    var sensor_id = $(this).attr("sensor_id");
    cl("camino " + camino_id);
    if(camino_id && sensor_id){
      asignar_sensores(camino_id,sensor_id);
    }
    
});

function cancelar_otras_acciones(accion){
    if(accion=='crear'){ 
	unir = false;	  $("#unir_puntos").text("Unir"); 
			    ini_col = '';
			    ini_row = '';
			    fin_col = '';
			    fin_row = '';
	asignar = false;  $("#asignar_sensores").text("asignar");
    }else
    if(accion=='unir'){
	crear = false;    $("#crear_nodos").text("Crear nodos");
	asignar = false;  $("#asignar_sensores").text("asignar");
    }else
    if(accion=='asignar'){
	crear = false;    $("#crear_nodos").text("Crear nodos");
	unir = false;	  $("#unir_puntos").text("Unir");
			    ini_col = '';
			    ini_row = '';
			    fin_col = '';
			    fin_row = '';
    }

}

function asignar_sensores(camino_id,sensor_id){
        $.post( "../controlador/control_funciones.php", 
        { opcion: "asignar_sensor",
          camino_id: camino_id,
          sensor_id: sensor_id
        },
        function(data){
                cl(data);
        }
    );
}

function cargar_sensores(camino_id){
    $.ajax({
        dataType: "json",
        url: "../controlador/control_funciones.php",
        data: { opcion: "cargar_sensores",
                camino_id: camino_id
              },
        success: function(data){
            cl(data);
            var table = "<table>";
                table += "<thead>\n\
                             <th>X</th>   \n\
                             <th>Descripcion</th>   \n\
                             <th>Tipo</th>   \n\
                             <th>Mux</th>   \n\
                             <th>Posicion</th>   \n\
                          </thead>";
            var table_asignados = table;
            var mostrar_asignados = false;                         
            $.each(data, function(index, tt) {
                if(tt["asignado"]==='no'){
                    table += "<tr>";
                        table += "<td>";
                            table +="<button class='b_asignar_sensor' camino_id = '"+ camino_id +"' sensor_id='"+tt["id"]+"'>asignar</button>";                
                        table += "</td>";
                        table += "<td>";
                            table += tt["descripcion"];         
                        table += "</td>";
                        table += "<td>";
                            table += tt["tipo_sensor"];         
                        table += "</td>";
                        table += "<td>";
                            table += tt["mux"];         
                        table += "</td>";
                        table += "<td>";
                            table += tt["posicion"];         
                        table += "</td>";
                    table +="</tr>";
                }else if(tt["camino_asignado"]==camino_id){
                    mostrar_asignados = true;
                    table_asignados += "<tr>";
                        table_asignados += "<td>";
                            table_asignados +="<button class='b_retirar_sensor' camino_id = '"+ camino_id +"' sensor_id='"+tt["id"]+"'>retirar</button>";                
                        table_asignados += "</td>";
                        table_asignados += "<td>";
                            table_asignados += tt["descripcion"];         
                        table_asignados += "</td>";
                        table_asignados += "<td>";
                            table_asignados += tt["tipo_sensor"];         
                        table_asignados += "</td>";
                        table_asignados += "<td>";
                            table_asignados += tt["mux"];         
                        table_asignados += "</td>";
                        table_asignados += "<td>";
                            table_asignados += tt["posicion"];         
                        table_asignados += "</td>";
                    table_asignados +="</tr>";
                }
            });
                table +="</table>";
                table_asignados +="</table>";
            if(mostrar_asignados){    
                $("#div_cargar_sensores_asignados").html(table_asignados);
            }
            $("#div_cargar_sensores").html(table);
                
        }
      });
    
    
    
    
    
}

function crear_nodo(nodo,pos_x,pos_y){
    cl(nodo);
    $.post( "../controlador/control_funciones.php", 
        { opcion: "crear_nodo",
          descripcion: nodo,
          pos_x: pos_x,
          pos_y: pos_y,
          tipo:'normal'
        },
        function(data){
            $("td[nodo="+nodo+"]").attr("nodo_id",data);
                cl(data);
        }
    );
}

function eliminar_nodo(nodo_id){
    $.post( "../controlador/control_funciones.php", 
            { opcion: "eliminar_nodo",
              id_nodo: nodo_id 
            },
            function(data){
                cl(data);
            }
    );
         
         
    
};

function unir_nodos(nodo_ini,nodo_fin,distancia){
    cl(nodo_ini + " - " + nodo_fin + " - "  + distancia)
    
        $.ajax({
		type:"POST",
		url:"../controlador/control_funciones.php",
		data: {opcion: "unir_nodos",
                       descripcion: "union " + nodo_ini + "-" + nodo_fin,
                       distancia: distancia,
                       inicio: nodo_ini,
                       fin: nodo_fin
		   },
		    
		async:false,
		success:function(data){
		    retorno = data; 
		} 
	});

return retorno;

}


function ingresar_distancia(dis)
{
    var distancia=prompt("Digite distancia entre puntos",dis + 1);
    return distancia;
}

function cl(m){ console.log(m); }

window.onbeforeunload = function(){
  return 'No Cerrar';
};
</script>