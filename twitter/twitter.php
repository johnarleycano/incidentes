<?php
include_once "../clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" )
    header("location:../entrada_usuario.php");

if(isset($_GET["msg"]))
    echo $_SESSION[APL]->msg($_GET["msg"]);
?>



<!-- 
<?php

if(isset($_POST['Enter'])){
    $carpeta = $_POST['carpeta'];
    
}else{

    $carpeta="";
   
}
?> -->


<?php
error_reporting(0);


$pagina_web = "http://www.devimed.com.co";
//Se definen los mensajes que se podran enviar

// $mensaje16 = "Hasta el 13 de noviembre, la vía Solla - Glorieta Niquía (ambos sentidos) estará en rehabilitación de 9 p.m. a 5 a.m.";
// $mensaje17 = "La vía Solla - Glorieta Niquía se encuentra en rehabilitación. Por favor, tome la Autopista Norte";
$mensaje0 = "&ensp;Prueba de implementacion de la API. ".$pagina_web;
$mensaje1 = "&ensp;Línea gratuita de Emergecias 018000 518427 - 386 0085. ";
$mensaje2 = "&ensp;Este espacio es usado solo como medio informativo. Si solicita mayor información, visite : ".$pagina_web;
$mensaje3 = "&ensp;Vía El Santuario–Caño Alegre, ambos tramos, operan con normalidad. ";
$mensaje4 = "&ensp;Vía Rionegro-La Ceja,  ambos tramos, operan con normalidad.";
$mensaje5 = "&ensp;Vía Glorieta Palmas-Don Diego, ambos tramos, operan con normalidad.";
$mensaje6 = "&ensp;Vía Don Diego-La Ceja, ambos tramos, operan con normalidad.";
$mensaje7 = "&ensp;Vía Don Diego-Rionegro, ambos tramos, operan con normalidad.";
$mensaje8 = "&ensp;Vía La Ceja-La Unión,  ambos tramos, operan con normalidad.";
$mensaje9 = "&ensp;Vía Rionegro- El Carmen de V, ambos tramos, operan con normalidad.";
$mensaje10 = "&ensp;Vía Rionegro- 4 Esquinas – Marinilla, ambos tramos, operan con normalidad.";
$mensaje11 = "&ensp;T Aeropuerto-Llanogrande,  ambos tramos, operan con normalidad.";
$mensaje12 = "&ensp;La Fe-El Retiro, ambos tramos, operan con normalidad.";
// $mensaje13 = "Punto de instalación Quick Pass 4/5: Estación de servicio Zeuss. Calle 104 # 01-401 Km 18, Autopista Norte-Copacabana";
// $mensaje14 = "Punto de instalación Quick Pass 5/5: Car Center, Centro Comercial Oviedo. Carrera 43B # 6 Sur-140, Medellín";
// $mensaje15 = "Las vías del Aburrá Norte (Solla - Barbosa - Donmatías) presentan alto flujo vehicular. Transite con precaución";

// $semana_santa1 = "Peregrinos del Se&ntilde;or Ca&iacute;do, por favor usar el and&eacute;n y los puentes peatonales. #SemanaSanta";
// $semana_santa2 = "Peregrinos, les recordamos que el acceso a Girardota se har&aacute; por la v&iacute;a provisional. #SemanaSanta";
// $semana_santa3 = "Se&ntilde;or usuario, por favor tenga en cuenta que hay peregrinos en la v&iacute;a. #SemanaSanta";
?>

