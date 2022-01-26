<?php include'config.php'; $id = $_GET['id'];
$numSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$id'");
$numFotos = $numSql->num_rows;
$paciente = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$id'")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
	<?php include'header.php'; ?>
</head>	
<style type="text/css">
    
</style>
<body>

    <div class="contenedorPrincipal">

        <div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

        <div class="titulo tituloSecundario">
            Registro Fotográfico: <?php echo $paciente['pc_nombres'] ?>
            <a data-toggle="modal" data-target="#src_img_upload" id="<?php echo $id ?>" class="consultorioNuevo"><?php echo $iconoNuevo ?>Subir Imágenes</a>
        </div>
    	

    	<div id="image_gallery">
        <!-- Slider -->
        <div class="contenedor-galeria">
            <div class="" id="slider-thumbs">
                <!-- Bottom switcher of slider -->
                <div class="hide-bullets">
                <?php $i = 0; $imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$id' ORDER BY IDPacFoto DESC");
                	while($imagenesRow = $imagenesSql->fetch_assoc()){
                        $imagenesID = "carousel-selector-".$i;
                ?>
                    <div class="miniatura">                        
                        <img src="<?php echo $imagenesRow['pf_foto'] ?>">
                        <div class="optionImage">
                          	<div class="ver" id="<?php echo $imagenesID ?>"><i class="fa fa-eye"></i></div>
                          	<div id="<?php echo $imagenesRow['IDPacFoto'] ?>" t="pacientefotos" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></div>
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
                                <?php $primeroSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$id' ORDER BY IDPacFoto DESC");
                                	$primeroRow = $primeroSql->fetch_assoc();
                                	$i = 0;
                                	$imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$id' ORDER BY IDPacFoto DESC");
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
<?php if($numFotos>0){ ?>
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
        <!--/Slider-->
        </div>
    </div>
<!--
    <div id="src_img_upload" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
			</div>
		</div>
	</div>
-->
	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>
<?php include'footer.php'; ?>

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

<script type="text/javascript">
        $(document).on('click', '.consultorioNuevo', function(){  
            var consultoriosId = $(this).attr("id");
            if(consultoriosId != '')
            {  
                $.ajax({
                    url:"foto.php",  
                    method:"POST",  
                    data:{id:consultoriosId},  
                    success:function(data){  
                        $('#consultoriosDetails').html(data);  
                        $('#consultoriosModal').modal('show');  
                    }
                });  
            }            
        });

        $(document).on('click', '.consultorioEliminar', function(){  
            var consultoriosId = $(this).attr("id");
            var consultoriosT = $(this).attr("t");
            if(consultoriosId != '')
            {  
                $.ajax({
                    url:"eliminar.php",  
                    method:"POST",  
                    data:{id:consultoriosId,t:consultoriosT},  
                    success:function(data){  
                        $('#consultoriosDetails').html(data);  
                        $('#consultoriosModal').modal('show');  
                    }
                });  
            }            
        }); 

</script>
</body>
</html>