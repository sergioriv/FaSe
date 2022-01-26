<?php include'config.php';

$citaID = $_POST['citaID'];
$extra = $_POST['extra'];
$site = $_POST['site'];
$div = $_POST['div'];
$action = $_POST['action'];

if(!empty($extra)){
	$send_id = ", id:$extra";
}else{ $send_id = ''; }

$estado = 0;
if($action==0){ $estado = 2; }
else if($action==1){ $estado = 1; }


$con->query("UPDATE citas SET ct_estado = '$estado' WHERE IDCita = '$citaID'");

$ajax_return = "#".$div;

if('ct_pendientes' == $site){ 

	$ajax_url = "get/citasPendientesData.php";
}
if('ct_sinevolucion' == $site){ 

	$ajax_url = "get/citasSinEvoluData.php";
}
if('ct_historico' == $site){ 

	$ajax_url = "get/citasHistoricoData.php";
}
if('pc_citas' == $site){ 

	$ajax_url = "get/pcCitasData.php";
}
if('dc_citas' == $site){ 

	$ajax_url = "get/hsDoctorCitasData.php";
}

?>


<script type="text/javascript">
				$.ajax({
		        	url: '<?= $ajax_url ?>',
			        method:"POST",
		            data:{page:0, id:'<?= $extra ?>'}, 
			        success:function(data){
						$('<?= $ajax_return ?>').html(data);
						$('#consultoriosModal').modal('hide');
					}
			    });
	
</script>