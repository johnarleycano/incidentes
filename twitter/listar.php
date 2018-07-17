<?php
  session_start();
   
  // Controlo si el usuario ya está logueado en el sistema.
  if(isset($_SESSION['user'])){
    // Le doy la bienvenida al usuario.
    echo 'Bienvenido <strong>' . $_SESSION['user'] . '</strong>, <a href="cerrar-sesion.php">cerrar sesión</a>';
  }else{
    // Si no está logueado lo redireccion a la página de login.
    header("HTTP/1.1 302 Moved Temporarily");
    header("Location: index.php");
  }
?>



<?php

if(isset($_POST['Enter'])){
    $carpeta = $_POST['carpeta'];
    
}else{

    $carpeta="";
   
}
?>


<?php
error_reporting(0);


$pagina_web = "http://www.cflorez.org";
$pagina_web_quick = "http://www.hatovial.com/site_web_quickpass/";

//Se definen los mensajes que se podran enviar

// $mensaje16 = "Hasta el 13 de noviembre, la vía Solla - Glorieta Niquía (ambos sentidos) estará en rehabilitación de 9 p.m. a 5 a.m.";
// $mensaje17 = "La vía Solla - Glorieta Niquía se encuentra en rehabilitación. Por favor, tome la Autopista Norte";
$mensaje1 = "Prueba de la integracion de la API de twitter. ".$pagina_web;
$mensaje2 = "Tenga presente: la línea de emergencias 24 horas es 018000 52 44 77. ".$pagina_web;
$mensaje3 = "Este espacio es usado solo como medio informativo. Si solicita mayor información, visite nuestra página web: ".$pagina_web;
$mensaje4 = "Concesión encargada de administrar, mejorar, mantener y operar la vía Solla-Barbosa-Donmatías. ".$pagina_web;
$mensaje5 = "La concesión cuenta con servicios de CCO, línea de emergencias 24 Horas, carro grúa, ambulancia. Info: ".$pagina_web;
$mensaje6 = "Instalación QuickPass: lunes a viernes 9 a.m. a 12 m. y 2 p.m. a 5 p.m. Tel: 4012277 Ext. 120 ".$pagina_web_quick;
$mensaje7 = "Instalación de tag QuickPass gratis en instalaciones de Hatovial. Detalles: 4012277 ".$pagina_web_quick;
$mensaje8 = "Sr. usuario: El Carril 3 del Peaje Niquía (ambos sentidos) es exclusivo para paso con tag QuickPass ".$pagina_web_quick;
$mensaje9 = "Los carriles 2 y 5 de Peaje Trapiche serán exclusivos para pago con tag QuickPass de lunes a viernes.";
$mensaje10 = "Punto de instalación Quick Pass 1/5: Hatovial SAS - lunes a viernes 8am-12m y 2pm-5pm. Calle 59 # 48-35, Copacabana";
$mensaje11 = "Punto de instalación Quick Pass 2/5: Texaco (Peaje El Trapiche), lunes a miércoles 2pm-5pm; viernes a sábado 2pm-5pm";
$mensaje12 = "Punto de instalación Quick Pass 3/5: Estación de servicio ESSO Cocorolló. Autopista Norte Km 20, sentido Medellín-Barbosa";
$mensaje13 = "Punto de instalación Quick Pass 4/5: Estación de servicio Zeuss. Calle 104 # 01-401 Km 18, Autopista Norte-Copacabana";
$mensaje14 = "Punto de instalación Quick Pass 5/5: Car Center, Centro Comercial Oviedo. Carrera 43B # 6 Sur-140, Medellín";
$mensaje15 = "Las vías del Aburrá Norte (Solla - Barbosa - Donmatías) presentan alto flujo vehicular. Transite con precaución";

// $semana_santa1 = "Peregrinos del Se&ntilde;or Ca&iacute;do, por favor usar el and&eacute;n y los puentes peatonales. #SemanaSanta";
// $semana_santa2 = "Peregrinos, les recordamos que el acceso a Girardota se har&aacute; por la v&iacute;a provisional. #SemanaSanta";
// $semana_santa3 = "Se&ntilde;or usuario, por favor tenga en cuenta que hay peregrinos en la v&iacute;a. #SemanaSanta";
?>

<!DOCTYPE html> 
<html lang="en"> 
  <head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi primer proyecto con Booststrap</title>

    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> 
    <script src="js/bootstrap.min.js"></script>
    <script>
        function deshabilitar(){
            document.getElementById("tweet1").checked = false;
            document.getElementById("tweet2").checked = false;
            document.getElementById("tweet3").checked = false;
            document.getElementById("tweet4").checked = false;
            document.getElementById("tweet5").checked = false;
            document.getElementById("tweet6").checked = false;
            document.getElementById("tweet7").checked = false;
        }
    </script>      
  </head> 
    <body>
      <!-- <a href="#content" class="sr-only sr-only-focusable">Skip to main content</a> -->
      <div class="container" id="content" tabindex="-1">
            <form action="" method="post" >
                <div class="form-group col-xs-12">
                    <label class="col-xs-12 control-label">Seleccione Una carpeta de la lista:</label>
                    <div class="form-group col-xs-6">
                        <!-- <label class="col-xs-6 control-label">Seleccione Una carpeta de la lista:</label> -->
                        <select name="carpeta" class = "form-control input-lg">
                            <option value="0">Seleccione Carpeta</option>
                            <option value="1">Compras</option>
                            <option value="2">Gestion Humana</option>
                            <option value="3">Juridica</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-6">
                        <input type="submit" class="btn btn-default btn-lg" name="Enter" value="buscar">
                    </div>
                </div>               
            </form>
            <div>       
                <table class="table table-striped">

                </table>
            </div>
      </div>
    </body>
</html>