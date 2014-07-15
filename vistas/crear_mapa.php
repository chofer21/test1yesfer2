<?php
//include ('../configuracion/conexion.php');
include ('../configuracion/conexion_1.php');

$con_tabla = q("select id,descripcion,pos_x,pos_y,tipo from nodo");

$tab = array();
foreach($con_tabla as $ct){
    $tab[ $ct["pos_x"] ][ $ct["pos_y"] ]["id"] = $ct["id"];
    $tab[ $ct["pos_x"] ][ $ct["pos_y"] ]["descripcion"] = $ct["descripcion"];
    $tab[ $ct["pos_x"] ][ $ct["pos_y"] ]["tipo"] = $ct["tipo"];
}


$color_nodo = "rgb(255, 0, 0)";
$color_camino = "cyan";
$color_salida = "green";

?>

<style>
    
    .sensor:before{
        
        content: "S"
        
    }
    
    .sensor{
        
        text-align: center;
        
    }
    
    .led:before{
        
        content: "L"
        
    }
    
    .led{
        
        text-align: center;
        
    }
    
    .ambos{
        
        text-align: center;
        
    }
    
    .ambos:before{
        
        content: "S.L"
        
    }
    
</style>

<br>


<div class="well">
    
    <h3 id="accion_actual" style="display: inline"></h3>  
    
    <button id='crear_nodos' class="btn btn-success">
         Crear / Eliminar Nodos
    </button>

    <button id='unir_puntos' class="btn btn-info">
        Unir Puntos
    </button>

    <button id='asignar_sensores' class="btn btn-danger">
        Asignar Sensores
    </button>     
    
    <button id='asignar_leds' class="btn btn-danger">
        Asignar Leds
    </button> 
    
    <button id='asignar_salida' class="btn btn-warning">
        Asignar Salida
    </button>
    
</div> 
    
<?php

echo "<table border='1' id='table1' align='center'>";
for($ti=1;$ti<=20;$ti++){
    echo "<tr>";
        for($di=1;$di<=20;$di++){
            $attrs = "";
            if($tab[$di][$ti]){
                $attrs = " nodo = '".  $tab[$di][$ti]["descripcion"]."' ";
                $attrs .=" nodo_id = '".$tab[$di][$ti]["id"]."' ";
                $attrs .=" style='background-color:".($tab[$di][$ti]["tipo"]=="normal"?$color_nodo:$color_salida)."' ";
                $attrs .=" id = '".$tab[$di][$ti]["id"]."' ";
            }
            echo "<td $attrs col='$di' row='$ti' title='".$tab[$di][$ti]["id"]."'>"; 
            echo"</td>";
        }
    echo "</tr>";
}
echo "</table>";

echo "<div style='position:relative;top:-320px;left:500px'>";
    /*echo "<div id='div_cargar_sensores_asignados'>SSSS";
    echo "</div>";

    echo "<div id='div_cargar_sensores'>XXXX";
    echo "</div>";*/

    
$consulta_caminos = "SELECT id, inicio, fin 
	             FROM camino";

$con_caminos = q($consulta_caminos);
$sensores_actuales = q("select * from camino_sensor");
$leds_actuales = q("select * from camino_led");


    $script_array_caminos = "";
    foreach ($con_caminos as $ar){
        $script_array_caminos .= "array_caminos.push('".$ar["inicio"]."-".$ar["fin"]."'); \n";
    }

    $script_array_caminos_actuales = "";
    foreach ($con_caminos as $arc){
       $script_array_caminos_actuales .= "caminos_actuales.push('".$arc["inicio"]."-".$arc["fin"]."-".$arc["id"]."'); \n";
    }
    
    $script_array_sensores_actuales = "";
    foreach ($sensores_actuales as $arc){
       $script_array_sensores_actuales .= "sensores_actuales.push('".$arc["id"]."-".$arc["camino_id"]."-".$arc["sensor_id"]."'); \n";
    }
    
    $script_array_leds_actuales = "";
    foreach ($leds_actuales as $arc){
       $script_array_leds_actuales .= "leds_actuales.push('".$arc["id"]."-".$arc["camino_id"]."-".$arc["led_id"]."'); \n";
    }

    
