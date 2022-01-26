<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;

    $rangoDeQuery = str_replace('-', '', $_POST['de']);
    $rangoHastaQuery = str_replace('-', '', $_POST['hasta']);

    if(empty($_POST['de']) && empty($_POST['hasta'])){
        $rangoDe = " AND citas.ct_fechaInicio >= $fechaMesInicio "; 
        $rangoHasta = " AND citas.ct_fechaInicio <= $fechaHoySinEsp "; 
    } else {
        $rangoDe = !empty($_POST['de']) ? " AND citas.ct_fechaInicio >= $rangoDeQuery " : '' ; 
        $rangoHasta = !empty($_POST['hasta']) ? " AND citas.ct_fechaInicio <= $rangoHastaQuery " : '' ;
    }
    
if($sessionRol==1){
    $citasHistoricoQuery = "SELECT * FROM citas, sucursales, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' $rangoDe $rangoHasta AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==2){
    $citasHistoricoQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$sessionUsuario' $rangoDe $rangoHasta AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente   AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==3){
    $citasHistoricoQuery = "SELECT * FROM citas, sucursales, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_idDoctor = '$sessionUsuario' AND citas.ct_terminado<='3' $rangoDe $rangoHasta AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idPaciente = pacientes.IDPaciente   AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==5){
    $userCitas = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas='$sessionUsuario'")->fetch_assoc();
    $citasHistoricoQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$userCitas[uc_idSucursal]' $rangoDe $rangoHasta AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC";
}

$citasHistoricoSql = $con->query($citasHistoricoQuery);

$numeroCitasHistorico = $citasHistoricoSql->num_rows;

                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $numeroCitasHistorico,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationCitasHistorico'
                        );
    $pagination =  new Pagination($pagConfig);

    $citasHistoricoSql = $con->query($citasHistoricoQuery." LIMIT $start,$numeroResultados");
?>
            
                    <table class="tableList">
                        <thead>
                            <tr>
                                <th class="estado">&nbsp</th>
                                <th class="columnaCorta">Fecha de Cita</th>
                                <th colspan="2">Paciente</th>
                            <?php if($sessionRol==1||$sessionRol==3){ ?><th>Sucursal | Unidad</th><?php } ?>
                            <?php if($sessionRol!=3){ ?><th colspan="2">Doctor</th><?php } ?>
                                <th>Tratamiento</th>
                                <th class="columnaTCita">&nbsp</th>
                                <th>&nbsp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($citasHistRow = $citasHistoricoSql->fetch_assoc()){
                                
                                $estadoEvolucion = 'iconGray';
                                
                                if( $citasHistRow['ct_estado']==2){
                                    $titleEstado = 'Cancelada';
                                    $estadoCita = ' estadoCancelado ';
                                    $estadoEvolucion = 'icon-cancelada'; }
                                else
                                if( $citasHistRow['ct_asistencia']==2){
                                    $titleEstado = 'Realizada';
                                    $estadoCita = ' cita-realizada ';
                                    $estadoEvolucion = 'icon-realizada'; }
                                else
                                if( $citasHistRow['ct_asistencia']==1){
                                    $titleEstado = 'Sin asistencia';
                                    $estadoCita = ' cita-sinasistencia ';
                                    $estadoEvolucion = 'icon-sinasistencia'; }
                                else
                                if( $citasHistRow['ct_evolucionada']==0 && ($citasHistRow['ct_fechaInicio'].str_replace(':','',$citasHistRow['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){
                                    $titleEstado = 'Sin evolución';
                                    $estadoCita = ' cita-sinevolucion ';
                                    $estadoEvolucion = 'icon-sinevolucion'; }
                                else
                                if( $citasHistRow['ct_estado']==1){
                                    $titleEstado = 'Confirmada';
                                    $estadoCita = ' cita-confirmada ';
                                    $estadoEvolucion = 'icon-confirmada'; }
                                else {
                                    $titleEstado = 'Creada';
                                    $estadoCita = ' cita-creada ';
                                    $estadoEvolucion = 'icon-creada'; }

                                $pacienteUrl = str_replace(" ","-", $citasHistRow['pc_nombres']);

                                $fechaCita = $citasHistRow['ct_anoCita'].'/'.$citasHistRow['ct_mesCita'].'/'.$citasHistRow['ct_diaCita'].' '.$citasHistRow['ct_horaCita'];

                                if($citasHistRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
                                } else { $iSC = ''; $cSC = ''; }

                                if($citasHistRow['dc_estado']==0){ $iDC = $iconW; $cDC = 'elementoEliminado';
                                } else { $iDC = ''; $cDC = ''; }

                                if($citasHistRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
                                } else { $iTR = ''; $cTR = ''; }

                                if($citasHistRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
                                else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

                                $unidadRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$citasHistRow[ct_idUnidad]'")->fetch_assoc();

                            ?>
                            <tr>
                                <td class="estado <?php echo $estadoCita ?>" title="<?= $titleEstado ?>"></td>
                                <td class="columnaCorta"><?php echo $fechaCita ?></td>
                                <td class="imgUser">
                                    <?php
                                    if(file_exists('../'.$citasHistRow['pc_foto'] )){ echo "<img src='$citasHistRow[pc_foto]'>"; }
                                    else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
                                    ?>
                                </td>
                                <td><a id="<?php echo $citasHistRow['IDPaciente'] ?>" class="consultorioEditarPaciente"><?php echo $citasHistRow['pc_nombres']; ?></a></td>
                            <?php if($sessionRol==1||$sessionRol==3){ ?>
                                <td class="<?php echo $cSC ?>"><?php echo $iSC.$citasHistRow['sc_nombre'].' <b>|</b> '.$unidadRow['uo_nombre'] ?></td><?php } ?>
                            <?php if($sessionRol!=3){ ?>
                                <td class="imgUser <?php echo $cDC ?>">
                                    <?php
                                    if(file_exists('../'.$citasHistRow['dc_foto'] )){ echo "<img src='$citasHistRow[dc_foto]'>"; }
                                    else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
                                    ?>
                                </td>
                                <td class="<?php echo $cDC ?>"><?php echo $iDC.$citasHistRow['dc_nombres']; ?></td><?php } ?>
                                <td class="<?php echo $cTR ?>"><?php echo $iTR.$citasHistRow['tr_nombre']; ?></td>
                                <td class="columnaTCita"><?php echo $tipoCita ?></td>
                                <td class="tableOption">
                                <?php if($citasHistRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
                                    <a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $citasHistRow["IDCita"] ?>&id=<?php echo $citasHistRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                    <?php } elseif($citasHistRow['ct_estado'] < 2) { ?>
                                        <a title="<?= $titleEstado ?>" id="<?php echo $citasHistRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucion ?>"><i class="fa fa-share-alt"></i></a>
                                    <?php } ?>
                                    <a title="Información Cita" data-id="<?php echo $citasHistRow['IDCita'] ?>" data-div="showResultsCitasHistorico" data-site="ct_historico" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $pagination->createLinks(); ?>

<?php
}
?>
<script type="text/javascript">
    $('#countHistorico').html('Cantidad: [<?php echo $numeroCitasHistorico ?>]');
</script>