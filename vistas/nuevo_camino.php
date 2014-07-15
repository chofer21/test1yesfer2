<?php 
include ('../configuracion/conexion_1.php');


$consulta_nodos = "SELECT id,descripcion,tipo FROM nodo";
$con_nodos = q($consulta_nodos);

$lista_nodos = "";
foreach($con_nodos as $c){
    $lista_nodos .="<option value='".$c["id"]."'>".$c["descripcion"]."(".$c["tipo"].")"."</option>";
}


$agregar = true;
if( count($con_nodos)==0 ){
    $agregar = false;
};





?>
<html>
    <head>
        
<script src="../js/jquery210.js" ></script>

<script src="../js/bootstrap/js/bootstrap.js" ></script>
<link rel="stylesheet" href="../js/bootstrap/css/bootstrap.css" >
<script>
function cl(m){ console.log(m); }  
    
$(document).ready(function(){
    
$(".titlefor").each(function(){
    if($(this).attr("id")){ if($(this).attr("id")){ theadTable($(this).attr("id"));  }} 
});    
    
function theadTable(table){
    $("#" + table + " > tbody > tr:first  > td").each(function(){
	var title = $(this).attr("titlefor");
	if(!title){ title = ""; }
	$("#" + table + " > thead").append("<th>" + title + "</th>");
    });
}    

$(".bEliminar").click( function(){
    var id_nodo = $(this).attr("id_nodo");
    var $this = $(this);
    $.post( "../controlador/control_funciones.php", 
            { opcion: "eliminar_nodo",
              id_nodo: id_nodo 
            },
            function(data){
                if(data!==''){
                    alert(data)
                }else{
                    $this.closest("tr").remove();    
                }
            }
    );
         
         
    
});


$("#enviar").click(function(){
   var inicio = $("#inicio").val();
   var fin = $("#fin").val();
   var distancia = $("#distancia").val();
   
   if(inicio == fin ){
       $("#alerta_inicio_fin_igual").show();
   }else if( distancia== ''){
       $("#alerta_distancia_vacia").show();
   }else{
       $("#form1").submit();
   }
   
});

$("#inicio").change(function(){
    $("#alerta_inicio_fin_igual").hide();
});
$("#fin").change(function(){
    $("#alerta_inicio_fin_igual").hide();
});


});
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
</script>
<style>
    .table{
        width: auto;
    }
</style>
    </head>
    <body>
        <h2>Nuevo Camino</h2>


<?php if($agregar){ ?>        


<div class="alert alert-danger" id='alerta_inicio_fin_igual' style='display:none'>
    El inicio debe ser diferente al fin
</div>     
<div class="alert alert-danger" id='alerta_distancia_vacia' style='display:none'>
    Debe ingresar distancia
</div>             
        
        
        <form autocomplete="off" action='../funciones/guardar.php' id='form1'>
            <input type="hidden" name='opcion' value='nuevo_camino' />
            
            <table class='table'>
                <tr>
                    <td>Description</td> 
                    <td><input type='text' name='descripcion' required /> </td> 
                </tr>
                <tr>
                    <td>Distancia</td> 
                    <td><input type='number' name='distancia' id='distancia' required /> </td> 
                </tr>
                <tr>
                    <td>Inicio</td> 
                    <td>
                        <select name='inicio' id='inicio'>
                            <?php echo $lista_nodos; ?> 
                        </select>
                    </td> 
                    <td>Fin</td> 
                    <td>
                        <select name='fin' id='fin'>
                            <?php echo $lista_nodos; ?> 
                        </select>
                    </td> 
                </tr>
                <tr>
                    <td colspan='2'>
                        <button type="button" id='enviar' class="btn btn-success">
                            <span class="glyphicon glyphicon-save"></span> Guardar
                        </button> 
                    </td>
                </tr>
            </table>
            
        </form>
            
            
        


<?php


echo "<h1>Nodos</h1>";

$consulta = "SELECT c.descripcion,
                    c.distancia,
                    i.descripcion AS inicio,
                    f.descripcion AS fin
               FROM camino c, nodo i, nodo f
              WHERE c.inicio = i.id AND c.fin = f.id";
$con = q($consulta);

if(count($con) > 0){


echo "<table class='table titlefor' id='tableTipos' border='1'>";
echo "<thead></thead>";
echo "<tbody>";
foreach($con as $c){
echo "<tr>";
    echo "<td titlefor='Nombre'>"; 
        echo $c["descripcion"];
    echo "</td>";
    echo "<td titlefor='Distancia'>"; 
        echo $c["distancia"];
    echo "</td>";
    echo "<td titlefor='Inicio'>"; 
        echo $c["inicio"];
    echo "</td>";
    echo "<td titlefor='Fin'>"; 
        echo $c["fin"];
    echo "</td>";
    echo "<td titlefor='Eliminar'>"; 
        echo "  <button type='submit' class='bEliminar btn btn-danger'>
                    <span class='glyphicon glyphicon-remove'></span>
                </button> 
            ";
        echo $c["id"];
    echo "</td>";
echo "</tr>";
}
echo "</tbody>";

echo "</table>";


}else{
    echo "No hay datos";
    
}



}// fin agregar 
else{
?>
<br />

<div class="alert alert-warning">
    No hay sensores para agregar al nodo, debe agregar un nuevo  &nbsp;&nbsp;
  <a href='nuevo_sensor.php' target='_blanck'>    
        <button type="button" class="btn btn-info">
          <span class="glyphicon glyphicon-plus"></span> sensor
        </button>    
  </a>
</div>

               
<?php
}
?>

       
        
        
        
    </body>
</html>
        