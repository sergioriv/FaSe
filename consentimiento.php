<?php include'config.php'; $id = $_POST['id'];
$consentimientoRow = $con->query("SELECT * FROM mis_concentimientos WHERE IDMiConcentimiento = '$id'")->fetch_assoc();
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4 class="modal-title">
        <?php if($id){ echo 'Consentimiento: '.$consentimientoRow['mct_nombre']; }else{echo'Nuevo consentimiento';} ?>
    </h4>
</div>
<form class="form" id="formConsentimiento" method="post" action="consentimiento-guardar.php">
    <div class="modal-body">
        <div class="divForm">
            <div class="container1Part">
                <input type="text" name="nombre" value="<?= $consentimientoRow['mct_nombre'] ?>"
                    class="formulario__modal__input" data-label="Nombre" required>
            </div>
            <div class="container1Part">
                <input type="text" name="contenido" id="contenidoRichText" value="<?= $consentimientoRow['mct_contenido'] ?>" required>
            </div>
        </div>
    </div>

    <div class="modal-footer">

        <input type="hidden" name="id" value="<?= $id ?>">
        <a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
        <button class="boton boton-primario">Guardar</button>

    </div>
</form>

<script src="js/jquery.richtext.min.js"></script>
<link rel="stylesheet" href="css/richtext.min.css">
<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
$('#contenidoRichText').richText({
    leftAlign: false,
    centerAlign: false,
    rightAlign: false,
    justify: false,
    heading: false,
    fonts: false,
    fontColor: false,
    fontSize: false,
    imageUpload: false,
    fileUpload: false,
    videoEmbed: false,
    urls: false,
    table: false,
    removeStyles: false,
    code: false,
    youtubeCookies: false,
});
validar('#formConsentimiento');
</script>