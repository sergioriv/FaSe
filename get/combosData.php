<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $combosQuery = "SELECT * FROM tratamientos WHERE tr_idClinica='$sessionClinica' AND tr_estado='1' AND tr_combo='1' AND ( tr_nombre LIKE '%$busqueda%' ) ORDER BY tr_nombre";

    $rowCount = $con->query($combosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationCombos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $combosSql = $con->query($combosQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($combosRow = $combosSql->fetch_assoc()){ ?>
                                <tr>
                                    <td><a id="<?php echo $combosRow['IDTratamiento'] ?>" class="consultorioEditar" data-page="combo"><?php echo $combosRow['tr_nombre'] ?></a></td>
                                    <td class="tableOption">
                                        <a title="Editar" id="<?php echo $combosRow['IDTratamiento'] ?>" class="consultorioEditar" data-page="combo"><?php echo $iconoEditar ?></a>
                                        <a id="<?php echo $combosRow['IDTratamiento'] ?>" t="combo" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>