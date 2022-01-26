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

	$citaID = $_GET['id'];

	$clinicaSql = $con->query("SELECT * FROM citas, clinicas WHERE citas.ct_idClinica = clinicas.IDClinica AND citas.IDCita = '$citaID'");
	$clinicaRow = $clinicaSql->fetch_assoc();

	if($clinicaRow['cl_logo']!=""){ $titulo = "<img src='$ruta$clinicaRow[cl_logo]'>"; } else { $titulo = $clinicaRow['cl_nombre']; }

?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Cancelación Cita</title>
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
		.contenedor-btn { padding: 30px 0px; }
		.button {
			border: 0;
			border-radius: 3px;
			padding: 10px 20px;
			font-size: 16px;
			margin: 20px;
			margin-bottom: 40px;
			color: white;
			text-align: center;
			outline: 0;
			cursor: pointer;
			-webkit-box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.15);
			-moz-box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.15);
			box-shadow: 2px 2px 2px 0px rgba(0,0,0,0.7);
		}
		.confirmar { background: <?php echo $clinicaRow['cl_colorPrimario'] ?>; }
		.cerrar {
			text-decoration: none;
			background:<?php echo $clinicaRow['cl_colorSecundario'] ?>;
		}
		@media (max-width: 768px){
			.contenedor { width: 100%; }
		}
	</style>
	<body>
		<div class="contenedor">
			<div class="img"><?php echo $titulo ?></div>
			<div id="info" class="txt">

				<?php
					if($clinicaRow['ct_estado']==2){
						echo "La Cita ya a sido Cancelada.";
				?>
						<script type="text/javascript"> 
						     setTimeout("window.close()", 4000);
						</script>
				<?php
					} else {
				?>
					¿Estás segúro de cancelar la Cita <strong><?php echo $clinicaRow['ct_anoCita'].'/'.$clinicaRow['ct_mesCita'].'/'.$clinicaRow['ct_diaCita'].' '.$clinicaRow['ct_horaCita']; ?></strong>?
					<div class="contenedor-btn">
						<a id="cancelacionBtn" class="button confirmar">Cancelar Cita</a>
						<a onclick="javascript:window.close();" class="button cerrar">Cerrar</a>
					</div>
				<?php } ?>

			</div>

		</div>

	<script src="js/jquery-2-2-0.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).on('click', '#cancelacionBtn', function(){  

			var citaID = <?php echo $citaID ?>;

			
			   	$.ajax({
			       	url:"cancelacion-guardar.php",  
			        method:"POST",
			        data:{citaID:citaID}, 
			        success:function(data){  
						$('#info').html(data);
					}
			    });
		   	
		});
	</script>

	</body>
	</html>
<?php } else { header('location:./'); } ?>