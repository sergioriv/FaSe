<?php include'config.php'; $id = $_POST['id'];
$doctorSql = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$id'");
$doctorRow = $doctorSql->fetch_assoc();

if($doctorRow['dc_genero']=='M'){ $tituloModal = "Doctor "; }
elseif($doctorRow['dc_genero']=='F'){ $tituloModal = "Doctora "; }

  $tipoIdentiSql = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$doctorRow[dc_idIdentificacion]'");
  $tipoIdentiRow = $tipoIdentiSql->fetch_assoc();

  $ciudadSql = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$doctorRow[dc_idCiudad]'");
  $ciudadRow = $ciudadSql->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>
  <div class="consultorioView">
    <div class="viewImg">
		  <?php
			   if($doctorRow['dc_foto']!=''){ echo "<img src='$doctorRow[dc_foto]'>"; }
		   	 else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
		  ?>
	  </div>
  	<h4 class="modal-title"><?php echo $tituloModal.$doctorRow['dc_nombres'] ?></h4>
    <h4 class="modal-title"><?php echo $tipoIdentiRow['ti_nombre'].' '.$doctorRow['dc_identificacion'] ?></h4>
    <p>&nbsp</p>
  	<h4 class="modal-title"><?php echo $doctorRow['dc_telefonoFijo'] ?></h4>
  	<h4 class="modal-title"><?php echo $doctorRow['dc_telefonoCelular'] ?></h4>
    <h4 class="modal-title"><?php echo $ciudadRow['cd_nombre'].' '.$doctorRow['dc_direccion'] ?></h4>
  	<h4 class="modal-title"><?php echo $doctorRow['dc_correo'] ?></h4>
  </div>
  
</div>