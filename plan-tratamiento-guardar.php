<?php include'config.php'; include'pagination-modal-params.php'; include'encrypt.php';

$pacienteID = $_POST['id'];
$planID = $_SESSION['consultorioTmpPlanID'];


if($_POST['formulario']==1) {

    $comentario = nl2br( trim($_POST['comentario_plan']) );
    $firma_paciente = $_POST['firma_plan_paciente'];
    $firma_usuario = $_POST['firma_plan_usuario'];

    $query = $con->query("UPDATE plantratamientos SET plt_comentario='$comentario', plt_firmaPaciente='$firma_paciente', plt_firmaUsuario='$firma_usuario', plt_estado='1' WHERE IDPlanTratamiento='$planID'");
}

if(!$query){
?>
    <script>$('#msj-odontograma').html('<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al eliminar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label></div>');
    </script>
<?php } else { ?>
    <script>$('#msj-odontograma').html('<div class="contenedorAlerta"><input type="radio" id="alertExito"><label class="alerta exito" for="alertExito"><div>Se ha guardado con exito.</div><div class="close">&times;</div></label></div>');
    </script>
<?php }

                $pcPlanesQuery = "SELECT * FROM plantratamientos
                                    INNER JOIN pacienteodontograma ON plantratamientos.plt_idOdontograma = pacienteodontograma.IDOdontograma
                                    WHERE plt_idClinica='$sessionClinica' AND plt_idPaciente='$pacienteID' AND plt_estado='1' ORDER BY IDPlanTratamiento DESC";

                                $rowCountPcPlanes = $con->query($pcPlanesQuery)->num_rows;

                                //Initialize Pagination class and create object
                                    $pagConfig = array(
                                        'totalRows' => $rowCountPcPlanes,
                                        'perPage' => $numeroResultados,
                                        'link_func' => 'paginationPcPlanes'
                                    );
                                    $pagination =  new Pagination($pagConfig);

                                $pcPlanesSql = $con->query($pcPlanesQuery." LIMIT $numeroResultados");
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