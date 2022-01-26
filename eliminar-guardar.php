<?php include'config.php';

$id = $_POST['id'];
$tabla = $_POST['t'];
$pacienteID = $_POST['pc'];

if($tabla=='pacientefotos'){
	$foto = $con->query("SELECT * FROM pacientefotos WHERE IDPacFoto = '$id'")->fetch_assoc();
	$query = $con->query("DELETE FROM pacientefotos WHERE IDPacFoto = '$id'");

	if($query){
		$delete = $foto['pf_foto'];
		unlink($delete);
		echo '
		<div class="contenedorAlerta">
			<input type="radio" id="alertError">
			<label class="alerta error" for="alertError">
				<div>Se eliminó la imágen correctamente.</div>
				<div class="close">&times;</div>
			</label>
		</div>';
	} else {
		echo '
		<div class="contenedorAlerta">
			<input type="radio" id="alertError">
			<label class="alerta error" for="alertError">
				<div>Error al eliminar la imágen, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div>
				<div class="close">&times;</div>
			</label>
		</div>';
	}

	$numFotosPaciente = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$pacienteID'")->num_rows;
?>
	<div class="contenedor-galeria">
	    <div class="" id="slider-thumbs">
	    <!-- Bottom switcher of slider -->
	        <div class="hide-bullets">
	            <?php $i = 0; $imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$pacienteID' ORDER BY IDPacFoto DESC");
	               	while($imagenesRow = $imagenesSql->fetch_assoc()){
	                    $imagenesID = "carousel-selector-".$i;
	            ?>
	            <div class="miniatura">                        
	                <img src="<?php echo $imagenesRow['pf_foto'] ?>">
	                <div class="optionImage">
	                   	<div title="Ver" class="ver" id="<?php echo $imagenesID ?>"><i class="fa fa-eye"></i></div>
	                   	<div title="Eliminar" id="<?php echo $imagenesRow['IDPacFoto'] ?>" t="pacientefotos" pc="<?php echo $pacienteID ?>" class="consultorioEliminarFoto eliminar"><?php echo $iconoEliminar ?></div>
	                </div>
                </div>
	            <?php $i++; } ?>
	        </div>
        </div>
        <div class="">
            <div class="" id="slider">
            <!-- Top part of the slider -->
                <div class="">
                    <div class="" id="carousel-bounding-box">
                        <div class="carousel slide" id="myCarousel">
                        <!-- Carousel items -->
                            <div class="carousel-inner">
	                            <?php $primeroSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$pacienteID' ORDER BY IDPacFoto DESC");
                                	$primeroRow = $primeroSql->fetch_assoc();
                                	$i = 0;
                                	$imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$pacienteID' ORDER BY IDPacFoto DESC");
				                	while($imagenesRow = $imagenesSql->fetch_assoc()){
				                		if($primeroRow['IDPacFoto']==$imagenesRow['IDPacFoto']){
				                			$active = 'active';
				                		} else {
				                			$active = '';
				                		}
				                ?>
		                                <div class="<?php echo $active ?> item" data-slide-number="<?php echo $i ?>">
		                                    <img src="<?php echo $imagenesRow['pf_foto'] ?>">
		                                </div>
		                                <?php $i++; } ?>

                            </div>
							<?php if($numFotosPaciente>0){ ?>
                            <!-- Carousel nav -->
	                                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
	                                    <i class="fa fa-angle-left"></i>
	                                </a>
	                                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
	                                    <i class="fa fa-angle-right"></i>
	                                </a>
							<?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
            jQuery(document).ready(function($) {
         
                $('#myCarousel').carousel({
                        interval: 10000
                });
         
                //Handles the carousel thumbnails
                $('[id^=carousel-selector-]').click(function () {
                var id_selector = $(this).attr("id");
                try {
                    var id = /-(\d+)$/.exec(id_selector)[1];
                    console.log(id_selector, id);
                    jQuery('#myCarousel').carousel(parseInt(id));
                } catch (e) {
                    console.log('Regex failed!', e);
                }
            });
                // When the carousel slides, auto update the text
                $('#myCarousel').on('slid.bs.carousel', function (e) {
                         var id = $('.item.active').data('slide-number');
                        $('#carousel-text').html($('#slide-content-'+id).html());
                });
        });
        </script>
<?php } ?>