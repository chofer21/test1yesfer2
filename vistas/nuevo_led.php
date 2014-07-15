<?php
include ('../configuracion/conexion_1.php');

$consulta_tipos_sensores = "SELECT id, descripcion AS tipo FROM tipo_sensor";
$con_tipos_sensores = q($consulta_tipos_sensores);

$lista_tipos_sensores = "";
foreach($con_tipos_sensores as $c){
    $lista_tipos_sensores .="<option value='".$c["id"]."'>".$c["tipo"]."</option>";
}


$agregar = true;
if( count($con_tipos_sensores)==0 ){
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
    var id_sensor = $(this).attr("id_led");
    
    $.post( "../controlador/control_funciones.php", 
            { opcion: "eliminar_led",
              id_led: id_sensor 
            },
            function(data){
                cl(data)
            }
    );
        
    $(this).closest("tr").remove();    
    
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
        <h2>Nuevo Led</h2>

<?php if($agregar){ ?>        
        
        <form action='../funciones/guardar.php'>
            <input type="hidden" name='opcion' value='nuevo_led' />
            
            <table class='table'>
                <tr>
                    <td>Descripcion</td> 
                    <td><input type='text' name='descripcion' required /> </td> 
                </tr>
                <tr>
                    <td>Mux</td> 
                    <td><input type='number' name='mux' required /> </td> 
                </tr>
                <tr>
                    <td>Posicion</td> 
                    <td><input type='number' name='posicion' required /> </td> 
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


echo "<h1>Tipos Leds</h1>";

$consulta = "SELECT *
           FROM led";
$con = q($consulta);

if(count($con) > 0){


echo "<table class='table titlefor' id='tableTipos' border='1'>";
echo "<thead></thead>";
echo "<tbody>";
foreach($con as $c){
echo "<tr>";
    echo "<td titlefor='Led'>"; 
        echo $c["descripcion"];
    echo "</td>";
    echo "<td titlefor='Mux'>"; 
        echo $c["mux"];
    echo "</td>";
    echo "<td titlefor='Posicion'>"; 
        echo $c["posicion"];
    echo "</td>";
    echo "<td titlefor='Eliminar'>"; 
        echo "  <button type='submit' class='bEliminar btn btn-danger' id_led='".$c["id"]."'>
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

?>
<br />

 
        
        
        
        
        
    </body>
</html>
        