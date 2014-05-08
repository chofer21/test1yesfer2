<?php
$color_nodo = "rgb(255, 0, 0)";
$color_camino = "cyan";
$color_salida = "green";


echo "<table border='1' id='table1'>";
for($ti=1;$ti<=20;$ti++){
    echo "<tr>";
        for($di=1;$di<=20;$di++){
            echo "<td col='$di' row='$ti'>"; 
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


?>

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
      
if(!unir && !asignar){
   if( $(this).css("background-color") === "<?php echo $color_nodo; ?>" ){
       $(this).css("background-color","white");
       eliminar_nodo( $(this).attr("nodo_id") );
       
   }else{
       $(this).css("background-color","<?php echo $color_nodo; ?>");
       $(this).attr("nodo","nodo" + cont_nodo);
       crear_nodo("nodo" + cont_nodo);
       
       cont_nodo++;
   }

}else
if(unir){
    
if( $(this).css("background-color") === "<?php echo $color_nodo; ?>" ){  
    if(ini_col==''){
        ini_col = col;
        ini_row = row;
    }else{
        fin_col = col;
        fin_row = row;
   
        cl( ini_col + " " + fin_col);
        cl( ini_row + " " + fin_row);
        if(ini_col==fin_col){
            var ini = ini_row;
            var fin = fin_row;

            if(parseInt(ini)>parseInt(fin)){
                var aux = fin;
                    fin = ini;
                    ini = aux;
            }    
            ini = parseInt(ini) + 1;
            fin = parseInt(fin) - 1;
            
            
            var nodo_ini = $("td[col="+ ini_col +"][row="+ini_row+"]").attr("nodo_id");  
            var nodo_fin = $("td[col="+ fin_col +"][row="+fin_row+"]").attr("nodo_id");  
                        
            cl(ini + " ini fin " + fin);
            for(var xx = ini; xx<= fin; xx ++ ){
                $("td[col="+ ini_col +"][row="+xx+"]").css("background-color","<?php echo $color_camino; ?>");
            }
            
            var distancia = ingresar_distancia(fin-ini);
            
            unir_nodos(nodo_ini,nodo_fin,distancia);
            
        
        }else 
        if(ini_row == fin_row){
            
            
                var ini = ini_col;
                var fin = fin_col;


                                cl(ini + " ini fin " + fin);
                if(parseInt(ini)>parseInt(fin)){
                    var aux = fin;
                        fin = ini;
                        ini = aux;
                        
                       cl(8);
                }    
                ini = parseInt(ini) + 1;
                fin = parseInt(fin) - 1;

                                cl(ini + " ini fin " + fin);

                
                var nodo_ini = $("td[col="+ ini_col +"][row="+ini_row+"]").attr("nodo_id");  
                var nodo_fin = $("td[col="+ fin_col +"][row="+fin_row+"]").attr("nodo_id");  
                
                for(var xx = ini; xx<= fin; xx ++ ){
                    $("td[col="+ xx +"][row="+ini_row+"]").css("background-color","<?php echo $color_camino; ?>");
                }


                var distancia =  ingresar_distancia(fin-ini);
          
                unir_nodos(nodo_ini,nodo_fin,distancia);

        }
        
        
        
        ini_col = '';
        ini_row = '';
        fin_col = '';
        fin_row = '';
    }
    
    
}
   
   
}else 
if(asignar){
    var nodo_id = $(this).attr("nodo_id");
    cargar_sensores(nodo_id);
    
}


});


$("#unir_puntos").click(function(){
   if(unir){
        unir = false;
        $(this).text("Unir");
   }else{
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
         asignar = true;
         $(this).text("No asignar");
         
    } 
});

$(document).on("click",".b_asignar_sensor",function(){
    var nodo_id = $(this).attr("nodo_id");
    var sensor_id = $(this).attr("sensor_id");
    
    if(nodo_id !== 'undefined' && sensor_id!== 'undefined'){
        asignar_sensores(nodo_id,sensor_id);
    }
    
});

function asignar_sensores(nodo_id,sensor_id){
        $.post( "../controlador/control_funciones.php", 
        { opcion: "asignar_sensor",
          nodo_id: nodo_id,
          sensor_id: sensor_id
        },
        function(data){
                cl(data);
        }
    );
}

function cargar_sensores(nodo_id){
    $.ajax({
        dataType: "json",
        url: "../controlador/control_funciones.php",
        data: { opcion: "cargar_sensores",
                nodo_id: nodo_id
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
                            table +="<button class='b_asignar_sensor' nodo_id = '"+ nodo_id +"' sensor_id='"+tt["id"]+"'>asignar</button>";                
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
                }else if(tt["nodo_asignado"]==nodo_id){
                    mostrar_asignados = true;
                    table_asignados += "<tr>";
                        table_asignados += "<td>";
                            table_asignados +="<button class='b_retirar_sensor' nodo_id = '"+ nodo_id +"' sensor_id='"+tt["id"]+"'>retirar</button>";                
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

function crear_nodo(nodo){
    cl(nodo);
    $.post( "../controlador/control_funciones.php", 
        { opcion: "crear_nodo",
          descripcion: nodo,
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
    
    $.post( "../controlador/control_funciones.php", 
            { opcion: "unir_nodos",
              descripcion: "union " + nodo_ini + "-" + nodo_fin,
              distancia: distancia,
              inicio: nodo_ini,
              fin: nodo_fin
            },
            function(data){
                    cl(data);
            }
    );
}


function ingresar_distancia(dis)
{
    var distancia=prompt("Digite distancia entre puntos",dis + 1);
    return distancia;
}

function cl(m){ console.log(m); }
</script>