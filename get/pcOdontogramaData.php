<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    
    //get number of rows
    $pcOdontogramasQuery = "SELECT * FROM pacienteodontograma WHERE pod_idPaciente = '$id' AND pod_estado = 1 ORDER BY IDOdontograma DESC";

    $rowCountPcOdontogramas = $con->query($pcOdontogramasQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountPcOdontogramas,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationPcOdontogramas'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $pcOdontogramasSql = $con->query($pcOdontogramasQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th class="columnaCorta">Fecha</th>
                                            <th>Consecutivo</th>
                                            <th>Nota</th>
                                            <th>&nbsp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($pcOdontogramaRow = $pcOdontogramasSql->fetch_assoc()){ ?>
                                        <tr>
                                            <td><?= $pcOdontogramaRow['pod_fecha'] ?></td>
                                            <td align="center"><?= $pcOdontogramaRow['pod_consecutivo'] ?></td>
                                            <td align="center"><?= $pcOdontogramaRow['pod_nota'] ?></td>
                                            <td class="tableOption">
                                                <a title="Ver odontograma" class="verOdontograma" data-id="<?= $pcOdontogramaRow['IDOdontograma'] ?>"><i class="fa fa-table" aria-hidden="true"></i></a>
                                                <a title="Crear plan de tratamiento" class="nuevoPlan" data-odontograma="<?= $pcOdontogramaRow['IDOdontograma'] ?>"><i class="fa fa-clipboard"></i></a>
                                                <a title="Descargar PDF" href="odontograma-paciente-pdf.php?q=<?= encrypt( 'id='.$pcOdontogramaRow['IDOdontograma'] ) ?>"><i class="fa fa-download"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>