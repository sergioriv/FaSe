<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    if($sessionRol==1){
        $citasQuery = "SELECT * FROM citas, sucursales, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_estado IN (0,1) AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR doctores.dc_nombres LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) LIKE '%$busqueda%' ) ORDER BY citas.ct_fechaOrden ASC";
    } else if($sessionRol==2){
        $citasQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$sessionUsuario' AND pacientes.pc_estado = '1' AND citas.ct_estado IN (0,1) AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente   AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR doctores.dc_nombres LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) LIKE '%$busqueda%' ) ORDER BY citas.ct_fechaOrden ASC";
    } else if($sessionRol==3){
        $citasQuery = "SELECT * FROM citas, sucursales, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_idDoctor = '$sessionUsuario' AND citas.ct_terminado<='3' AND pacientes.pc_estado = '1' AND citas.ct_estado IN (0,1) AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idPaciente = pacientes.IDPaciente   AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) ORDER BY citas.ct_fechaOrden ASC";
    } else if($sessionRol==5){
        $userCitas = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas='$sessionUsuario'")->fetch_assoc();
        $citasQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$userCitas[uc_idSucursal]' AND pacientes.pc_estado = '1' AND citas.ct_estado IN (0,1) AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR doctores.dc_nombres LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) LIKE '%$busqueda%' ) ORDER BY citas.ct_fechaOrden ASC";
    }

    $citasSql = $con->query($citasQuery);

    $numeroCitasPendientes = $citasSql->num_rows;

                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $numeroCitasPendientes,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationCitasPendientes'
                        );
    $pagination =  new Pagination($pagConfig);

    $citasSql = $con->query($citasQuery." LIMIT $start,$numeroResultados");
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
                            <?php while($citasRow = $citasSql->fetch_assoc()){

                                $estadoEvolucion = 'iconGray';
                                
                                if( $citasRow['ct_estado']==2){
                                    $titleEstado = 'Cancelada';
                                    $estadoCita = ' estadoCancelado ';
                                    $estadoEvolucion = 'icon-cancelada'; }
                                else
                                if( $citasRow['ct_asistencia']==2){
                                    $titleEstado = 'Realizada';
                                    $estadoCita = ' cita-realizada ';
                                    $estadoEvolucion = 'icon-realizada'; }
                                else
                                if( $citasRow['ct_asistencia']==1){
                                    $titleEstado = 'Sin asistencia';
                                    $estadoCita = ' cita-sinasistencia ';
                                    $estadoEvolucion = 'icon-sinasistencia'; }
                                else
                                if( $citasRow['ct_evolucionada']==0 && ($citasRow['ct_fechaInicio'].str_replace(':','',$citasRow['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){
                                    $titleEstado = 'Sin evolución';
                                    $estadoCita = ' cita-sinevolucion ';
                                    $estadoEvolucion = 'icon-sinevolucion'; }
                                else
                                if( $citasRow['ct_estado']==1){
                                    $titleEstado = 'Confirmada';
                                    $estadoCita = ' cita-confirmada ';
                                    $estadoEvolucion = 'icon-confirmada'; }
                                else {
                                    $titleEstado = 'Creada';
                                    $estadoCita = ' cita-creada ';
                                    $estadoEvolucion = 'icon-creada'; }                              
                               

                                $pacienteUrl = str_replace(" ","-", $citasRow['pc_nombres']);

                                $fechaCita = $citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'].' '.$citasRow['ct_horaCita'];

                                if($citasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
                                } else { $iSC = ''; $cSC = ''; }

                                if($citasRow['dc_estado']==0){ $iDC = $iconW; $cDC = 'elementoEliminado';
                                } else { $iDC = ''; $cDC = ''; }

                                if($citasRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
                                } else { $iTR = ''; $cTR = ''; }

                                if($citasRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
                                else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

                                $unidadRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$citasRow[ct_idUnidad]'")->fetch_assoc();

                            ?>
                            <tr>
                                <td class="estado <?php echo $estadoCita ?>" title="<?= $titleEstado ?>"></td>
                                <td class="columnaCorta"><?php echo $fechaCita ?></td>
                                <td class="imgUser">
                                    <?php
                                    if(file_exists('../'.$citasRow['pc_foto'] )){ echo "<img src='$citasRow[pc_foto]'>"; }
                                    else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
                                    ?>
                                </td>
                                <td><a id="<?php echo $citasRow['IDPaciente'] ?>" class="consultorioEditarPaciente"><?php echo $citasRow['pc_nombres']; ?></a></td>
                            <?php if($sessionRol==1||$sessionRol==3){ ?>
                                <td class="<?php echo $cSC ?>"><?php echo $iSC.$citasRow['sc_nombre'].' <b>|</b> '.$unidadRow['uo_nombre'] ?></td><?php } ?>
                            <?php if($sessionRol!=3){ ?>
                                <td class="imgUser <?php echo $cDC ?>">
                                    <?php
                                    if(file_exists('../'.$citasRow['dc_foto'] )){ echo "<img src='$citasRow[dc_foto]'>"; }
                                    else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
                                    ?>
                                </td>
                                <td class="<?php echo $cDC ?>"><?php echo $iDC.$citasRow['dc_nombres']; ?></td><?php } ?>
                                <td class="<?php echo $cTR ?>"><?php echo $iTR.$citasRow['tr_nombre']; ?></td>
                                <td class="columnaTCita"><?php echo $tipoCita ?></td>
                                <td class="tableOption">
                                <?php if($citasRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
                                    <a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $citasRow["IDCita"] ?>&id=<?php echo $citasRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                    <?php } elseif($citasRow['ct_estado'] < 2) { ?>
                                    <a title="<?= $titleEstado ?>" id="<?php echo $citasRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucion ?>"><i class="fa fa-share-alt"></i></a>
                                <?php } ?>
                                <a title="Información Cita" data-id="<?php echo $citasRow['IDCita'] ?>" data-div="showResultsCitasPendientes" data-site="ct_pendientes" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
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
    $('#countPendientes').html('Cantidad: [<?php echo $numeroCitasPendientes ?>]');
</script>