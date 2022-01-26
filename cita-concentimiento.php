<?php include'config.php'; include'encrypt.php';

$mesesArray = array('0' => '0', 
		'1' => 'Enero', 
		'2' => 'Febrero', 
		'3' => 'Marzo', 
		'4' => 'Abril', 
		'5' => 'Mayo', 
		'6' => 'Junio', 
		'7' => 'Julio', 
		'8' => 'Agosto', 
		'9' => 'Septiembre', 
		'10' => 'Octubre', 
		'11' => 'Noviembre', 
		'12' => 'Diciembre'
	);

$citaID = $_POST['citaID'];
$extra = $_POST['extra'];
$site = $_POST['site'];
$div = $_POST['div'];
$action = $_POST['action'];

$citaSql = $con->query("SELECT * FROM citas AS ct
	INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
	INNER JOIN doctores AS dc ON ct.ct_idDoctor = dc.IDDoctor
	WHERE IDCita = '$citaID'")->fetch_assoc();

$concentimientoSql = $con->query("SELECT * FROM concentimientos AS ctm 
    INNER JOIN mis_concentimientos AS mct ON ctm.ctm_consentimiento = mct.IDMiConcentimiento
    WHERE ctm.ctm_idCita = '$citaID'")->fetch_assoc();

$tipoDocumento = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$citaSql[pc_idIdentificacion]'")->fetch_assoc();
if($tipoDocumento){

	if($citaSql['pc_idSexo']==1){
		$identificacionPaciente = 'identificado con ';
	} else {
		$identificacionPaciente = 'identificada con ';
	}

	$identificacionPaciente .= '<b>'.$tipoDocumento['ti_nombre'].' '.$citaSql['pc_identificacion'].'</b>';
} else {
	$identificacionPaciente .= "identificado como aparece al pie de mi firma";
}
?>

<style type="text/css">
.table_concentimiento li {
    margin-left: 30px;
    padding: 5px 0;
}

.table_concentimiento td {
    padding: 5px 0;
}
</style>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 class="modal-title">Concentimiento Informado</h4>
</div>
<div class="form">

    <div class="modal-body divForm">

        <div id="msj-cita-concentimiento" class="contenedorAlerta"></div>

        <?php if(!$concentimientoSql['IDConcentimiento']){ ?>
        <select name="concentimientoSelect" id="concentimientoSelect" class="formulario__modal__input top"
            data-label="Consentimiento" required>
            <option selected hidden value="">-- Seleccionar --</option>
            <?php $misConcentimientoSql = $con->query("SELECT * FROM mis_concentimientos WHERE mct_idClinica = '$sessionClinica' ORDER BY mct_nombre");
				while($misConcentimientoRow = $misConcentimientoSql->fetch_assoc()){
				    echo "<option value=".$misConcentimientoRow['IDMiConcentimiento'].">".$misConcentimientoRow['mct_nombre']."</option>";	
				}
			?>
        </select>
        <?php } ?>


        <table class="table_concentimiento">
            <tr>
                <td>
                    Yo, <b><?= $citaSql['pc_nombres'] ?></b> <?= $identificacionPaciente ?>, declaro que he sido
                    informado por el odontólogo Dr. <b><?= $citaSql['dc_nombres'] ?></b> después de haber realizado el
                    examen diagnóstico de los procedimientos odontológicos que se van a realizar, que me ha explicado en
                    forma suficiente y adecuada en que consiste el tratamiento y cuáles son sus consecuencias, ventajas,
                    riesgos posibles complicaciones o molestias que puedan presentarse, ya que no puede asegurarse que
                    el tratamiento tenga éxito en todos los casos.
                </td>
            </tr>
            <tr>
                <td>Declaro que se sido informado por el doctor abajo firmante de lo siguiente:</td>
            </tr>
            <tr>
                <td id="cargaConcentimiento">
                    <?php if($concentimientoSql['IDConcentimiento']){
                        echo $concentimientoSql['mct_contenido'];
                    } else {
                        echo '[Seleccione un consentimiento para completarlo]';
                    }?>
                </td>
            </tr>
            <tr>
                <td>Entiendo por lo tanto que en el curso del tratamiento pueden presentarse situaciones especiales e
                    imprevistas y procedimientos adicionales. Por ello manifiesto que estoy satisfecho con la
                    información recibida y comprendo el alcance y los riesgos del tratamiento y en tales condiciones
                    consiento que se me practique el tratamiento que me ha explicado el odontólogo en la IPS
                    <b><?= strtoupper($clinicaRow['cl_nombre']) ?></b> a los <?= date('d') ?> días del mes de
                    <?= $mesesArray[date('n')] ?> de <?= date('Y') ?>.
                </td>
            </tr>
            <tr>
                <td>El suscrito odontólogo deja constancia que ha explicado la naturaleza, propósito, ventajas, riesgos,
                    y alternativas de tratamiento y que ha respondido las preguntas formuladas por el paciente o persona
                    responsable de este.</td>
            </tr>
        </table>

        <div class="containerFirmas">
            <div class="content_signature">
                <?php if($concentimientoSql['IDConcentimiento']){ ?>

                <?php if(!empty($concentimientoSql['ctm_firmaPaciente'])){ ?>
                <img src="<?php echo $concentimientoSql['ctm_firmaPaciente'] ?>">
                <?php } ?>

                <div class="option_signature_pad">
                    Firma Paciente
                </div>

                <?php } else { ?>

                <div id="firma_paciente_image" class="ocultar"></div>
                <canvas id="signature_pad_concent_paciente" class="signature_pad" width=400 height=200></canvas>

                <div class="option_signature_pad">
                    Firma Paciente
                    <span id="clear_signature_concent_paciente" title="Limpiar"><i class="fa fa-times"></i></span>
                </div>

                <div class="option_signature_botton boton" onclick="$('#firma_file_paciente').click()">Cargar imágen</div>

                <input type="hidden" name="firma_concent_paciente" id="firma_concent_paciente">
                <input type="file" accept="image/png, .jpeg, .jpg, .bmp" style="display:none" id="firma_file_paciente">

                <?php } ?>

            </div>

            <div class="content_signature">
                <?php if($concentimientoSql['IDConcentimiento']){ ?>

                <?php if(!empty($concentimientoSql['ctm_firmaDoctor'])){ ?>
                <img src="<?php echo $concentimientoSql['ctm_firmaDoctor'] ?>">
                <?php } ?>

                <div class="option_signature_pad">
                    Firma Doctor
                </div>

                <?php } else { 
                    if( !empty($citaSql['dc_firma']) ){
                ?>
                    <img src="<?php echo $citaSql['dc_firma'] ?>">

                    <div class="option_signature_pad">
                        Firma Doctor
                    </div>
                    <input type="hidden" name="firma_concent_usuario" id="firma_concent_usuario" value="<?php echo $citaSql['dc_firma'] ?>">
                <?php } else { ?>

                    <div id="firma_usuario_image" class="ocultar"></div>
                    <canvas id="signature_pad_concent_usuario" class="signature_pad" width=400 height=200></canvas>

                    <div class="option_signature_pad">
                        Firma Doctor
                        <span id="clear_signature_concent_usuario" title="Limpiar"><i class="fa fa-times"></i></span>
                    </div>                

                    <div class="option_signature_botton boton" onclick="$('#firma_file_usuario').click()">Cargar imágen</div>

                    <input type="hidden" name="firma_concent_usuario" id="firma_concent_usuario">
                    <input type="file" accept="image/png, .jpeg, .jpg, .bmp" style="display:none" id="firma_file_usuario">

                <?php } } ?>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <?php if(!$concentimientoSql['IDConcentimiento']){ ?>
        <input type="hidden" id="ct_info_citaID" value="<?= $citaID ?>">
        <input type="hidden" id="ct_info_extra" value="<?= $extra ?>">
        <input type="hidden" id="ct_info_site" value="<?= $site ?>">
        <input type="hidden" id="ct_info_div" value="<?= $div ?>">

        <a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
        <a class="boton boton-primario btn_cita_concentimiento_guardar" data-site="<?= $_POST['site'] ?>">Guardar</a>
        <?php } else { ?>
        <a class="boton boton-secundario" data-dismiss="modal">Cerrar</a>
        <?php } ?>
    </div>

</div>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
<?php if(!$concentimientoSql['IDConcentimiento']){ ?>

// SELECCIONAR CONCENTIMIENTO
var misConsentimientos = $("#concentimientoSelect");
misConsentimientos.change(function() {
    $.ajax({
        url: "consentimientos-list.php",
        method: "POST",
        data: {
            id: misConsentimientos.val(),
        },
        cache: false,
        success: function(data) {
            $('#cargaConcentimiento').html(data);
        }
    });
});

// FIRMA PACIENTE
var signaturePad_concent_paciente = new SignaturePad(document.querySelector('#signature_pad_concent_paciente'), {
    backgroundColor: 'rgba(255, 255, 255, 0)',
    penColor: 'rgb(0, 0, 0)'
});

var imageLoaderPaciente = document.getElementById('firma_file_paciente');
imageLoaderPaciente.addEventListener('change', handleImagePaciente, false);
function handleImagePaciente(e) {
	var reader = new FileReader();
	reader.onload = function (event) {
        $('#firma_paciente_image').html( '<img src="'+event.target.result+'"/>' );
        $('#firma_concent_paciente').val(event.target.result);
    }
	reader.readAsDataURL(e.target.files[0]);
    $('#firma_paciente_image').removeClass('ocultar');
    $('#signature_pad_concent_paciente').addClass('ocultar');
};

$(document).on('click', '#clear_signature_concent_paciente', function() {
    signaturePad_concent_paciente.clear();
    $('#firma_concent_paciente').val(null);
    $('#firma_paciente_image').addClass('ocultar');
    $('#signature_pad_concent_paciente').removeClass('ocultar');
});

$(document).on('mouseup', '#signature_pad_concent_paciente', function() {
    $('#firma_concent_paciente').val(document.querySelector('#signature_pad_concent_paciente').toDataURL());
});

// FIRMA USUARIO
var signaturePad_concent_usuario = new SignaturePad(document.querySelector('#signature_pad_concent_usuario'), {
    backgroundColor: 'rgba(255, 255, 255, 0)',
    penColor: 'rgb(0, 0, 0)'
});

var imageLoaderDoctor = document.getElementById('firma_file_usuario');
imageLoaderDoctor.addEventListener('change', handleImageDoctor, false);
function handleImageDoctor(e) {
	var reader = new FileReader();
	reader.onload = function (event) {
        $('#firma_usuario_image').html( '<img src="'+event.target.result+'"/>' );
        $('#firma_concent_usuario').val(event.target.result);
    }
	reader.readAsDataURL(e.target.files[0]);
    $('#firma_usuario_image').removeClass('ocultar');
    $('#signature_pad_concent_usuario').addClass('ocultar');
};

$(document).on('click', '#clear_signature_concent_usuario', function() {
    signaturePad_concent_usuario.clear();
    $('#firma_concent_usuario').val(null);
    $('#firma_usuario_image').addClass('ocultar');
    $('#signature_pad_concent_usuario').removeClass('ocultar');
});

$(document).on('mouseup', '#signature_pad_concent_usuario', function() {
    $('#firma_concent_usuario').val(document.querySelector('#signature_pad_concent_usuario').toDataURL());
});

<?php } ?>
</script>