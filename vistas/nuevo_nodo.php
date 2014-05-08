<?php 
include ('../configuracion/conexion.php');


$consulta_sensores = "SELECT id FROM sensor";
$con_sensores = q($consulta_sensores);

$agregar = true;
if( count($con_sensores)==0 ){
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


});
</script>
<style>
    .table{
        width: auto;
    }
</style>
    </head>
    <body>
        <h2>Nuevo Nodo</h2>


<?php if($agregar){ ?>        
               
        
        <form action='../funciones/guardar.php'>
            <input type="hidden" name='opcion' value='nuevo_nodo' />
            
            <table class='table'>
                <tr>
                    <td>Description</td> 
                    <td><input type='text' name='descripcion' required /> </td> 
                </tr>
                <tr>
                    <td>Tipo</td> 
                    <td>
                        <select name='tipo_nodo'>
                            <option value='normal'>Normal</option>
                            <option value='salida'>Entrada/salida</option>
                        </select>
                        
                    </td> 
                </tr>
                <tr>
                    <td colspan='2'>
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-save"></span> Guardar
                        </button> 
                    </td>
                </tr>
            </table>
            
        </form>
            
            
        


<?php


echo "<h1>Nodos</h1>";

$consulta = "SELECT id,descripcion, tipo
             FROM nodo";
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
    echo "<td titlefor='Tipo'>"; 
        echo $c["tipo"];
    echo "</td>";
    echo "<td titlefor='Eliminar'>"; 
        echo "  <button type='submit' class='bEliminar btn btn-danger' id_nodo='".$c["id"]."'>
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
        