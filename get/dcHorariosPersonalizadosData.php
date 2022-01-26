<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    
    //get number of rows
    $horariosPersonalizadosQuery = "SELECT * FROM doctoreshorarios WHERE dch_idDoctor = '$id' ORDER BY dch_fechaInt DESC";

    $rowCount = $con->query($horariosPersonalizadosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationHorariosPersonalizados'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $horariosPersonalizadosSql = $con->query($horariosPersonalizadosQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th class="columnaCorta">Fecha</th>
                                    <th>Horario de atención</th>
                                    <th>Bloque de descanso</th>
                                    <th>&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($horariosPersonalizadosRow = $horariosPersonalizadosSql->fetch_assoc()){ ?>
                                <tr>
                                    <td><?php echo $horariosPersonalizadosRow['dch_fecha'] ?></td>
                                    <td class="centro"><?php echo $horariosPersonalizadosRow['dch_atencionDe'].' / '.$horariosPersonalizadosRow['dch_atencionHasta'] ?></td>
                                    <td class="centro"><?php echo $horariosPersonalizadosRow['dch_horarioLibreDe'].' / '.$horariosPersonalizadosRow['dch_horarioLibreHasta'] ?></td>
                                    <td class="tableOption">
                                        <a title="Eliminar" id="<?php echo $horariosPersonalizadosRow['IDDocHorario'] ?>" class="consultorioEliminarHorario eliminar"><?php echo $iconoEliminar ?></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>