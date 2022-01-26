<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $userInventarioQuery = "SELECT * FROM usuariosinventario, sucursales WHERE usuariosinventario.ui_idSucursal = sucursales.IDSucursal AND usuariosinventario.ui_idClinica='$sessionClinica' AND sucursales.sc_estado='1' AND usuariosinventario.ui_estado='1' AND (usuariosinventario.ui_nombres LIKE '%$busqueda%' OR usuariosinventario.ui_correo LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%') ORDER BY usuariosinventario.ui_nombres";

    $rowCount = $con->query($userInventarioQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationUserInventarios'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $userInventarioSql = $con->query($userInventarioQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Correo</th>
                        <th>Sucursal</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($userInventarioRow = $userInventarioSql->fetch_assoc()){

                        if($userInventarioRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
                        } else { $iSC = ''; $cSC = ''; }
                    ?>
                    <tr>
                        <td><?php echo $userInventarioRow['ui_nombres']; ?></td>
                        <td><?php echo $userInventarioRow['ui_correo']; ?></td>
                        <td class="<?php echo $cSC ?>"><?php echo $iSC.$userInventarioRow['sc_nombre']; ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $userInventarioRow['IDUserInventario'] ?>" class="consultorioEditar" page="usuario-inventario"><?php echo $iconoEditar ?></a>
                            <a title="Eliminar" id="<?php echo $userInventarioRow['IDUserInventario'] ?>" t="usInventario" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>