<?php

include ('../configuracion/conexion.php');


$consulta_sensores = "SELECT id FROM sensor " ;
$con_sensores = q($consulta_sensores);

$agregar = TRUE;
if(count($con_sensores==0)){
    $agregar=FALSE;
    }

?>      
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
        <h2>Nuevo nodo</h2>
 
        
<?php
if($agregar){ ?>     
        
        <form action='../funciones/guardar.php'>
            <input type="hidden" name='opcion' value='nuevo_nodo' />
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



}
else{
    ?>
      
        <br />
        
<div class="alert alert-warning">
    No hay sensores agregados, debe agregar un  &nbsp;&nbsp;
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
        