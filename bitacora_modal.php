<!DOCTYPE html>
<html>
<head>
	<title></title>
<link rel="stylesheet" type="text/css" href="css/campo.css">
<link rel="stylesheet" type="text/css" href="css/ventana.css">
<link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
<script src="jquery/jquery-1.9.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>

<script type="text/javascript" src="libs/js/vista.js"></script>
<script type="text/javascript" src="libs/js/scripts.js"></script>
<style type="text/css">

    header {
        text-align: center;

    }
	
	

    section table tr td a{
    	font-size: 10px;
    	font-family:Verdana, Arial, Helvetica, sans-serif;
		font-weight: bold;
    	align:left;
    	height:18px;
    	cursor:hand;
		vertical-align:top;	
		color: black;
		background: #DAF7A6;

    }

  


</style>


</head>
<body>
    <header></header>
    <hr>

    <br>
    <br>
    <br>

    

		<center>
	    	<section>
		        <table>
		            
					<tr>
		            	<th class="resaltar">Fecha Inicial&nbsp;&nbsp;&nbsp;</th>
						
		                <td><input type="text" class="campos cmpFec" id="bd-desde"  name="bd-desde"/></td>
					</tr>
					<tr><th colspan="8"  height="20">&nbsp;</th></tr>
					<tr>
		                <th class="resaltar">Fecha Final</th>
		                <td><input type="text" class="campos cmpFec" id="bd-hasta"  name="bd-hasta"/></td>
					</tr>
					<tr><th colspan="8"  height="20">&nbsp;</th></tr>
					<tr>
		                <th class="resaltar">Usuario</th>
		                
		                <td><select id="select" name="select">
		                        <option value="">.::Seleccione::.</option>
		                        <?php
		                        include_once "conexion.php";

		                        $registro = mysql_query("SELECT * FROM dvm_usuarios ORDER BY id DESC");

		                        while($registro2 = mysql_fetch_array($registro)){
		                            ?>
		                            <option value="<?php echo $registro2['id']; ?>_<?php echo $registro2['nombres']; ?>_<?php echo $registro2['apellidos']; ?>"><?php echo $registro2['login']; ?></option>
		                        <?php
		                        }

		                        ?>
		                    </select>
		                </td>

		               
		                <td><input type="text" id="nombre"  name="nombre" hidden /></td>
		                <td><input type="text" id="apellido"  name="apellido" hidden /></td>
					</tr>
					<tr><th colspan="8"  height="20">&nbsp;</th></tr>
					<tr>
		                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		                <td width="200"><a target="_blank" onclick="reportePDF()">Exportar a PDF</a></td>
		            </tr>
		            
		        </table>

		   	</section>
	 	</center> 

	 	<script>

	 	vis_ponCampoFecha(".cmpFec");

	 $("select").change(mostrarValores);

    function mostrarValores(){

    	datos = document.getElementById('select').value.split('_')

    	$("#nombre").val(datos[1]);
    	$("#apellido").val(datos[2]);

    }

   
   
        function reportePDF(){



            var desde = $('#bd-desde').val();
            var hasta = $('#bd-hasta').val();
            var select = $('#select').val();
            var nombre = $('#nombre').val();
            var apellido = $('#apellido').val();

           
            
            if( desde=="" ){

				alert("Seleccione el campo");
				

			}else if(hasta==""){

				alert("Seleccione el campo");

			}else if(select==""){

				alert("Seleccione el campo");
			}

			else{
			
	           
	            window.open('repor_bit.php?desde='+desde+'&hasta='+hasta+'&select='+select+'&nombre='+nombre+'&apellido='+apellido);
	    }
	}
	 	</script>
   
</body>




    
	

</html>