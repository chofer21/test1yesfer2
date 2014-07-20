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
            { opcion: "eliminar_tiempo",
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
        
        <?php require_once("menu.php"); ?>
        
        <div class="container" style="width: 40%">
        
        <h2>Tiempo</h2>

<?php if($agregar){ ?>        
        
        <form action='../funciones/guardar.php'>
            <input type="hidden" name='opcion' value='nuevo_tiempo' />
            
            <table class='table'>
                <tr>
                    <td>Descripcion</td> 
                    <td><select id="descripcion" name="descripcion">
                                
                            <option value="tiempo_normal">Tiempo Normal</option>
                            <option value="tiempo_alarma">Tiempo de Alarma</option>
                            
                        </select></td> 
                </tr>
                <tr>
                    <td>Valor (Segundos)</td> 
                    <td><input type='number' name='valor' required /> </td> 
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


echo "<h1>Tiempos</h1>";

$consulta = "SELECT *
           FROM configuracion";
$con = q($consulta);

if(count($con) > 0){


echo "<table class='table titlefor' id='tableTipos' border='1'>";
echo "<thead></thead>";
echo "<tbody>";
foreach($con as $c){
echo "<tr>";
    echo "<td titlefor='DESCRIPCION'>"; 
        echo $c["descripcion"];
    echo "</td>";
    echo "<td titlefor='TIEMPO'>"; 
        echo $c["valor"];
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

 
        
        
        
        </div>
        
    </body>
</html>
        