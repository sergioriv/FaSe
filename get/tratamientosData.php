<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $tratamientosQuery = "SELECT * FROM tratamientos INNER JOIN fases ON tratamientos.tr_idFase = fases.IDFase INNER JOIN cups ON tratamientos.tr_idCups = cups.IDCups WHERE tr_idClinica='$sessionClinica' AND tr_estado='1' AND tr_combo='0' AND ( tr_nombre LIKE '%$busqueda%' OR cup_codigo LIKE '%$busqueda%' OR fs_nombre LIKE '%$busqueda%' ) ORDER BY tr_nombre";

    $rowCount = $con->query($tratamientosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationTratamientos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $tratamientosSql = $con->query($tratamientosQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th>Precio</th>
                                    <th>CUP</th>
                                    <th class="columnaCorta">Fase</th>
                                    <th>Tratamiento</th>
                                    <th>&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($tratamientosRow = $tratamientosSql->fetch_assoc()){ ?>
                                <tr>
                                    <td align="right" class="columnaCorta"><?php echo '$'.number_format($tratamientosRow['tr_precio'], 0, ".", ","); ?></td>
                                    <td align="center" class="columnaCorta"><?php echo $tratamientosRow['cup_codigo'] ?></td>
                                    <td><?php echo $tratamientosRow['fs_nombre'] ?></td>
                                    <td><a id="<?php echo $tratamientosRow['IDTratamiento'] ?>" class="consultorioEditar" data-page="tratamiento"><?php echo $tratamientosRow['tr_nombre']; ?></a></td>
                                    <td class="tableOption">
                                        <a title="Editar" id="<?php echo $tratamientosRow['IDTratamiento'] ?>" class="consultorioEditar" data-page="tratamiento"><?php echo $iconoEditar ?></a>
                                        <a id="<?php echo $tratamientosRow['IDTratamiento'] ?>" t="tratamiento" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>