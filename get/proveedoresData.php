<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $proveedoresQuery = "SELECT * FROM proveedores WHERE pr_idClinica='$sessionClinica' AND pr_estado='1' AND (pr_nombre LIKE '%$busqueda%' OR pr_nit LIKE '%$busqueda%' OR pr_telefonoFijo LIKE '%$busqueda%' OR pr_correo LIKE '%$busqueda%') ORDER BY pr_nombre";

    $rowCount = $con->query($proveedoresQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationProveedores'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $proveedoresSql = $con->query($proveedoresQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>NIT</th>
                        <th>Teléfono</th>
                        <th>Ciudad</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($proveedoresRow = $proveedoresSql->fetch_assoc()){
                        $ciudadRow = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$proveedoresRow[pr_idCiudad]'")->fetch_assoc();
                    ?>
                    <tr>
                        <td><a id="<?php echo $proveedoresRow['IDProveedor'] ?>" class="consultorioEditar"><?php echo $proveedoresRow['pr_nombre']; ?></a></td>
                        <td><?php echo $proveedoresRow['pr_nit']; ?></td>
                        <td><?php echo $proveedoresRow['pr_telefonoFijo']; ?></td>
                        <td><?php echo $ciudadRow['cd_nombre'] ?></td>
                        <td><?php echo $proveedoresRow['pr_direccion']; ?></td>
                        <td><?php echo $proveedoresRow['pr_correo']; ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $proveedoresRow['IDProveedor'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
                            <a title="Orden de Entrada" id="<?php echo $proveedoresRow['IDProveedor'] ?>" class="consultorioOrdenEntrada"><i class="fa fa-file-o" aria-hidden="true"></i></a>
                            <a title="Eliminar" id="<?php echo $proveedoresRow['IDProveedor'] ?>" t="proveedor" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>