<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];

    $vnReferidoRangoDe = str_replace("-", "", $_POST['vnReferidoRangoDe']);
    $vnReferidoRangoHasta = str_replace("-", "", $_POST['vnReferidoRangoHasta']);

    if( !empty( $vnReferidoRangoDe ) ) {
        $searchRangoDe = " AND ct.ct_fechaInicio >= '$vnReferidoRangoDe' "; }
        else { $searchRangoDe = NULL; }
        
    if( !empty( $vnReferidoRangoHasta ) ) {
        $searchRangoHasta = " AND ct.ct_fechaInicio <= '$vnReferidoRangoHasta' "; }
        else { $searchRangoHasta = NULL; }
    
    //get number of rows
    $vnReferidosQuery = "SELECT * FROM citas AS ct
                                    INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
                                    INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
                                    WHERE pc.pc_idReferido = 'V-$id' AND ct.ct_inicial = '1' $searchRangoDe $searchRangoHasta ORDER BY ct.ct_fechaOrden DESC ";

    $rowCountVnReferidos = $con->query($vnReferidosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountVnReferidos,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationVnReferidos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $vnReferidosSql = $con->query($vnReferidosQuery." LIMIT $start,$numeroResultados");
    
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
                                        <?php while($vnReferidosRow = $vnReferidosSql->fetch_assoc()){
                                            $fechaVnReferido = $vnReferidosRow['ct_anoCita'].'/'.$vnReferidosRow['ct_mesCita'].'/'.$vnReferidosRow['ct_diaCita'];
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo $fechaVnReferido ?></td>
                                            <td><?php echo $vnReferidosRow['pc_nombres'] ?></td>
                                            <td><?php echo $vnReferidosRow['tr_nombre'] ?></td>
                                            <td align="right"><?php echo '$'.number_format($vnReferidosRow['ct_costo'], 0, ".", ",")  ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>