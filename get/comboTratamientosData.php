<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    
    //get number of rows
    $comboTratamientosQuery = "SELECT * FROM tratamientos AS tr 
        INNER JOIN combotratamientos AS cbt ON cbt.cbt_idTratamiento = tr.IDTratamiento 
        INNER JOIN fases AS fs ON tr.tr_idFase = fs.IDFase
        INNER JOIN cups ON tr.tr_idCups = cups.IDCups
        WHERE cbt_idCombo = '$id' AND tr_combo = '0' ORDER BY tr_nombre ASC";

    $rowCountCbTratamientos = $con->query($comboTratamientosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountCbTratamientos,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationComboTratamientos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $comboTratamientosSql = $con->query($comboTratamientosQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th class="columnaCorta">Precio</th>
                                    <th>CUP</th>
                                    <th>Fase</th>
                                    <th>Tratamiento</th>
                                    <th>&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php while($comboTratamientosRow = $comboTratamientosSql->fetch_assoc()){

                                if($comboTratamientosRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
                                    } else { $iTR = ''; $cTR = ''; }
                        ?>
                                <tr>
                                    <td align="right"><?php echo '$'.number_format($comboTratamientosRow['cbt_precio'], 0, ".", ","); ?></td>
                                    <td align="center"><?php echo $comboTratamientosRow['cup_codigo'] ?></td>
                                    <td><?php echo $comboTratamientosRow['fs_nombre'] ?></td>
                                    <td class="<?php echo $cTR ?>"><?php echo $iTR.$comboTratamientosRow['tr_nombre']; ?></td>
                                    <td class="tableOption">
                                        <a title="Eliminar" id="<?php echo $comboTratamientosRow['IDComboTrata'] ?>" t="comboTratamiento" class="eliminarTratamientoCombo eliminar" data-combo="<?php echo $id ?>"><?php echo $iconoEliminar ?></a>
                                    </td>
                                </tr>
                        <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>