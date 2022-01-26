<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;

    //get number of rows
    $tareasPendientesQuery = "SELECT tareas.*, tpt_nombre, pc_nombres FROM tareas 
                        INNER JOIN citas ON tareas.tar_idCita = citas.IDCita
                        INNER JOIN pacientes ON citas.ct_idPaciente = pacientes.IDPaciente 
                        INNER JOIN tipotarea ON tareas.tar_idTipo = tipotarea.IDTipoTarea
                        WHERE tar_idClinica='$sessionClinica' AND tar_estado=0 ORDER BY tar_fecha ASC";

    $rowCountTareasPendientes = $con->query($tareasPendientesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountTareasPendientes,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashTareasPendientes'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $tareasPendientesSql = $con->query($tareasPendientesQuery." LIMIT $start,$numeroResultados");
   
?>
                    <table class="tableList">
                            <thead>
                                <tr>
                                    <th class="estado">&nbsp;</th>
                                    <th class="columnaCorta">Creación</th>
                                    <th class="columnaCorta">Para</th>
                                    <th>Responsable</th>
                                    <th>Tipo</th>
                                    <th>Paciente</th>
                                    <th class="tableNota">Nota</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($tareasPendientesRow = $tareasPendientesSql->fetch_assoc()){

                                    $responsableNombrePendiente = '';

                                    $usuarioTareaPendienteSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$tareasPendientesRow[tar_responsable]'")->fetch_assoc();
                                    if($usuarioTareaPendienteSql['us_idRol']==2){
                                        $responsableNombrePendienteSql = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal = '$usuarioTareaPendienteSql[us_id]'")->fetch_assoc();
                                        $responsableNombrePendiente = $responsableNombrePendienteSql['sc_nombre'];
                                    } elseif($usuarioTareaPendienteSql['us_idRol']==3){
                                        $responsableNombrePendienteSql = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor = '$usuarioTareaPendienteSql[us_id]'")->fetch_assoc();
                                        $responsableNombrePendiente = $responsableNombrePendienteSql['dc_nombres'];
                                    } elseif($usuarioTareaPendienteSql['us_idRol']==4){
                                        $responsableNombrePendienteSql = $con->query("SELECT ui_nombres FROM usuariosinventario WHERE IDUserInventario = '$usuarioTareaPendienteSql[us_id]'")->fetch_assoc();
                                        $responsableNombrePendiente = $responsableNombrePendienteSql['ui_nombres'];
                                    } elseif($usuarioTareaPendienteSql['us_idRol']==5){
                                        $responsableNombrePendienteSql = $con->query("SELECT uc_nombres FROM usuarioscitas WHERE IDUserCitas = '$usuarioTareaPendienteSql[us_id]'")->fetch_assoc();
                                        $responsableNombrePendiente = $responsableNombrePendienteSql['uc_nombres'];
                                    }

                                    $tareaPacienteUrl = str_replace(" ","-", $tareasPendientesRow['pc_nombres']);

                                    if( $tareasPendientesRow['tar_fecha'] < date('Y-m-d') ){ $estadoTarea = 'estadoCancelado'; }
                                    else{ $estadoTarea = 'estadoNeutro'; }

                                ?>
                                    <tr>
                                        <td class="estado <?php echo $estadoTarea ?>"></td>
                                        <td align="center"><?php echo $tareasPendientesRow['tar_creada'] ?></td>
                                        <td align="center"><?php echo str_replace('-','/',$tareasPendientesRow['tar_fecha']) ?></td>
                                        <td><?php echo $responsableNombrePendiente ?></td>
                                        <td><?php echo $tareasPendientesRow['tpt_nombre'] ?></td>
                                        <td><a title="<?php echo $tareaPacienteUrl ?>" id="<?php echo $tareasPendientesRow['IDPaciente'] ?>" class="consultorioDashEditar" data-page="paciente"><?php echo $tareasPendientesRow['pc_nombres'] ?></a></td>
                                        <td><?php echo $tareasPendientesRow['tar_nota'] ?></td>
                                        <td>
                                            <a title="Confirmar tarea" class="verTarea" data-id="<?= $tareasPendientesRow['IDTarea'] ?>" data-tipo="pendientes"><i class="fa fa-file-text-o"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>