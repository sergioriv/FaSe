<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];

    $dcReferidoRangoDe = str_replace("-", "", $_POST['dcReferidoRangoDe']);
    $dcReferidoRangoHasta = str_replace("-", "", $_POST['dcReferidoRangoHasta']);

    if( !empty( $dcReferidoRangoDe ) ) {
        $searchRangoDe = " AND ct.ct_fechaInicio >= '$dcReferidoRangoDe' "; }
        else { $searchRangoDe = NULL; }
        
    if( !empty( $dcReferidoRangoHasta ) ) {
        $searchRangoHasta = " AND ct.ct_fechaInicio <= '$dcReferidoRangoHasta' "; }
        else { $searchRangoHasta = NULL; }
    
    //get number of rows
    $dcReferidosQuery = "SELECT * FROM citas AS ct
                                    INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
                                    INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
                                    WHERE pc.pc_idReferido = 'D-$id' AND ct.ct_inicial = '1' $searchRangoDe $searchRangoHasta ORDER BY ct.ct_fechaOrden DESC ";

    $rowCountDcReferidos = $con->query($dcReferidosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountDcReferidos,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDcReferidos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $dcReferidosSql = $con->query($dcReferidosQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th class="columnaCorta">Fecha</th>
                                            <th>Paciente</th>
                                            <th>Tratamiento</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($dcReferidosRow = $dcReferidosSql->fetch_assoc()){
                                            $fechaDcReferido = $dcReferidosRow['ct_anoCita'].'/'.$dcReferidosRow['ct_mesCita'].'/'.$dcReferidosRow['ct_diaCita'];
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo $fechaDcReferido ?></td>
                                            <td><?php echo $dcReferidosRow['pc_nombres'] ?></td>
                                            <td><?php echo $dcReferidosRow['tr_nombre'] ?></td>
                                            <td align="right"><?php echo '$'.number_format($dcReferidosRow['ct_costo'], 0, ".", ",")  ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#countDcReferidos').html('Cantidad: [<?php echo $rowCountDcReferidos ?>]');
</script>