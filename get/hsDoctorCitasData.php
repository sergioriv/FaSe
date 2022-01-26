<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    $hsDoctorTipoEvolucion = $_POST['hsDoctorTipoEvolucion'];
    $hsDoctorTratamiento = $_POST['hsDoctorTratamiento'];
    $hsDoctorRangoHasta = str_replace("-", "", $_POST['hsDoctorRangoHasta']);
    $hsDoctorRangoDe = str_replace("-", "", $_POST['hsDoctorRangoDe']);

    if( !empty( $hsDoctorTipoEvolucion ) ) {
        if($hsDoctorTipoEvolucion==2){ $hsDoctorTipoEvolucion=0; }
        $searchTipoEvolucion = " AND citas.ct_evolucionada = '$hsDoctorTipoEvolucion' AND ct_fechaOrden < '$fechaEvolucionCita' "; }
        else { $searchTipoEvolucion = NULL; }
    if( !empty( $hsDoctorTratamiento ) ) {
        $searchTratamiento = " AND tratamientos.IDTratamiento = '$hsDoctorTratamiento' "; }
        else { $searchTratamiento = NULL; }
    if( !empty( $hsDoctorRangoHasta ) ) {
        $searchRangoHasta = " AND citas.ct_fechaInicio <= '$hsDoctorRangoHasta' "; }
        else { $searchRangoHasta = NULL; }
    if( !empty( $hsDoctorRangoDe ) ) {
        $searchRangoDe = " AND citas.ct_fechaInicio >= '$hsDoctorRangoDe' "; }
        else { $searchRangoDe = NULL; }
    
    //get number of rows
    $historialDoctorQuery = "SELECT * FROM citas, pacientes, sucursales, tratamientos WHERE citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idDoctor = '$id' $searchTipoEvolucion $searchTratamiento $searchRangoDe $searchRangoHasta ORDER BY citas.ct_fechaOrden DESC";

    $rowCountHsDoctorCitas = $con->query($historialDoctorQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountHsDoctorCitas,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationHsDoctorCitas'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $historialDoctorSql = $con->query($historialDoctorQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th class="estado">&nbsp</th>
                                    <th class="columnaCorta">Fecha de Cita</th>
                                    <th>Sucursal | Unidad</th>
                                    <th>Tratamiento</th>
                                    <th>Paciente</th>
                                    <th>Estado tratamiento</th>
                                    <th class="columnaTCita">&nbsp</th>
                                    <th>&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($HistoDocRow = $historialDoctorSql->fetch_assoc()){

                                        $pacienteUrl = str_replace(" ","-", $HistoDocRow['pc_nombres']);
                                        
                                        if($HistoDocRow['ct_terminado']==3){
                                            $estadoHDoc = 'Terminado '.$HistoDocRow['ct_terminadoFecha'];
                                        } else { $estadoHDoc = 'Activo'; }

                                        $fechaCitaHD = $HistoDocRow['ct_anoCita'].'/'.$HistoDocRow['ct_mesCita'].'/'.$HistoDocRow['ct_diaCita'].' '.$HistoDocRow['ct_horaCita'];

                                        $estadoEvolucionHD = 'iconGray';
                                
                                        if( $HistoDocRow['ct_estado']==2){
                                            $titleEstadoHD = 'Cancelada';
                                            $estadoCitaHD = ' estadoCancelado ';
                                            $estadoEvolucionHD = 'icon-cancelada'; }
                                        else
                                        if( $HistoDocRow['ct_asistencia']==2){
                                            $titleEstadoHD = 'Realizada';
                                            $estadoCitaHD = ' cita-realizada ';
                                            $estadoEvolucionHD = 'icon-realizada'; }
                                        else
                                        if( $HistoDocRow['ct_asistencia']==1){
                                            $titleEstadoHD = 'Sin asistencia';
                                            $estadoCitaHD = ' cita-sinasistencia ';
                                            $estadoEvolucionHD = 'icon-sinasistencia'; }
                                        else
                                        if( $HistoDocRow['ct_evolucionada']==0 && ($HistoDocRow['ct_fechaInicio'].str_replace(':','',$HistoDocRow['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){
                                            $titleEstadoHD = 'Sin evolución';
                                            $estadoCitaHD = ' cita-sinevolucion ';
                                            $estadoEvolucionHD = 'icon-sinevolucion'; }
                                        else
                                        if( $HistoDocRow['ct_estado']==1){
                                            $titleEstadoHD = 'Confirmada';
                                            $estadoCitaHD = ' cita-confirmada ';
                                            $estadoEvolucionHD = 'icon-confirmada'; }
                                        else {
                                            $titleEstadoHD = 'Creada';
                                            $estadoCitaHD = ' cita-creada ';
                                            $estadoEvolucionHD = 'icon-creada'; }


                                        if($HistoDocRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
                                        } else { $iSC = ''; $cSC = ''; }

                                        if($HistoDocRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
                                        } else { $iPC = ''; $cPC = ''; }

                                        if($HistoDocRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
                                        } else { $iTR = ''; $cTR = ''; }

                                        if($HistoDocRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
                                        else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

                                        $unidadRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$HistoDocRow[ct_idUnidad]'")->fetch_assoc();
                                ?>
                                        <tr>
                                            <td class="estado <?php echo $estadoCitaHD ?>" title="<?= $titleEstadoHD ?>"></td>
                                            <td><?php echo $fechaCitaHD ?></td>
                                            <td class="<?php echo $cSC ?>"><?php echo $iSC.$HistoDocRow['sc_nombre'].' <b>|</b> '.$unidadRow['uo_nombre'] ?></td>
                                            <td class="<?php echo $cTR ?>"><?php echo $iTR.$HistoDocRow['tr_nombre'] ?></td>
                                            <td class="<?php echo $cPC ?>"><?php echo $iPC.$HistoDocRow['pc_nombres'] ?></td>
                                            <td align="center"><?php echo $estadoHDoc ?></td>
                                            <td class="columnaTCita"><?php echo $tipoCita ?></td>
                                            <td class="tableOption">
                                                <?php if($HistoDocRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
                                                    <a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $HistoDocRow["IDCita"] ?>&id=<?php echo $HistoDocRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                                <?php } elseif($HistoDocRow['ct_estado'] < 2) { ?>
                                                    <a title="<?= $titleEstadoHD ?>" id="<?php echo $HistoDocRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucionHD ?>"><i class="fa fa-share-alt"></i></a>
                                                <?php } ?>
                                                <a title="Información Cita" data-id="<?php echo $HistoDocRow['IDCita'] ?>" data-extra="<?= $id ?>" data-div="showResultsHsDoctorCitas" data-site="dc_citas" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>