<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $sucursalesQuery = "SELECT * FROM sucursales WHERE sc_idClinica='$sessionClinica' AND sc_estado='1' AND (sc_nombre LIKE '%$busqueda%' OR sc_telefonoFijo LIKE '%$busqueda%' OR sc_correo LIKE '%$busqueda%' OR sc_direccion LIKE '%$busqueda%') ORDER BY sc_nombre";

    $rowCount = $con->query($sucursalesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationSucursales'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $sucursalesSql = $con->query($sucursalesQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Sucursal</th>
                        <th>Unid.</th>
                        <th>Teléfono</th>
                        <th>Ciudad</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th>Horario</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($sucursalesRow = $sucursalesSql->fetch_assoc()){
                        $ciudadRow = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$sucursalesRow[sc_idCiudad]'")->fetch_assoc();

                        $cantidadUnidades = $con->query("SELECT COUNT(*) AS cantidad FROM unidadesodontologicas WHERE uo_idSucursal = '$sucursalesRow[IDSucursal]' ")->fetch_assoc();
                    ?>
                    <tr>
                        <td><a id="<?php echo $sucursalesRow['IDSucursal'] ?>" class="consultorioEditar"><?php echo $sucursalesRow['sc_nombre']; ?></a></td>
                        <td align="center"><?php echo $cantidadUnidades['cantidad']; ?></td>
                        <td><?php echo $sucursalesRow['sc_telefonoFijo']; ?></td>
                        <td><?php echo $ciudadRow['cd_nombre'] ?></td>
                        <td><?php echo $sucursalesRow['sc_direccion']; ?></td>
                        <td><?php echo $sucursalesRow['sc_correo']; ?></td>
                        <td class="centro"><?php echo $sucursalesRow['sc_atencionDe'].' / '.$sucursalesRow['sc_atencionHasta']; ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $sucursalesRow['IDSucursal'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
                            <a id="<?php echo $sucursalesRow['IDSucursal'] ?>" t="sucursal" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#countSucursales').html('[<?php echo $rowCount ?>]');
</script>