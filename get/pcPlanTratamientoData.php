<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    include('../encrypt.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    
    //get number of rows
    $pcPlanesQuery = "SELECT * FROM plantratamientos
                                    INNER JOIN pacienteodontograma ON plantratamientos.plt_idOdontograma = pacienteodontograma.IDOdontograma
                                    WHERE plt_idClinica='$sessionClinica' AND plt_idPaciente='$id' AND plt_estado='1' ORDER BY IDPlanTratamiento DESC";

    $rowCountPcPlanes = $con->query($pcPlanesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountPcPlanes,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationPcPlanes'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $pcPlanesSql = $con->query($pcPlanesQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th class="columnaCorta">Fecha</th>
                                            <th>Odontograma</th>
                                            <th>Consecutivo</th>
                                            <th>Creador</th>
                                            <th>Comentario</th>
                                            <th>&nbsp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($pcPlanesRow = $pcPlanesSql->fetch_assoc()){

                                            $creadorPlanSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$pcPlanesRow[plt_idUsuario]'")->fetch_assoc();

                                            $IDusuarioPlan = $creadorPlanSql['us_id'];
                                            $nombreUsuarioPlan = '';
                                                if($creadorPlanSql['us_idRol']==1){
                                                    $usuarioPlan = $con->query("SELECT cl_nombre FROM clinicas WHERE IDClinica='$IDusuarioPlan'")->fetch_assoc();
                                                    $nombreUsuarioPlan = $usuarioPlan['cl_nombre'];

                                                } elseif($creadorPlanSql['us_idRol']==2){
                                                    $usuarioPlan = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal='$IDusuarioPlan'")->fetch_assoc();
                                                    $nombreUsuarioPlan = $usuarioPlan['sc_nombre'];

                                                } elseif($creadorPlanSql['us_idRol']==3){
                                                    $usuarioPlan = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor='$IDusuarioPlan'")->fetch_assoc();
                                                    $nombreUsuarioPlan = $usuarioPlan['dc_nombres'];
                                                }

                                                $planEncodeGenerar = encrypt( 'id='.$pcPlanesRow['IDPlanTratamiento'] );
                                        ?>
                                        <tr>
                                            <td class="columnaCorta"><?php echo $pcPlanesRow['plt_fechaCreacion']; ?></td>
                                            <td align="center"><?php echo $pcPlanesRow['pod_consecutivo'] ?></td>
                                            <td align="center"><?php echo $pcPlanesRow['plt_consecutivo'] ?></td>
                                            <td><?php echo $nombreUsuarioPlan ?></td>
                                            <td><?php echo $pcPlanesRow['plt_comentario'] ?></td>
                                            <td class="tableOption">
                                                <a title="Ver plan de tratamiento" class="pacienteVerPlan" data-id="<?php echo $pcPlanesRow['IDPlanTratamiento'] ?>">ver</a>    
                                                <a title="Crear presupuesto" class="nuevoPresupuesto" data-id="<?php echo $pcPlanesRow['IDPlanTratamiento'] ?>"><i class="fa fa-usd" aria-hidden="true"></i></a>
                                                <a title="Descargar PDF" href="plan-tratamiento-generar?q=<?= $planEncodeGenerar ?>"><i class="fa fa-download"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>