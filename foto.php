<?php include'config.php'; $id = $_POST['id'];

$limiteRegFotografico = $clinicaRow['cl_cantImgPaciente'];

$numeroFotos = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$id'")->num_rows;
$validaCantidad = $limiteRegFotografico - $numeroFotos;
if($validaCantidad>1){
	$mensajeVal = "Solo se permite subir $validaCantidad imágenes más";
	$tituloImg = $validaCantidad." Imágenes restantes.";
} else {
	$mensajeVal = "Solo se permite subir una imágen más.";
	$tituloImg = "Una Imágen restante.";
}
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Subir imágen</h4>
</div>
<form class="form" method="POST" id="upload_form">
	<div class="modal-body">
		<div class="divForm">
			<div class="titulo tituloSecundario"><?php echo $tituloImg ?></div><p>&nbsp</p>
        <div class="titulo tituloSecundario" id="upload-msj"></div><p>&nbsp</p>
            <input type="text" name="titulo" class="formulario__modal__input" data-label="Título para la imágen">
            <textarea name="descripcion" rows="5" class="formulario__modal__input" data-label="Descripción"></textarea>
            <?php if($validaCantidad>0){ ?>
			        <input type="file" accept="image/png, .jpeg, .jpg, .bmp" name="images[]" id="img_select" class='form-control'>
			      <?php } else { echo "Superó el límite máximo permitido de imágenes."; } ?>
            <input type="hidden" name="pacienteID" value="<?php echo $id ?>">
            <progress id="progressCarga" value="0" min="0" max="100"></progress>
		</div>
	</div>
</form>
<script src="js/label-modal.js"></script>
<script>
$('#progressCarga').hide();
 $('#img_select').change(function(){
    $('#upload_form').submit();
 });

 $('#upload_form').on('submit', function(e){
 e.preventDefault();
    var fileUpload = $("#img_select");
    if (parseInt(fileUpload.get(0).files.length)><?php echo $validaCantidad ?>){
        $('#upload-msj').html("<?php echo $mensajeVal ?>");
    } else { 
                $('#progressCarga').show();
     $.ajax({

         xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
              if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                percentComplete = parseInt(percentComplete * 100);
                console.log(percentComplete);
                $('#progressCarga').val(percentComplete);
              }
            }, false);

            return xhr;
         },
         url : "foto-upload.php",
         method : "POST",
         data: new FormData(this),
         contentType:false,
         processData:false,
         success: function(data){
         $('#img_select').val('');  
                $('#src_img_upload').modal('hide');  
                $('#image_gallery').html(data); 
         }
     });
    }
 });
 

 
</script>