<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;

    //get number of rows
    $tareasQuery = "SELECT * FROM citas INNER JOIN pacientes ON citas.ct_idPaciente = pacientes.IDPaciente WHERE ct_idClinica='$sessionClinica' AND ct_tarea=1 AND ct_tareaEstado IN(0,1) ORDER BY ct_tareaEstado DESC, ct_tareaFechaCreacion ASC ";

    $rowCountTareas = $con->query($tareasQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountTareas,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashTareas'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $tareasSql = $con->query($tareasQuery." LIMIT $start,$numeroResultados");
   
?>
                    <table class="tableList">
                            <thead>
                                <tr>
                                    <th class="estado">&nbsp;</th>
                                    <th class="columnaCorta">Creación</th>
                                    <th>Tipo</th>
                                    <th>Paciente</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th class="tableNota">Nota</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($tareasRow = $tareasSql->fetch_assoc()){
                                    if($tareasRow['pc_telefonoCelular']>0){
                                        $tareaPacienteTelefono = $tareasRow['pc_telefonoCelular'];
                                    } else {
                                        $tareaPacienteTelefono = $tareasRow['pc_telefonoFijo'];
                                    }

                                    $tareaTipo = $con->query("SELECT tpt_nombre FROM tipotarea WHERE IDTipoTarea = '$tareasRow[ct_tareaTipo]'")->fetch_assoc();

                                    $tareaPacienteUrl = str_replace(" ","-", $tareasRow['pc_nombres']);

                                    if( $tareasRow['ct_tareaEstado']== 1 ){ $estadoTarea = 'estadoCancelado'; }
                                    else{ $estadoTarea = 'estadoNeutro'; }

                                ?>
                                    <tr>
                                        <td class="estado <?php echo $estadoTarea ?>"></td>
                                        <td align="center"><?php echo $tareasRow['ct_tareaFechaCreacion'] ?></td>
                                        <td><?php echo $tareaTipo['tpt_nombre'] ?></td>
                                        <td><a title="<?php echo $tareaPacienteUrl ?>" id="<?php echo $tareasRow['IDPaciente'] ?>" class="consultorioDashEditar" data-page="paciente"><?php echo $tareasRow['pc_nombres'] ?></a></td>
                                        <td align="center"><?php echo $tareaPacienteTelefono ?></td>
                                        <td><?php echo $tareasRow['pc_correo'] ?></td>
                                        <td><?php echo $tareasRow['ct_tareaNota'] ?></td>
                                        <td>
                                            <a class="confirmarTarea" data-id="<?= $tareasRow['IDCita'] ?>"><i class="fa fa-check-square-o"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>