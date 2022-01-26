<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);

    $hoyHora_int = (int) date('Hi');
    
    if($sessionRol==1){
    $citasSinEvoluQuery = "SELECT * FROM citas, sucursales, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio<='$fechaHoySinEsp' AND citas.ct_horaCitaDe<='$hoyHora_int' AND citas.ct_evolucionada = '0' AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR doctores.dc_nombres LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) LIKE '%$busqueda%' ) ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==2){
    $citasSinEvoluQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$sessionUsuario' AND citas.ct_fechaInicio<='$fechaHoySinEsp' AND citas.ct_horaCitaDe<='$hoyHora_int' AND citas.ct_evolucionada = '0' AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR doctores.dc_nombres LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) LIKE '%$busqueda%' ) ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==3){
    $citasSinEvoluQuery = "SELECT * FROM citas, sucursales, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_idDoctor = '$sessionUsuario' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio<='$fechaHoySinEsp' AND citas.ct_horaCitaDe<='$hoyHora_int' AND citas.ct_evolucionada = '0' AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==5){
    $userCitas = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas='$sessionUsuario'")->fetch_assoc();
    $citasSinEvoluQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$userCitas[uc_idSucursal]' AND citas.ct_fechaInicio<='$fechaHoySinEsp' AND citas.ct_horaCitaDe<='$hoyHora_int' AND citas.ct_evolucionada = '0' AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente   AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR doctores.dc_nombres LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) LIKE '%$busqueda%' ) ORDER BY citas.ct_fechaOrden ASC";
}

$citasSinEvoluSql = $con->query($citasSinEvoluQuery);

$numeroCitasSinEvolu = $citasSinEvoluSql->num_rows;

                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $numeroCitasSinEvolu,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationCitasSinEvolu'
                        );
    $pagination =  new Pagination($pagConfig);

    $citasSinEvoluSql = $con->query($citasSinEvoluQuery." LIMIT $start,$numeroResultados");
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
                            <?php while($citasRow = $citasSinEvoluSql->fetch_assoc()){

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
                                <td class="estado cita-sinevolucion" title="Sin evolución"></td>
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
                                <?php if($citasRow['ct_fechaOrden'] <= $fechaEvolucionCita){ ?>
                                    <a title="Sin evolución" id="<?php echo $citasRow['IDCita'] ?>" class="consultoriosEvolucion icon-sinevolucion"><i class="fa fa-share-alt"></i></a>
                                <?php } ?>
                                <a title="Información Cita" data-id="<?php echo $citasRow['IDCita'] ?>" data-div="showResultsCitasSinEvolu" data-site="ct_sinevolucion" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
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
    $('#countSinEvolucion').html('Cantidad: [<?php echo $numeroCitasSinEvolu ?>]');
</script>