?>

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
        height: 25px;
        width: 30px;
    }
</style>

<script src="../js/jquery210.js" ></script>
<script src="../js/bootstrap/js/bootstrap.js" ></script>
<link rel="stylesheet" href="../js/bootstrap/css/bootstrap.css" >


<script>
    
var array_caminos = [];
<?php echo $script_array_caminos; ?>
//cl(array_caminos);

var caminos_actuales = [];

<?php echo $script_array_caminos_actuales ?>
    
var sensores_actuales = [];    

<?php echo $script_array_sensores_actuales ?>
//acciones botones

var leds_actuales = [];

<?php echo $script_array_leds_actuales ?>

var crear = false;    
var unir = false;  
var asignar = false;
var led = false;
var salida = false;

var cont_nodo = 1;
var ini_col = "";
var ini_row = "";
var fin_col = "";
var fin_row = "";

//llenar camionos guardados

for(var i=0 ; i<caminos_actuales.length;i++){
       
    camino_id = caminos_actuales[i].split("-")[2];
    ini_col = $("#"+caminos_actuales[i].split("-")[1]).attr("col");
    fin_col = $("#"+caminos_actuales[i].split("-")[0]).attr("col");
    ini_row = $("#"+caminos_actuales[i].split("-")[1]).attr("row");
    fin_row = $("#"+caminos_actuales[i].split("-")[0]).attr("row");
    
    pintar_camino(camino_id, ini_col,fin_col, ini_row,fin_row);
    
}

