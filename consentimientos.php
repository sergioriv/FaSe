<?php include'config.php'; include'pagination-modal-params.php';

$consentimientosQuery = "SELECT * FROM mis_concentimientos WHERE mct_idClinica='$sessionClinica' ORDER BY mct_nombre";

$rowCount = $con->query($consentimientosQuery)->num_rows;
	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationConsentimientos'
	);
    $pagination =  new Pagination($pagConfig);

$consentimientosSql = $con->query($consentimientosQuery." LIMIT $numeroResultados");

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include'header.php';
			include'footer.php'; ?>
</head>

<body>
    <div class="contenedorPrincipal">

        <div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

        <div class="tituloBuscador">
            <div class="titulo tituloSecundario">Consentimientos<a
                    class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Consentimiento</a></div>
            <span>
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="text" id="searchConsentimiento" list="consentimientos" class="buscador"
                    placeholder="Buscar . . ." onkeyup="paginationConsentimientos();">
            </span>
        </div>

        <div id="showResults">
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Creador</th>
                        <th>Nombre</th>
                        <th>Fecha creación</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($consentimientoRow = $consentimientosSql->fetch_assoc()){

						$creadorConsentimientoSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$consentimientoRow[mct_idUsuario]'")->fetch_assoc();

						$IDusuarioConsentimiento = $creadorConsentimientoSql['us_id'];
						$nombreUsuarioConsentimiento = '';
					    if($creadorConsentimientoSql['us_idRol']==1){
					   		$usuarioConsentimiento = $con->query("SELECT cl_nombre FROM clinicas WHERE IDClinica='$IDusuarioConsentimiento'")->fetch_assoc();
					   		$nombreUsuarioConsentimiento = $usuarioConsentimiento['cl_nombre'];

					    } elseif($creadorConsentimientoSql['us_idRol']==2){
					   		$usuarioConsentimiento = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal='$IDusuarioConsentimiento'")->fetch_assoc();
					   		$nombreUsuarioConsentimiento = $usuarioConsentimiento['sc_nombre'];

					    } elseif($creadorConsentimientoSql['us_idRol']==3){
					   		$usuarioConsentimiento = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor='$IDusuarioConsentimiento'")->fetch_assoc();
					   		$nombreUsuarioConsentimiento = $usuarioConsentimiento['dc_nombres'];
					    }
					?>
                    <tr>
                        <td><?php echo $nombreUsuarioConsentimiento ?></td>
                        <td><?php echo $consentimientoRow['mct_nombre']; ?></td>
                        <td><?php echo $consentimientoRow['mct_fechaCreacion']; ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $consentimientoRow['IDMiConcentimiento'] ?>"
                                class="consultorioEditar"><?php echo $iconoEditar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $pagination->createLinks(); ?>
        </div>
    </div>

    <div id="consultoriosModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content" id="consultoriosDetails"></div>
        </div>
    </div>

    <script type="text/javascript">
    function paginationConsentimientos(page_num) {
        page_num = page_num ? page_num : 0;
        var busqueda = $('#searchConsentimiento').val();
        $.ajax({
            type: 'POST',
            url: 'get/consentimientosData.php',
            data: 'page=' + page_num + '&busqueda=' + busqueda,
            success: function(html) {
                $('#showResults').html(html);
            }
        });
    }

    $(document).on('click', '.consultorioNuevo', function() {
        $.ajax({
            url: "consentimiento.php",
            method: "POST",
            success: function(data) {
                $('#consultoriosDetails').html(data);
                $('#consultoriosModal').modal('show');
            }
        });
    });
    $(document).on('click', '.consultorioEditar', function() {
        var consultoriosId = $(this).attr("id");
        if (consultoriosId != '') {
            $.ajax({
                url: "consentimiento.php",
                method: "POST",
                data: {
                    id: consultoriosId
                },
                success: function(data) {
                    $('#consultoriosDetails').html(data);
                    $('#consultoriosModal').modal('show');
                }
            });
        }
    });
    </script>
</body>

</html>