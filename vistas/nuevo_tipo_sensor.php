<?php 
include ('../configuracion/conexion.php');
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
    var id_tipo_sensor = $(this).attr("id_tipo_sensor");
    var $this = $(this);
    $.post( "../controlador/control_funciones.php", 
            { opcion: "eliminar_tipo_sensor",
              id_tipo_sensor: id_tipo_sensor 
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
        <h2>Nuevo Tipo Sensor</h2>
        
        <form action='../funciones/guardar.php'>
            <input type="hidden" name='opcion' value='nuevo_tipo_sensor' />
            <table class='table'>
                <tr>
                    <td>Description</td> 
                    <td><input type='text' name='descripcion' required /> </td> 
                </tr>
                <tr>
                    <td>Rango Max</td> 
                    <td><input type='number' name='rango_max' required /> </td> 
                </tr>
                <tr>
                    <td>Rango Min</td> 
                    <td><input type='number' name='rango_min' required /> </td> 
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


echo "<h1>Tipos sensores</h1>";

$consulta = "SELECT id,
                    descripcion,
                    rango_max,
                    rango_min
               FROM tipo_sensor";
$con = q($consulta);

if(count($con) > 0){


echo "<table class='table titlefor' id='tableTipos' border='1'>";
echo "<thead></thead>";
echo "<tbody>";
foreach($con as $c){
echo "<tr>";
    echo "<td titlefor='Tipo'>"; 
        echo $c["descripcion"];
    echo "</td>";
    echo "<td titlefor='Rango Max'>"; 
        echo $c["rango_max"];
    echo "</td>";
    echo "<td titlefor='Rango Min'>"; 
        echo $c["rango_min"];
    echo "</td>";
    echo "<td titlefor='Eliminar'>"; 
        echo "  <button type='submit' class='bEliminar btn btn-danger' id_tipo_sensor='".$c["id"]."'>
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

?>

       
        
        
        
    </body>
</html>
        