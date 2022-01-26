<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');

    $start  = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    $pcCitasSucursal = $_POST['pcCitasSucursal'];
    $pcCitasDoctor = $_POST['pcCitasDoctor'];
    $pcCitasTratamiento = $_POST['pcCitasTratamiento'];
    $pcCitasRangoDe = str_replace("-", "", $_POST['pcCitasRangoDe']);
    $pcCitasRangoHasta = str_replace("-", "", $_POST['pcCitasRangoHasta']);

    if( !empty( $pcCitasSucursal ) ) {
        $searchSucursal = " AND sucursales.IDSucursal = '$pcCitasSucursal' "; }
        else { $searchSucursal = NULL; }
        
    if( !empty( $pcCitasDoctor ) ) {
        $searchDoctor = " AND doctores.IDDoctor = '$pcCitasDoctor' "; }
        else { $searchDoctor = NULL; }
        
    if( !empty( $pcCitasTratamiento ) ) {
        $searchTratamiento = " AND tratamientos.IDTratamiento = '$pcCitasTratamiento' "; }
        else { $searchTratamiento = NULL; }
        
    if( !empty( $pcCitasRangoDe ) ) {
        $searchRangoDe = " AND citas.ct_fechaInicio >= '$pcCitasRangoDe' "; }
        else { $searchRangoDe = NULL; }
        
    if( !empty( $pcCitasRangoHasta ) ) {
        $searchRangoHasta = " AND citas.ct_fechaInicio <= '$pcCitasRangoHasta' "; }
        else { $searchRangoHasta = NULL; }
    


    $pacienteRow = $con->query("SELECT IDPaciente, pc_nombres FROM pacientes WHERE IDPaciente='$id'")->fetch_assoc();
    $pacienteUrl = str_replace(" ","-", $pacienteRow['pc_nombres']);
    //get number of rows
    $pcCitasQuery = "SELECT * FROM citas, sucursales, doctores, tratamientos WHERE citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$id' $searchSucursal $searchDoctor $searchTratamiento $searchRangoDe $searchRangoHasta ORDER BY citas.ct_fechaOrden DESC";

    $rowCountPcCitas = $con->query($pcCitasQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountPcCitas,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationPcCitas'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $pcCitasSql = $con->query($pcCitasQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th class="estado">&nbsp</th>
                                            <th class="columnaCorta">Fecha de Cita</th>
                                            <th>Sucursal | Unidad</th>
                                            <th>Doctor</th>
                                            <th>Tratamiento</th>
                                            <th class="columnaTCita">&nbsp</th>
                                            <th>&nbsp</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php
                                            while($pcCitasRow = $pcCitasSql->fetch_assoc()){
                                                
                                                $estadoEvolucion = 'iconGray';
                                
                                                if( $pcCitasRow['ct_estado']==2){
                                                    $titleEstado = 'Cancelada';
                                                    $estadoCita = ' estadoCancelado ';
                                                    $estadoEvolucion = 'icon-cancelada'; }
                                                else
                                                if( $pcCitasRow['ct_asistencia']==2){
                                                    $titleEstado = 'Realizada';
                                                    $estadoCita = ' cita-realizada ';
                                                    $estadoEvolucion = 'icon-realizada'; }
                                                else
                                                if( $pcCitasRow['ct_asistencia']==1){
                                                    $titleEstado = 'Sin asistencia';
                                                    $estadoCita = ' cita-sinasistencia ';
                                                    $estadoEvolucion = 'icon-sinasistencia'; }
                                                else
                                                if( $pcCitasRow['ct_evolucionada']==0 && ($pcCitasRow['ct_fechaInicio'].str_replace(':','',$pcCitasRow['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){
                                                    $titleEstado = 'Sin evolución';
                                                    $estadoCita = ' cita-sinevolucion ';
                                                    $estadoEvolucion = 'icon-sinevolucion'; }
                                                else
                                                if( $pcCitasRow['ct_estado']==1){
                                                    $titleEstado = 'Confirmada';
                                                    $estadoCita = ' cita-confirmada ';
                                                    $estadoEvolucion = 'icon-confirmada'; }
                                                else {
                                                    $titleEstado = 'Creada';
                                                    $estadoCita = ' cita-creada ';
                                                    $estadoEvolucion = 'icon-creada'; }


                                                if($pcCitasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
                                                } else { $iSC = ''; $cSC = ''; }

                                                if($pcCitasRow['dc_estado']==0){ $iDC = $iconW; $cDC = 'elementoEliminado';
                                                } else { $iDC = ''; $cDC = ''; }

                                                if($pcCitasRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
                                                } else { $iTR = ''; $cTR = ''; }

                                                if($pcCitasRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
                                                else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

                                                $unidadRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$pcCitasRow[ct_idUnidad]'")->fetch_assoc();
                                        ?>
                                            <tr>
                                                <td class="estado <?php echo $estadoCita ?>" title="<?= $titleEstado ?>"></td>
                                                <td class="columnaCorta"><?php echo $pcCitasRow['ct_anoCita'].'/'.$pcCitasRow['ct_mesCita'].'/'.$pcCitasRow['ct_diaCita'].' '.$pcCitasRow['ct_horaCita']; ?></td>
                                                <td class="<?php echo $cSC ?>"><?php echo $iSC.$pcCitasRow['sc_nombre'].' <b>|</b> '.$unidadRow['uo_nombre'] ?></td>
                                                <td class="<?php echo $cDC ?>"><?php echo $iDC.$pcCitasRow['dc_nombres']?></td>
                                                <td class="<?php echo $cTR ?>"><?php echo $iTR.$pcCitasRow['tr_nombre'] ?></td>
                                                <td class="columnaTCita"><?php echo $tipoCita ?></td>
                                                <td class="tableOption">

                                                <?php if($pcCitasRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
                                                    <a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $pcCitasRow["IDCita"] ?>&id=<?php echo $id ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                                <?php } elseif($pcCitasRow['ct_estado'] < 2) { ?>
                                                    <a title="<?= $titleEstado ?>" id="<?php echo $pcCitasRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucion ?>"><i class="fa fa-share-alt"></i></a>
                                                <?php } ?>
                                                <a title="Información Cita" data-id="<?php echo $pcCitasRow['IDCita'] ?>" data-extra="<?= $id ?>" data-div="showResultsPcCitas" data-site="pc_citas" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>        
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>