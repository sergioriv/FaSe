<?php
$archivo_actual = basename($_SERVER['PHP_SELF']);
$archivo_actual = str_replace('.php', '', $archivo_actual);
$archivo_actual = str_replace('-', ' ', $archivo_actual);
?>
<title><?php echo ucwords($archivo_actual) ?> | FaSe</title>
<style type="text/css">
:root {
  --colorPrimary: <?php echo $colorPrincipal ?>;
  --colorSecondary: <?php echo $colorSecundario ?>;
}
.tableList tbody td {padding: 3px 5px;}
</style>
<link rel="icon" type="image/png" href="img/favicon.ico"/>
<link rel="stylesheet" type="text/css" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/modal-bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/pasarela.css">
<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script src="js/jquery-2-2-0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/validacion.js"></script>

<div class="contenedorHeader">
	<div><a onClick="location.href='./'"><img src="img/logo-lobby.png"></a></div>
	<div class="txt">Configuración Inicial</div>
	<div></div>
</div>