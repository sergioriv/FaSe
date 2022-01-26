<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $vendedoresQuery = "SELECT * FROM vendedores WHERE vn_idClinica='$sessionClinica' AND vn_estado='1' AND vn_nombre LIKE '%$busqueda%' ORDER BY vn_nombre ASC ";

    $rowCount = $con->query($vendedoresQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationVendedores'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $vendedoresSql = $con->query($vendedoresQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($vendedoresRow = $vendedoresSql->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $vendedoresRow['vn_nombre'] ?></td>
                        <td><?php echo $vendedoresRow['vn_telefono'] ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $vendedoresRow['IDVendedor'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
                            <a title="Eliminar" id="<?php echo $vendedoresRow['IDVendedor'] ?>" t="vendedor" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>