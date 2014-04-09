<html>
    <head>
        
<script src="../js/jquery210.js" ></script>

<script src="../js/bootstrap/js/bootstrap.js" ></script>
<link rel="stylesheet" href="../js/bootstrap/css/bootstrap.css" >
<script>
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
            <input type="hidden" name='opcion' value='nuevoTipoSensor' />
            <table class='table'>
                <tr>
                    <td>Description</td> 
                    <td><input type='text' name='descripcion' required /> </td> 
                </tr>
                <tr>
                    <td>Rango Max</td> 
                    <td><input type='number' name='rangoMax' required /> </td> 
                </tr>
                <tr>
                    <td>Rango Min</td> 
                    <td><input type='number' name='rangoMin' required /> </td> 
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

include ('../configuracion/conexion.php');

echo "<h1>hola</h1>";

$consulta = "SELECT descripcion,
                    rangoMax,
                    rangoMin
               FROM tipo_sensor";
$con = q($consulta);


echo "<table class='table titlefor' id='tableTipos' border='1'>";
echo "<thead></thead>";
echo "<tbody>";
foreach($con as $c){
echo "<tr>";
    echo "<td titlefor='Tipo'>"; 
        echo $c["descripcion"];
    echo "</td>";
    echo "<td titlefor='Rango Max'>"; 
        echo $c["rangoMax"];
    echo "</td>";
    echo "<td titlefor='Rango Min'>"; 
        echo $c["rangoMin"];
    echo "</td>";
echo "</tr>";
}
echo "</tbody>";

echo "</table>";


?>

       
        
        
        
    </body>
</html>
        