<!DOCTYPE html> 
<html lang="en"> 
  <head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Twitter Devimed</title>

    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
    <link href="css/estilo.css" rel="stylesheet" media="screen"> 
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
        <div class="container">
            <form action="enviar/index.php" method="post" >
              <h3>Publicacion de Estados de la Via</h3>
              <br>
              <p><strong>-</strong> Selecciona entre las diferentes opciones para armar el mensaje o escoja uno dentro de los predefinidos y da clic en el boton <strong>"Publicar Twitter".</strong></p>
              <p><strong>-</strong> Los capos marcados con <strong>(*) </strong>son obligatorios.</p>
              <ul class="nav nav-pills nav-justified">
                <li class="active"><a href="#">TWITTER DEVIMED</a></li>
                    <br>
                    <br>
                    <div class="row">
                    <div class="col-md-3">
                        <label class="col-md-3 control-label">Via:*</label>
                            <select name="via" onChange="deshabilitar()" class="form-control input-lg">
                                <option value=""></option>
                                <option value="Autopista sentido Medellín-Bogotá">Autopista sentido Medellín-Bogotá</option>
                                <option value="Autopista sentido Bogotá-Medellín">Autopista sentido Bogotá-Medellín</option>
                                <option value="La via Rionegro-La Ceja">Rionegro - La Ceja</option>
                                <option value="La via Glorieta Palmas-Don Diego">Glorieta Palmas - Don Diego</option>
                                <option value="La via Don Diego-La Ceja">Don Diego-La Ceja</option>
                                <option value="La via Don Diego-Rionegro">Don Diego-Rionegro</option>
                                <option value="La via La Ceja-La Unión">La Ceja-La Unión</option>
                                <option value="La via Rionegro-El Carmen de V.">Rionegro-El Carmen de V.</option>
                                <option value="La via Rionegro - 4 Esquinas-Marinilla.">Rionegro - 4 Esquinas-Marinilla</option>
                                <option value="La via T Aeropuerto-Llanogrande.">T Aeropuerto-Llanogrande</option>
                                <option value="La via La Fe-El Retiro">La Fe-El Retiro</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        <label class="col-md-3 control-label">Ubicación:</label> 
                            <select name="ubicacion" class ="form-control input-lg" >
                                <option value=""></option>
                                <option value="Guarne">Guarne</option>
                                <option value="comfama">sector comfama</option>
                                <option value="San Antonio de Pereira">sector San Antonio de Pereira</option>
                                <option value="peaje">sector Peaje</option>
                                <option value="La Fe">sector La Fe</option>
                                <option value="Llano Grande">sector Llano Grande</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        <label class="col-md-3 control-label">Km:</label>
                            <input type="text" name="km" id="km" class="form-control input-lg" placeholder="0+000">
                        </div>
                        <div class="col-md-2">
                        <label class="col-md-3 control-label">Estado:*</label> 
                            <select name="estado" onChange="deshabilitar()" class ="form-control input-lg">
                                <option value=""></option>
                                <option value="no presenta ninguna novedad">Sin Novedad</option>
                                <option value="presenta movilidad fluída">Movilidad Flu&iacute;da</option>
                                <option value="presenta movilidad reducida">Movilidad Reducida</option>
                                <option value="se encuentra en condiciones de vía húmeda">V&iacute;a H&uacute;meda</option>
                                <option value="presenta cierre parcial">Cierre Parcial</option>
                                <option value="presenta cierre total">Cierre Total</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                        <label class="col-md-3 control-label">Causa(Opcional):</label> 
                            <select name="causa" onChange="deshabilitar()" class="form-control input-lg">
                                <option value=""></option>
                                <option value="Mensaje de prueba con el API de Twitter">Mensaje de prueba con el API de Twitter</option>
                                <option value="accidente de tránsito">Accidente de Tr&aacute;nsito</option>
                                <option value="alto flujo vehicular">Alto Flujo Vehicular</option>
                                <option value="realización de ciclovía">Ciclov&iacute;a</option>
                                <option value="condiciones de lluvia">Condici&oacute;n Lluviosa</option>
                                <option value="realización de evento público">Evento P&uacute;blico</option>
                                <option value="incidente de tránsito">Incidente de tr&aacute;nsito</option>
                                <option value="manifestación pública">Manifestaci&oacute;n P&uacute;blica</option>
                                <option value="pavimentación de vía">Pavimentaci&oacute;n de v&iacute;a</option>
                                <option value="rehabilitación de vía">Rehabilitaci&oacute;n de v&iacute;a</option>
                                <option value="trabajos de modernización del peaje Niquía">Trabajos de obra</option>
                                <option value="trabajos en la vía">Trabajos en la V&iacute;a</option>
                                <option value="caídas de piedras">Caídas de Piedras</option>
                                <option value="derrame de líquidos">Derrame de líquidos</option>
                                <option value="evento deportivo">Evento Deportivo</option>
                                <option value="vehiculo varado">Vehiculo Varado</option>
                                <option value="derrumbe">derrumbe</option>
                            </select>
                        </div>
                        <div class="clearfix visible-lg"></div>
                  </div>
              </ul>
            </div>
        
        <br>
        <br>
        <div class="container">
          <h3>MENSAJES PREDEFINIDOS:</h3>
          <p></p>
          <br>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet0" value="<?php echo $mensaje0; ?>">
                <?php echo $mensaje0; ?></label>
            </div>
              <div class="radio">
              <label><input type="radio" name="tweet" id="tweet1" value="<?php echo $mensaje1; ?>">
                <?php echo $mensaje1; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet2" value="<?php echo $mensaje2; ?>">
                <?php echo $mensaje2; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet3" value="<?php echo $mensaje3; ?>">
                <?php echo $mensaje3; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet4" value="<?php echo $mensaje4; ?>">
                <?php echo $mensaje4; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet5" value="<?php echo $mensaje5; ?>">
                <?php echo $mensaje5; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet6" value="<?php echo $mensaje6; ?>">
                <?php echo $mensaje6; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet7" value="<?php echo $mensaje7; ?>">
                <?php echo $mensaje7; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet8" value="<?php echo $mensaje8; ?>">
                <?php echo $mensaje8; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet9" value="<?php echo $mensaje9; ?>">
                <?php echo $mensaje9; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet10" value="<?php echo $mensaje10; ?>">
                <?php echo $mensaje10; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet11" value="<?php echo $mensaje11; ?>">
                <?php echo $mensaje11; ?></label>
            </div>
            <div class="radio">
              <label><input type="radio" name="tweet" id="tweet12" value="<?php echo $mensaje12; ?>">
                <?php echo $mensaje12; ?></label>
            </div>
        </div>
        <br>
        <br>
        <div class="container">
        <table align="Center">
            <tr>
                <td>
                    <button type="submit" class="btn btn-success btn-lg" id="guardar" name="publicar" value="publicar_twitter">Publicar Twitter</button>
                </td>
            </tr>
        </table>
        </div>
     </form>   
    </body>
</html>