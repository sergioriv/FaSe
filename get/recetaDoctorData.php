<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    
    //get number of rows
    $recetaDoctorQuery = "SELECT * FROM citas, citamedicamentos, vadecum, pacientes WHERE citamedicamentos.cm_idCita = citas.IDCita AND citamedicamentos.cm_idVadecum = vadecum.IDVadecum AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idDoctor = '$id'";

    $rowCountRecetaDoctor = $con->query($recetaDoctorQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountRecetaDoctor,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationRecetaDoctor'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $recetaDoctorSql = $con->query($recetaDoctorQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList tableSinheight tablePadding">
                            <thead>
                                <tr>
                                    <th class="columnaCorta">Fecha asignación</th>
                                    <th>Cant.</th>
                                    <th>Medicamento</th>
                                    <th>Paciente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($recetaDoctorRow = $recetaDoctorSql->fetch_assoc()){

                                        if($recetaDoctorRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
                                        } else { $iPC = ''; $cPC = ''; }
                                ?>
                                <tr>
                                    <td><?php echo $recetaDoctorRow['cm_fechaCreacion'] ?></td>
                                    <td><?php echo $recetaDoctorRow['cm_cantidad'] ?></td>
                                    <td class="selectMedicamento"><?php echo '<span>'.$recetaDoctorRow['vd_medicamento']
                                                        .'</span><i>'.$recetaDoctorRow['vd_presentacion'].'</i>' ?></td>
                                    <td class="<?php echo $cPC ?>"><?php echo $iPC.$recetaDoctorRow['pc_nombres'] ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>