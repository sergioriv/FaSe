<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    $type = $_POST['q'];
    
    //get number of rows
    $citaMedicamentosQuery = "SELECT * FROM citamedicamentos, vadecum WHERE citamedicamentos.cm_idVadecum = vadecum.IDVadecum AND citamedicamentos.cm_idCita='$id' ORDER BY citamedicamentos.IDCitaMedicamento ASC";

    $rowCountCitaMedicamentos = $con->query($citaMedicamentosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountCitaMedicamentos,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationCitaMedicamentos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $citaMedicamentosSql = $con->query($citaMedicamentosQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList tableSinheight tablePadding">
                                    <thead>
                                      <tr>
                                        <th class="columnaCorta">Fecha asignación</th>
                                        <th>Cant.</th>
                                        <th>Medicamento</th>
                                        <?php if($type==false){ ?>
                                            <th>&nbsp</th>
                                        <?php } ?>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($citaMedicamentosRow = $citaMedicamentosSql->fetch_assoc()){
                                        ?>
                                        <tr>
                                            <td><?php echo $citaMedicamentosRow['cm_fechaCreacion'] ?></td>   
                                            <td><?php echo $citaMedicamentosRow['cm_cantidad'] ?></td>   
                                            <td class="selectMedicamento"><?php echo '<span>'.$citaMedicamentosRow['vd_medicamento']
                                                    .'</span><i>'.$citaMedicamentosRow['vd_presentacion'].'</i>' ?></td>
                                            
                                            <?php if($type==false){ ?>    
                                                <td class="tableOption">
                                                
                                                    <a title="Eliminar" class="eliminarMedic eliminar" id="<?php echo $citaMedicamentosRow['IDCitaMedicamento'] ?>" ct="<?php echo $id ?>"><?php echo $iconoEliminar ?></a>
                                                
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>