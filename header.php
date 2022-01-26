<?php
$archivo_actual = basename($_SERVER['PHP_SELF']);
$archivo_actual = str_replace('.php', '', $archivo_actual);
$archivo_actual = str_replace('-', ' ', $archivo_actual);
?>
<title><?php echo ucwords($archivo_actual) ?> | FaSe</title>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="icon" type="image/png" href="img/favicon.ico"/>
<link rel="stylesheet" type="text/css" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/iconCita/style.css">
<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css" href="css/menu.css">
<link rel="stylesheet" type="text/css" href="css/modal-bootstrap.css">
<link rel="stylesheet" href="css/pagination.css">
<link href="css/pace-theme-flash.css" rel="stylesheet" />
<link href="css/select2.css" rel="stylesheet" />
<link rel="stylesheet" href="css/bootstrap-slider.css">

<script src="js/jquery-2-2-0.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!--<script type="text/javascript" src="js/validar.js"></script>-->
<script src="js/pace.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/i18n/es.js"></script>
<script type="text/javascript">$.fn.select2.defaults.set('language', 'es');</script>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<!--<script src="js/select2-405.js"></script>-->
<script src="js/jquery.validate.js"></script>
<script src="js/validacion.js"></script>
<script src="js/footer.js"></script>
<script src="js/bootstrap-slider.js"></script>

<!--
	* Administrador: 1
	* Sucursal: 2
	* Doctor: 3
	* Inventario: 4
	* Citas: 5
-->

<header class="contenedorMenu">
	<input type="checkbox" id="btn-menu">
   	<label for="btn-menu"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></label>
	<nav class="menuPrincipal">
		<ul>
			<li class="menuLogo"><img src="img/logo-menu.jpeg"></li>
      	<?php if($sessionRol==1){ ?><li class="menuIcon"><a href="dashboard"><i class="fa fa-dashboard"></i></a></li><?php } ?>
		<?php if($sessionRol==1||$sessionRol==2||$sessionRol==3||$sessionRol==5){ ?><li><a href="citas">Citas</a></li><?php } ?>
		<?php if($sessionRol==1||$sessionRol==2||$sessionRol==5){ ?><li><a href="pacientes">Pacientes</a></li><?php } ?>
		<?php if($sessionRol==1||$sessionRol==2){ ?><li><a href="doctores">Doctores</a></li><?php } ?>
		<?php if($sessionRol==1||$sessionRol==2){ ?><li><a href="materiales">Inventario</a></li><?php } ?>

		<!-- Menu Doctores -->
		<?php if($sessionRol==3){ ?>
			<li><a href="listas-auxiliares">Listas Auxiliares</a></li>
			<li><a class="consultorioUsuario" data-id="<?= $sessionRol ?>">Ver perfil</a></li>
		<?php } ?>


      	<?php if($sessionRol==1){ ?>
			<li class="menuConfig menuDown"><a>Administrar</a>
				<ul>
					<li><a class="consultorioReporteRips">Reporte RIPS</a></li>
					<li><a class="consultorioReporteCitas">Reporte citas</a></li>
					<li><a href="sirho">Sirho</a></li>
					<li><a href="flujo-caja">Flujo de Caja</a></li>
					<li><a href="sucursales">Sucursales</a></li>
					<li><a href="tratamientos">Tratamientos / Fases</a></li>
					<li><a href="especialidades">Especialidades</a></li>
					<li><a href="proveedores">Proveedores</a></li>
					<li><a href="vendedores">Vendedores</a></li>
					<li><a href="usuarios-extras">Usuarios Extras</a></li>
					<li><a href="listas-auxiliares">Listas Auxiliares</a></li>
					<li><a href="consentimientos">Consentimientos</a></li>
					<li><a href="convenios">Convenios</a></li>
					<li><a href="tipo-tareas">Tipos de tareas</a></li>
					<li><a class="consultorioEmpresa">Info. Empresa</a></li>
				</ul>
			</li>
		<?php } ?>
		<?php if($sessionRol==1||$sessionRol==2||$sessionRol==5){ ?>
			<li class="menuSearch">
				<span>
					<i class="fa fa-search" aria-hidden="true"></i>
					<input type="text" name="buscadorPrincipal" id="buscadorPrincipal" class="buscador buscadorMenu" placeholder="Nombre o documento del paciente">
				</span>
				<div class="mostrarBusqueda" id="mostrarBusqueda"></div>
			</li>
		<?php } ?>
			<li class="menuIcon menuDown"><a><i class="fa fa-user" aria-hidden="true"></i></a>
				<ul>
				<?php if($sessionRol==1||$sessionRol==2){ ?>
					<li><a class="consultorioUsuario" data-id="<?= $sessionRol ?>">
						<?php
						 if($_SESSION['concultoriosRol']==1){
							$userMenu = $con->query("SELECT IDClinica, cl_nombre FROM clinicas WHERE IDClinica = '$_SESSION[concultoriosUsuario]'")->fetch_assoc();
							echo strtoupper($userMenu['cl_nombre']);
						} if($_SESSION['concultoriosRol']==2){
							$userMenu = $con->query("SELECT IDSucursal, sc_nombre FROM sucursales WHERE IDSucursal = '$_SESSION[concultoriosUsuario]'")->fetch_assoc();
							echo strtoupper($userMenu['sc_nombre']);
						}/* if($_SESSION['concultoriosRol']==3){
							$userMenu = $con->query("SELECT IDDoctor, dc_nombres FROM doctores WHERE IDDoctor = '$_SESSION[concultoriosUsuario]'")->fetch_assoc();
							echo strtoupper($userMenu['dc_nombres']);
						} if($_SESSION['concultoriosRol']==4){
							$userMenu = $con->query("SELECT IDUserInventario, ui_nombres FROM usuariosinventario WHERE IDUserInventario = '$_SESSION[concultoriosUsuario]'")->fetch_assoc();
							echo strtoupper($userMenu['ui_nombres']);
						} if($_SESSION['concultoriosRol']==5){
							$userMenu = $con->query("SELECT IDUserCitas, uc_nombres FROM usuarioscitas WHERE IDUserCitas = '$_SESSION[concultoriosUsuario]'")->fetch_assoc();
							echo strtoupper($userMenu['uc_nombres']);
						}*/

						?>

					</a></li>
				<?php } ?>
				<?php if($sessionRol!=1){ ?><li><a class="consultorioCambioPassword">Cambiar Contraseña</a></li><?php } ?>
					<li><a href="cerrar-sesion">Cerrar Sesión</a></li>
					<li><a class="consultorioSugerencia">Enviar Sugerencia</a></li>
				</ul>
			</li>
		</ul>
	</nav>
</header>


<span id="FaSeRedireccion" style="display: none;"></span>
