<?php include'config.php';

$citaID = $_POST['citaID'];
$accion = $_POST['accion'];

if(!empty($_POST['date'])){
	$date = $_POST['date'];
} else {
	$date = date('Y-m-d');
}

$estado = 0;
if($accion==0){ $estado = 2; }
else if($accion==1){ $estado = 1; }

$con->query("UPDATE citas SET ct_estado = '$estado' WHERE IDCita = '$citaID'");

?>
<script type="text/javascript">
				$.ajax({
		        	url: "extras/dashComparativo.php",
			        method:"POST",
		            data:{fecha:'<?= $date ?>'}, 
			        success:function(data){
						$('#unidadComparativoCont').html(data);
						$('#consultoriosModal').modal('hide');
					}
			    });
	
</script>