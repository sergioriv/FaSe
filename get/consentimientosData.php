<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $consentimientosQuery = "SELECT * FROM mis_concentimientos WHERE mct_idClinica='$sessionClinica' AND (mct_nombre LIKE '%$busqueda%') ORDER BY mct_nombre ";

    $rowCount = $con->query($consentimientosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationConsentimientos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $consentimientosSql = $con->query($consentimientosQuery." LIMIT $start,$numeroResultados");
    
?>
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
<?php
}
?>