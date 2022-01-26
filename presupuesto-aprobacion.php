<?php include'config.php';

$presupuestoID = $_POST['presupuestoID'];
$query = $con->query("UPDATE presupuestos SET pp_aprobado=1, pp_fechaAprobacion='$fechaHoy', pp_fechaAprobacionInt='$fechaEvolucionCita' WHERE IDPresupuesto='$presupuestoID'");

?>