<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $conveniosQuery = "SELECT * FROM convenios WHERE cnv_idClinica='$sessionClinica' AND cnv_estado='1' AND (cnv_nombre LIKE '%$busqueda%') ORDER BY cnv_nombre ";

    $rowCount = $con->query($conveniosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationConvenios'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $conveniosSql = $con->query($conveniosQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Creador</th>
                        <th>Nombre</th>
                        <th>%</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($conveniosRow = $conveniosSql->fetch_assoc()){

                        $creadorConvenioSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$conveniosRow[cnv_idUsuario]'")->fetch_assoc();

                        $IDusuarioConvenio = $creadorConvenioSql['us_id'];
                        $nombreUsuarioConvenio = '';
                        if($creadorConvenioSql['us_idRol']==1){
                            $usuarioConvenio = $con->query("SELECT cl_nombre FROM clinicas WHERE IDClinica='$IDusuarioConvenio'")->fetch_assoc();
                            $nombreUsuarioConvenio = $usuarioConvenio['cl_nombre'];

                        } elseif($creadorConvenioSql['us_idRol']==2){
                            $usuarioConvenio = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal='$IDusuarioConvenio'")->fetch_assoc();
                            $nombreUsuarioConvenio = $usuarioConvenio['sc_nombre'];

                        } elseif($creadorConvenioSql['us_idRol']==3){
                            $usuarioConvenio = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor='$IDusuarioConvenio'")->fetch_assoc();
                            $nombreUsuarioConvenio = $usuarioConvenio['dc_nombres'];
                        }
                    ?>
                    <tr>
                        <td><?php echo $nombreUsuarioConvenio ?></td>
                        <td><a id="<?php echo $conveniosRow['IDConvenio'] ?>" class="consultorioEditar"><?php echo $conveniosRow['cnv_nombre']; ?></a></td>
                        <td align="center"><?php echo $conveniosRow['cnv_descuento']; ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $conveniosRow['IDConvenio'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
                            <a id="<?php echo $conveniosRow['IDConvenio'] ?>" t="convenio" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>