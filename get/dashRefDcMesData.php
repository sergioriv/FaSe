<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $mesID = $_POST['mes'];
    if($mesID == ""){
        $mesID = $hoyMes;
    }
    
    //get number of rows
    $dashRefDcMesQuery = "SELECT SUM(ct.ct_costo) AS recaudo, dc.IDDoctor, dc.dc_nombres FROM doctores AS dc
        INNER JOIN pacientes AS pcref ON pcref.pc_idReferido = CONCAT('D-', dc.IDDoctor)
        INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
        WHERE dc.dc_idClinica = '$sessionClinica' AND dc.dc_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$mesID' AND ct.ct_anoCita = '$hoyAno'
        GROUP BY dc.IDDoctor
        ORDER BY dc.dc_nombres ASC";

    $dashRowCountRefDcMes = $con->query($dashRefDcMesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $dashRowCountRefDcMes,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashRefDc'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $dashRefDcMesSql = $con->query($dashRefDcMesQuery." LIMIT $start,$numeroResultados");

$arrayMeses = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$IDmes = (int) $mesID;
    
?>
                                    <table class="tableList">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($dashRefDcMesRow = $dashRefDcMesSql->fetch_assoc()){

                                            ?>
                                                <tr>
                                                    <td><?= $dashRefDcMesRow['dc_nombres'] ?></td>
                                                    <td align="right"><?= '$'.number_format($dashRefDcMesRow['recaudo'], 0, ".", ","); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#contSelectRefDc').hide();
    $('#contRefDc').show();
    $('#contRefDc').html("<?php echo $arrayMeses[$IDmes].' | Doctores' ?>");
</script>