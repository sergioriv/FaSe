<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;

    //get number of rows
    $tareasRealizadasQuery = "SELECT tareas.*, tpt_nombre, pc_nombres FROM tareas 
                        INNER JOIN citas ON tareas.tar_idCita = citas.IDCita
                        INNER JOIN pacientes ON citas.ct_idPaciente = pacientes.IDPaciente 
                        INNER JOIN tipotarea ON tareas.tar_idTipo = tipotarea.IDTipoTarea
                        WHERE tar_idClinica='$sessionClinica' AND tar_estado=1 ORDER BY tar_completada ASC";

    $rowCountTareasRealizadas = $con->query($tareasRealizadasQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountTareasRealizadas,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashTareasRealizadas'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $tareasRealizadasSql = $con->query($tareasRealizadasQuery." LIMIT $start,$numeroResultados");
   
?>
                    <table class="tableList">
                            <thead>
                                <tr>
                                    <th class="estado">&nbsp;</th>
                                    <th class="columnaCorta">Creación</th>
                                    <th class="columnaCorta">Completada</th>
                                    <th>Responsable</th>
                                    <th>Tipo</th>
                                    <th>Paciente</th>
                                    <th class="tableNota">Nota</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($tareasRealizadasRow = $tareasRealizadasSql->fetch_assoc()){

                                    $responsableNombreRealizada = '';

                                    $usuarioTareaRealizadaSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$tareasRealizadasRow[tar_responsable]'")->fetch_assoc();
                                    if($usuarioTareaRealizadaSql['us_idRol']==2){
                                        $responsableNombreRealizadaSql = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal = '$usuarioTareaRealizadaSql[us_id]'")->fetch_assoc();
                                        $responsableNombreRealizada = $responsableNombreRealizadaSql['sc_nombre'];
                                    } elseif($usuarioTareaRealizadaSql['us_idRol']==3){
                                        $responsableNombreRealizadaSql = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor = '$usuarioTareaRealizadaSql[us_id]'")->fetch_assoc();
                                        $responsableNombreRealizada = $responsableNombreRealizadaSql['dc_nombres'];
                                    } elseif($usuarioTareaRealizadaSql['us_idRol']==4){
                                        $responsableNombreRealizadaSql = $con->query("SELECT ui_nombres FROM usuariosinventario WHERE IDUserInventario = '$usuarioTareaRealizadaSql[us_id]'")->fetch_assoc();
                                        $responsableNombreRealizada = $responsableNombreRealizadaSql['ui_nombres'];
                                    } elseif($usuarioTareaRealizadaSql['us_idRol']==5){
                                        $responsableNombreRealizadaSql = $con->query("SELECT uc_nombres FROM usuarioscitas WHERE IDUserCitas = '$usuarioTareaRealizadaSql[us_id]'")->fetch_assoc();
                                        $responsableNombreRealizada = $responsableNombreRealizadaSql['uc_nombres'];
                                    }

                                    $tareaPacienteUrl = str_replace(" ","-", $tareasRealizadasRow['pc_nombres']);
                                ?>
                                    <tr>
                                        <td class="estado semaforoVerde"></td>
                                        <td align="center"><?php echo $tareasRealizadasRow['tar_creada'] ?></td>
                                        <td align="center"><?php echo $tareasRealizadasRow['tar_completada'] ?></td>
                                        <td><?php echo $responsableNombreRealizada ?></td>
                                        <td><?php echo $tareasRealizadasRow['tpt_nombre'] ?></td>
                                        <td><a title="<?php echo $tareaPacienteUrl ?>" id="<?php echo $tareasRealizadasRow['IDPaciente'] ?>" class="consultorioDashEditar" data-page="paciente"><?php echo $tareasRealizadasRow['pc_nombres'] ?></a></td>
                                        <td><?php echo $tareasRealizadasRow['tar_nota'] ?></td>
                                        <td>
                                            <a title="Ver tarea" class="verTarea" data-id="<?= $tareasRealizadasRow['IDTarea'] ?>" data-tipo="realizadas"><i class="fa fa-file-text-o"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>