<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];

    $pcReferidoRangoDe = str_replace("-", "", $_POST['pcReferidoRangoDe']);
    $pcReferidoRangoHasta = str_replace("-", "", $_POST['pcReferidoRangoHasta']);

    if( !empty( $pcReferidoRangoDe ) ) {
        $searchRangoDe = " AND ct.ct_fechaInicio >= '$pcReferidoRangoDe' "; }
        else { $searchRangoDe = NULL; }
        
    if( !empty( $pcReferidoRangoHasta ) ) {
        $searchRangoHasta = " AND ct.ct_fechaInicio <= '$pcReferidoRangoHasta' "; }
        else { $searchRangoHasta = NULL; }
    
    //get number of rows
    $pcReferidosQuery = "SELECT * FROM citas AS ct
                                    INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
                                    INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
                                    WHERE pc.pc_idReferido = 'P-$id' AND ct.ct_inicial = '1' $searchRangoDe $searchRangoHasta ORDER BY ct.ct_fechaOrden DESC ";

    $rowCountPcReferidos = $con->query($pcReferidosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountPcReferidos,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationPcReferidos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $pcReferidosSql = $con->query($pcReferidosQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th>Paciente</th>
                                            <th>Tratamiento</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($pcReferidosRow = $pcReferidosSql->fetch_assoc()){ ?>
                                        <tr>
                                            <td><?php echo $pcReferidosRow['pc_nombres'] ?></td>
                                            <td><?php echo $pcReferidosRow['tr_nombre'] ?></td>
                                            <td align="right"><?php echo '$'.number_format($pcReferidosRow['ct_costo'], 0, ".", ",")  ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>