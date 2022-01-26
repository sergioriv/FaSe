<?php include'config.php';

$id = $_POST['id'];
$etiqueta = $con->query("SELECT pc_nombres, pc_etiqueta FROM pacientes WHERE IDPaciente = '$id'")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><span class="icon-etiqueta-title"><?= $iconW ?></span> Paciente: <?= $etiqueta['pc_nombres'] ?></h4>
</div>
<div class="modal-body">
	<span style="font-size: 13px;"><?= nl2br(trim($etiqueta['pc_etiqueta'])) ?></span>
</div>