<?php include'config-lobby.php'; include'key.php';

	$decodeID = $_SERVER["REQUEST_URI"];
	$cad = split("[?]",$decodeID); //separo la url desde el ?
	$decodeID = $cad[1]; //capturo la url desde el separador ? en adelante
	$decodeID = base64_decode($decodeID); //decodifico la cadena
	$decodeID = str_replace($llave_encriptacion, "", "$decodeID"); //quito la llave de la cadena
	
	//procedo a dejar cada variable en el $_GET
	$cad_get = split("[&]",$decodeID); //separo la url por &
	foreach($cad_get as $value)
	{
	$val_get = split("[=]",$value); //asigno los valosres al GET
	$_GET[$val_get[0]]=utf8_decode($val_get[1]);
	}

if($_GET['id']){

	$clinicaSql = $con->query("SELECT * FROM citas, clinicas WHERE citas.ct_idClinica = clinicas.IDClinica AND citas.IDCita = '$_GET[id]'");
	$clinicaRow = $clinicaSql->fetch_assoc();

	if($clinicaRow['cl_logo']!=""){ $titulo = "<img src='$ruta$clinicaRow[cl_logo]'>"; } else { $titulo = $clinicaRow['cl_nombre']; }

	if($clinicaRow['ct_estado']<=1){
		$con->query("UPDATE citas SET ct_estado='1' WHERE IDCita = '$_GET[id]'");
		$mensaje = "Gracias por Confirmarnos tu Cita.";
	} else {
		$mensaje = "La Cita fue Cancelada.";
	}
?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Confirmación Cita</title>
	</head>
	<style type="text/css">
		.contenedor {
			width: 50%;
			display: flex;
			flex-direction: column;
			text-align: center;
			font-family: Helvetica, Arial, sans-serif;
			border: 2px solid <?php echo $clinicaRow['cl_colorPrimario'] ?>;
			border-radius: 3px;
			margin: 0 auto;
		}
		.txt {
			margin-top: 25px;
			font-size: 25px;
			padding: 20px;
		}
		.img {
			margin-top: 15px;
			padding: 15px;
			font-size: 24px;
			text-align: center;
		}
		.img img {
			max-width: 100%;
			max-height: 300px;
		}
		@media (max-width: 768px){
			.contenedor { width: 100%; }
		}
	</style>
	<body>

		<div class="contenedor">
			<div class="img"><?php echo $titulo ?></div>
			<div class="txt"><?php echo $mensaje ?></div>
		</div>

	<script type="text/javascript"> 
	     setTimeout("window.close()", 6000);
	</script>
	</body>
	</html>
<?php } else { header('location:./'); } ?>