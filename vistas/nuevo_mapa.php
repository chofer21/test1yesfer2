<?php
    include ('../configuracion/conexion_1.php');
    
    $niveles = q("select distinct(nivel) as nivel from nodo");
    $max = q1("select max(nivel)+1 as sig from nodo");
    
?>

<script src="../js/jquery210.js" ></script>
<script src="../js/bootstrap/js/bootstrap.js" ></script>
<link rel="stylesheet" href="../js/bootstrap/css/bootstrap.css" >

<script>

$(document).ready(function(){
    
   $(".nivel").click(function(){
       
     nivel = $(this).attr("nivel");
     window.open("crear_mapa.php?ntabla="+nivel);  
       
   });
   
   $("#mas").click(function(){
      
      nivel = $(this).val();
      window.open("crear_mapa.php?ntabla="+nivel);   
       
   });
   
   $(".ver").click(function(){
       
        window.location = "crear_mapa.php";
       
   });
   
});

</script>

<body>
    
    <?php require_once("menu.php"); ?>
        
    <div class="container" style="width: 40%">
        
        <h2>Configuracion</h2>
        <br>
        <form action='../funciones/guardar.php'>
            <input type="hidden" name='opcion' value='config_tabla' />
            
            <table class='table'>
                <tr>
                    <td>Filas</td> 
                    <td><input type='number' name='filas' required /> </td> 
                </tr>
                <tr>
                    <td>Columnas</td> 
                    <td><input type='number' name='col' required /> </td> 
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
    
    $con = q("select * from tamano_tabla");
    
    echo "<table class='table titlefor' id='tableTipos' border='1'>";
    echo "<thead><tr><th>Filas</th><th>Columnas</th><th>Eliminar</th></tr></thead>";
    echo "<tbody>";
    foreach($con as $c){
    echo "<tr>";
        echo "<td titlefor='Filas'>"; 
            echo $c["filas"];
        echo "</td>";
        echo "<td titlefor='Columnas'>"; 
            echo $c["col"];
        echo "</td>";
        echo "<td titlefor='Eliminar'>"; 
            echo "  <button type='submit' class='bEliminar btn btn-danger' id_led='".$c["id"]."'>
                        <span class='glyphicon glyphicon-remove'></span>
                    </button> 
                ";
        echo "</td>";
    echo "</tr>";
    }
    echo "</tbody>";

    echo "</table>";
    
    ?>
        
        <br>
        
        <button class="btn btn-primary ver"> 
            VER MAPA
        </button>    
        
    </div>  
</body>