<?php include'config.php';


$id = $_POST['id'];
$ev = $_POST['ev'];

$query = $con->query("SELECT ct_evolucionada FROM citas WHERE IDCita = $id")->fetch_assoc();

if( $query['ct_evolucionada'] == 1 ):
	$url_evolucion = 'cita-ver-evolucion.php';
else :
	$url_evolucion = 'cita-evolucion.php';
endif;
?>

	<script>
		$.ajax({
		        type: 'POST',
		        url: '<?= $url_evolucion ?>',
		        data:{id:"<?= $id ?>",ev:"<?= $ev ?>"},
		        success: function (html) {
		            $('#consultoriosDetails').html(html);
		        }
		    });
	</script>