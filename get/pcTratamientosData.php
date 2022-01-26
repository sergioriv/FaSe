<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    
    //get number of rows
    $pcTratamientosQuery = "SELECT * FROM citas, tratamientos WHERE citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$id' AND citas.ct_inicial = '1' ORDER BY citas.ct_fechaOrden DESC";

    $rowCountPcTratamientos = $con->query($pcTratamientosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountPcTratamientos,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationPcTratamientos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $pcTratamientosSql = $con->query($pcTratamientosQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th>Tratamiento</th>
                                            <th class="columnaCorta">Fecha de inicio</th>
                                            <th>Progreso</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php 
                                    while($pcTratamientosRow = $pcTratamientosSql->fetch_assoc()){
                                
                                        if($pcTratamientosRow['ct_terminado']==3){
                                            $estado = 'Terminado '.$pcTratamientosRow['ct_terminadoFecha'];
                                            $porcentajeTratamiento = 100;
                                        } else { 
                                            $estado = 'Activo';

                                            $porcentajeSql = $con->query("SELECT SUM(ct_trataPorcentaje) AS porcentaje FROM citas 
                                            WHERE ct_idPaciente = '$id' AND ct_idTratamiento = '$pcTratamientosRow[IDTratamiento]' AND IDCita >= '$pcTratamientosRow[IDCita]'")->fetch_assoc();
                                            $porcentajeTratamiento = $porcentajeSql['porcentaje'];
                                        }

                                        $fechaInicioTratamiento = $pcTratamientosRow['ct_anoCita'].'/'.$pcTratamientosRow['ct_mesCita'].'/'.$pcTratamientosRow['ct_diaCita'].' '.$pcTratamientosRow['ct_horaCita'];

                                        if($pcTratamientosRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
                                        } else { $iTR = ''; $cTR = ''; }
                                ?>
                                        <tr>
                                            <td class="<?php echo $cTR ?>"><?php echo $iTR.$pcTratamientosRow['tr_nombre'] ?></td>
                                            <td><?php echo $fechaInicioTratamiento ?></td>
                                            <td class="centro"><?php echo $porcentajeTratamiento.' %' ?></td>
                                            <td class="centro"><?php echo $estado ?></td>
                                        </tr>
                                <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>