$("td").click(function(){
       
   var col = $(this).attr("col");
   var row = $(this).attr("row");
      
if(crear){
   if( $(this).css("background-color") === "<?php echo $color_nodo; ?>" || $(this).css("background-color") === "<?php echo $color_salida; ?>" ){
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
    
}else if(led){

    var camino_id = $(this).attr("camino_id");
    
    if(camino_id){   
    
    
        cargar_leds(camino_id);
    
    }

}else if(salida){
    
      tipo = 'normal';
      colorN = "<?php echo $color_nodo ?>";
      
      if( $(this).css("background-color") === "<?php echo $color_nodo; ?>"){
          tipo = 'salida';
            colorN = "<?php echo $color_salida ?>";
      }
          $this = $(this);
          nodo_id = $(this).attr("nodo_id");
          
          $.ajax({
		type:"POST",
		url:"../controlador/control_funciones.php",
		data: {opcion: "agregar_salida",
                       nodo_id: nodo_id,
                       tipo: tipo
		   },		    
		async:false,
		success:function(){
                    
                   $this.css("background-color", colorN);
                    
		} 
	});
          
      
    
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
        $(this).text("Crear / Eliminar Nodos");
        $("#unir_puntos").show();
        $("#asignar_sensores").show();
        $("#asignar_leds").show();
        $("#accion_actual").hide();
   }else{
	cancelar_otras_acciones('crear');
        crear = true;        
        $(this).text("Finalizar");
   }
    
});


$("#unir_puntos").click(function(){
   if(unir){
        unir = false;
        $(this).text("Unir Puntos");	
        $("#crear_nodos").show();
        $("#asignar_sensores").show();
        $("#asignar_leds").show();
        $("#asignar_salida").show();
        $("#accion_actual").hide();
   }else{
	cancelar_otras_acciones('unir');	
        unir = true;
        $(this).text("Finalizar");
   }
    
});

$("#asignar_leds").click(function(){
   
   if(led){
         led = false;
         $(this).text("Asignar Leds");
         $("#crear_nodos").show();
        $("#asignar_sensores").show();
         $("#unir_puntos").show();     
        $("#asignar_salida").show();    
         $("#accion_actual").hide();
    }else{
	 cancelar_otras_acciones('led');
         led = true;
         $(this).text("Finalizar");         
    } 
   
});

$("#asignar_sensores").click(function(){    
       
    if(asignar){
         asignar = false;
         $(this).text("Asignar Sensores");
         $("#crear_nodos").show();
        $("#asignar_leds").show();
         $("#unir_puntos").show();
        $("#asignar_salida").show();
         $("#accion_actual").hide();
    }else{
	 cancelar_otras_acciones('asignar');
         asignar = true;
         $(this).text("Finalizar");
         
    } 
});

$("#asignar_salida").click(function(){    
       
    if(salida){
         salida = false;
         $(this).text("Asignar Sensores");
         $("#crear_nodos").show();
        $("#asignar_leds").show();
         $("#unir_puntos").show();
        $("#asignar_salida").show();
        $("#asignar_sensores").show();
         $("#accion_actual").hide();
    }else{
	 cancelar_otras_acciones('salida');
         salida = true;
         $(this).text("Finalizar");
         
    } 
});

$(document).on("click",".b_asignar_sensor",function(){
    var camino_id = $(this).attr("camino_id");
    var sensor_id = $(this).attr("sensor_id");
    cl("camino " + camino_id);
    $this = $(this);
    if(camino_id && sensor_id){
      asignar_sensores(camino_id,sensor_id,$this);
    }    
});

$(document).on("click",".b_asignar_led",function(){
    
    var camino_id = $(this).attr("camino_id");
    var sensor_id = $(this).attr("led_id");
    var index = $(this).attr("index");
    var sentido = $("input:radio[name=sentido"+index+"]:checked").val();     
    cl("camino " + camino_id);
    $this = $(this);
    
    asignar_led(camino_id,sensor_id,sentido,$this);
            
});

$(document).on("click",".b_retirar_sensor",function(){
    
    var camino_id = $(this).attr("camino_id");
    var sensor_id = $(this).attr("sensor_id");
    $this = $(this);
    
    retirarSensor(camino_id,sensor_id,$this);
    
});

$(document).on("click",".b_retirar_led",function(){
    
    var camino_id = $(this).attr("camino_id");
    var sensor_id = $(this).attr("sensor_id");
    $this = $(this);
    
    retirarLed(camino_id,sensor_id,$this);
    
});

function cancelar_otras_acciones(accion){
    if(accion=='crear'){ 
        
	unir = false;	 
        //$("#unir_puntos").text("Unir"); 
        $("#unir_puntos").hide();
        ini_col = '';
        ini_row = '';
        fin_col = '';
        fin_row = '';
	asignar = false;  
        //$("#asignar_sensores").text("asignar");        
        $("#asignar_sensores").hide();
        $("#asignar_leds").hide();
        $("#asignar_salida").hide();
        
        $("#accion_actual").html("Accion: Creando / Eliminando Nodos&nbsp;&nbsp;&nbsp;");
        $("#accion_actual").show();
        
    }else if(accion=='unir'){
        
	crear = false; 
	asignar = false; 
        
        $("#crear_nodos").hide();
        $("#asignar_sensores").hide();
        $("#asignar_leds").hide();
        $("#accion_actual").html("Accion: Uniendo Puntos&nbsp;&nbsp;&nbsp;");
        $("#accion_actual").show();
        $("#asignar_salida").hide();
        
    }else if(accion=='asignar'){
	crear = false;    
        $("#crear_nodos").hide();
        $("#asignar_salida").hide();
        $("#asignar_leds").hide();
        
	unir = false;	 
        $("#accion_actual").html("Accion: Asignando Sensores&nbsp;&nbsp;");
        $("#accion_actual").show();
        $("#unir_puntos").hide();
			    ini_col = '';
			    ini_row = '';
			    fin_col = '';
			    fin_row = '';
    }else if(accion == 'led'){
        
        crear = false; 
        unir = false;	
        $("#crear_nodos").hide();
        $("#asignar_salida").hide();
        $("#asignar_sensores").hide();        
        $("#unir_puntos").hide();
        
	 
        $("#accion_actual").html("Accion: Asignando Leds&nbsp;&nbsp;");
        $("#accion_actual").show();
        ini_col = '';
        ini_row = '';
        fin_col = '';
        fin_row = '';
        
    }else if(accion == 'salida'){
        
        crear = false; 
        unir = false;	
        $("#crear_nodos").hide();
        $("#asignar_leds").hide();
        $("#asignar_sensores").hide();        
        $("#unir_puntos").hide();
        
	 
        $("#accion_actual").html("Accion: Asignando Salidas&nbsp;&nbsp;");
        $("#accion_actual").show();
        ini_col = '';
        ini_row = '';
        fin_col = '';
        fin_row = '';
        
    }

}

function asignar_sensores(camino_id,sensor_id,$this){
        $.post( "../controlador/control_funciones.php", 
        { opcion: "asignar_sensor",
          camino_id: camino_id,
          sensor_id: sensor_id
        },
        function(data){
                //$("td[camino_id="+camino_id+"]").html("S").css("text-align","center");
                //$("td[camino_id="+camino_id+"]").addClass("sensor").removeAttr("style");
                
                if($("td[camino_id="+camino_id+"]").hasClass("led")){
                    
                    $("td[camino_id="+camino_id+"]").addClass("ambos");
                    
                }else{
                    
                    $("td[camino_id="+camino_id+"]").addClass("sensor");
                    
                }               
                
                
                
                $this.parent().parent().fadeOut(1000,function(){
                    
                    $(this).hide();
                    
                })
                alert("El sensor fue agregado con exito!");
        }
    );
}

function asignar_led(camino_id,sensor_id,sentido,$this){


        $.post( "../controlador/control_funciones.php", 
        { opcion: "asignar_led",
          camino_id: camino_id,
          sensor_id: sensor_id,
          sentido: sentido
        },
        function(data){
            
                if($("td[camino_id="+camino_id+"]").hasClass("sensor")){
                    
                    $("td[camino_id="+camino_id+"]").addClass("ambos");
                    
                }else{
                    
                    $("td[camino_id="+camino_id+"]").addClass("led");
                    
                }
                $this.parent().parent().fadeOut(1000,function(){
                    
                    $(this).hide();
                    
                })
                alert("El Led fue agregado con exito!");
        }
    );
    
}

function retirarSensor(camino_id,sensor_id,$this){
    
    //alert(camino_id+" - "+sensor_id)
    $.ajax({
		type:"POST",
		url:"../controlador/control_funciones.php",
		data: {opcion: "retirar_sensor",
                       camino_id: camino_id,
                       sensor_id: sensor_id
		   },		    
		async:false,
		success:function(data){
		   // alert("lksjdbb")
                    $this.parent().parent().fadeOut(500,function(){
                        
                        //$("td[camino_id="+camino_id+"]").html("");
                        if($("td[camino_id="+camino_id+"]").hasClass("ambos")){
                            $("td[camino_id="+camino_id+"]").removeClass("ambos");
                            $("td[camino_id="+camino_id+"]").addClass("led");
                        }else{
                            $("td[camino_id="+camino_id+"]").removeClass("sensor");
                        }
                        
                        $(this).remove();
                        
                    })
                    //fadeout
                    
		} 
	});
    
}

function retirarLed(camino_id,sensor_id,$this){
    
    //alert(camino_id+" - "+sensor_id)
    $.ajax({
		type:"POST",
		url:"../controlador/control_funciones.php",
		data: {opcion: "retirar_led",
                       camino_id: camino_id,
                       sensor_id: sensor_id
		   },		    
		async:false,
		success:function(data){
		   // alert("lksjdbb")
                    $this.parent().parent().fadeOut(500,function(){
                        
                        //$("td[camino_id="+camino_id+"]").html("");
                        
                        if($("td[camino_id="+camino_id+"]").hasClass("ambos")){
                            $("td[camino_id="+camino_id+"]").removeClass("ambos");
                            $("td[camino_id="+camino_id+"]").addClass("sensor");
                        }else{
                            $("td[camino_id="+camino_id+"]").removeClass("led");
                        }
                        
                        $(this).remove();
                        
                    })
                    //fadeout
                    
		} 
	});
    
}

function cargar_leds(camino_id){    

    $.ajax({
        dataType: "json",
        url: "../controlador/control_funciones.php",
        data: { opcion: "cargar_leds",
                camino_id: camino_id
              },
        success: function(data){
            cl(data);
            var table = "";
            var table_asignados = table;
            var mostrar_asignados = false;    
                        
            $.each(data.datos, function(index, tt) {
                
                if(tt["asignado"]==='no'){
                    table += "<tr>";
                        table += "<td>";
                            table += tt["descripcion"];         
                        table += "</td>";
                        table += "<td>";
                            table += tt["mux"];         
                        table += "</td>";
                        table += "<td>";
                            table += tt["posicion"];         
                        table += "</td>";
                        table += "<td style='text-align:center'>";
                            table += data.datosCamino[0]['inicio']+" &rarr; "+data.datosCamino[0]['fin']+" <input type='radio' name='sentido"+index+"' value='normal'>";
                            table += "<br>";
                            table += data.datosCamino[0]['fin']+" &rarr; "+data.datosCamino[0]['inicio']+" <input type='radio' name='sentido"+index+"' value='invertido'>";
                        table += "</td>";
                        table += "<td style='text-align:center'>";
                            table +="<button class='b_asignar_led btn btn-success' index='"+index+"' camino_id = '"+ camino_id +"' led_id='"+tt["id"]+"'>asignar</button>";                
                        table += "</td>";
                    table +="</tr>";
                    
                }else if(tt["camino_asignado"]==camino_id){
                    
                    mostrar_asignados = true;
                    table_asignados += "<tr>";
                        table_asignados += "<td>";
                            table_asignados += tt["descripcion"];         
                        table_asignados += "</td>";
                        table_asignados += "<td>";
                            table_asignados += tt["mux"];         
                        table_asignados += "</td>";
                        table_asignados += "<td>";
                            table_asignados += tt["posicion"];         
                        table_asignados += "</td>";
                        table_asignados += "<td>";                           
                            table_asignados += tt["sentido"];
                        table_asignados += "</td>";
                        table_asignados += "<td style='text-align:center'>";
                            table_asignados +="<button class='b_retirar_led btn btn-success' camino_id = '"+ camino_id +"' sensor_id='"+tt["id"]+"'>retirar</button>";                
                        table_asignados += "</td>";
                    table_asignados +="</tr>";
                }
                
            });
            
            table +="</table>";
            table_asignados +="</table>";
                
            if(mostrar_asignados){    
                
                $("#tbody_leds_activos").html(table_asignados);
                
            }
            
            $("#tbody_leds").html(table);
                
            $("#div_leds").modal();    
                
        }
      });
  
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
            var table = "";
            var table_asignados = table;
            var mostrar_asignados = false;    
            
            $.each(data, function(index, tt) {
                
                if(tt["asignado"]==='no'){
                    table += "<tr>";
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
                        table += "<td style='text-align:center'>";
                            table +="<button class='b_asignar_sensor btn btn-success' camino_id = '"+ camino_id +"' sensor_id='"+tt["id"]+"'>asignar</button>";                
                        table += "</td>";
                    table +="</tr>";
                    
                }else if(tt["camino_asignado"]==camino_id){
                    
                    mostrar_asignados = true;
                    table_asignados += "<tr>";
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
                        table_asignados += "<td style='text-align:center'>";
                            table_asignados +="<button class='b_retirar_sensor btn btn-success' camino_id = '"+ camino_id +"' sensor_id='"+tt["id"]+"'>retirar</button>";                
                        table_asignados += "</td>";
                    table_asignados +="</tr>";
                }
                
            });
            
            table +="</table>";
            table_asignados +="</table>";
                
            if(mostrar_asignados){    
                
                $("#tbody_sensores_activos").html(table_asignados);
                
            }
            
            $("#tbody_sensores").html(table);
                
            $("#div_sensores").modal();    
                
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
            $("td[nodo="+nodo+"]").attr("nodo_id",data).attr("id",data);
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
                    
                   json = JSON.parse(data);
                   $.each(json, function(key,val){
                       
                      $("td[camino_id="+val.id+"]").html("");
                      $("td[camino_id="+val.id+"]").removeAttr("style").removeAttr("camino_id",data);                      
                      
                   });
                
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

for(var j=0; j < sensores_actuales.length; j++){
    
    separar = sensores_actuales[j].split("-");
        
    $("td[camino_id="+separar[1]+"]").addClass("sensor");
    
}

for(var j=0; j < leds_actuales.length; j++){
    
    separar = leds_actuales[j].split("-");
        
    $("td[camino_id="+separar[1]+"]").addClass("led");
    
}

$("td").each(function(){
    
    $this = $(this);    
    
    if( $(this).css("background-color") === "rgb(0, 255, 255)"){
        
        if($this.hasClass("sensor") && $this.hasClass("led")){
            
            $this.removeClass("sensor");
            $this.removeClass("led");
            $this.addClass("ambos");
            
        }
        
    }
    
});


</script>

<div class="modal fade" id="div_sensores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel"><b>SENSORES</b></h5>
      </div>
      <div class="modal-body">        
          
          <ul class="nav nav-tabs">
            <li class="active"><a href="#asignar" data-toggle="tab"><b>Asignar Sensores</b></a></li>
            <li><a href="#activos" data-toggle="tab"><b>Sensores Activos</b></a></li>
          </ul>
          
          <div class="tab-content">
              
            <br><br>
              
            <div class="tab-pane active" id="asignar">
                
                <table class="table table-condensed table-bordered">              
                    <thead>                  
                        <tr>                      
                            <th>
                                DESCRIPCION
                            </th>                     
                            <th>
                                TIPO
                            </th>                     
                            <th>
                                MUX
                            </th>                        
                            <th>
                                POSISCION
                            </th>                    
                            <th>
                                ACCION
                            </th>                      
                        </tr>                  
                    </thead> 
                    <tbody id="tbody_sensores"></tbody>
                </table>
                
            </div>       
              
            <div class="tab-pane" id="activos">
                
                <table class="table table-condensed table-bordered">              
                    <thead>                  
                        <tr>                      
                            <th>
                                DESCRIPCION
                            </th>                     
                            <th>
                                TIPO
                            </th>                     
                            <th>
                                MUX
                            </th>                        
                            <th>
                                POSISCION
                            </th>                    
                            <th>
                                ACCION
                            </th>                      
                        </tr>                  
                    </thead> 
                    <tbody id="tbody_sensores_activos"></tbody>
                </table>
                
            </div>       
              
      </div>  
          
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>  

<div class="modal fade" id="div_leds" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel"><b>LEDS</b></h5>
      </div>
      <div class="modal-body">        
          
          <ul class="nav nav-tabs">
            <li class="active"><a href="#asignar_led" data-toggle="tab"><b>Asignar Leds</b></a></li>
            <li><a href="#activos_led" data-toggle="tab"><b>Leds Activos</b></a></li>
          </ul>
          
          <div class="tab-content">
              
            <br><br>
              
            <div class="tab-pane active" id="asignar_led">
                
                <table class="table table-condensed table-bordered">              
                    <thead>                  
                        <tr>                      
                            <th>
                                DESCRIPCION
                            </th>                         
                            <th>
                                MUX
                            </th>                        
                            <th>
                                POSISCION
                            </th>                       
                            <th>
                                SENTIDO
                            </th>                     
                            <th>
                                ACCION
                            </th>                      
                        </tr>                  
                    </thead> 
                    <tbody id="tbody_leds"></tbody>
                </table>
                
            </div>       
              
            <div class="tab-pane" id="activos_led">
                
                <table class="table table-condensed table-bordered">              
                    <thead>                  
                        <tr>                      
                            <th>
                                DESCRIPCION
                            </th>                      
                            <th>
                                MUX
                            </th>                        
                            <th>
                                POSISCION
                            </th>                         
                            <th>
                                SENTIDO
                            </th>               
                            <th>
                                ACCION
                            </th>                      
                        </tr>                  
                    </thead> 
                    <tbody id="tbody_leds_activos"></tbody>
                </table>
                
            </div>       
              
      </div>  
          
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div> 