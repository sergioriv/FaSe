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
    $dashRefPcMesQuery = "SELECT SUM(ct.ct_costo) AS recaudo, pc.IDPaciente, pc.pc_nombres FROM pacientes AS pc
        INNER JOIN pacientes AS pcref ON pcref.pc_idReferido = CONCAT('P-', pc.IDPaciente)
        INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
        WHERE pc.pc_idClinica = '$sessionClinica' AND pc.pc_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$mesID' AND ct.ct_anoCita = '$hoyAno'
        GROUP BY pc.IDPaciente
        ORDER BY pc.pc_nombres ASC";

    $dashRowCountRefPcMes = $con->query($dashRefPcMesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $dashRowCountRefPcMes,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashRefPc'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $dashRefPcMesSql = $con->query($dashRefPcMesQuery." LIMIT $start,$numeroResultados");

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
                                            <?php while($dashRefPcMesRow = $dashRefPcMesSql->fetch_assoc()){                                            
                                            ?>
                                                <tr>
                                                    <td><?= $dashRefPcMesRow['pc_nombres'] ?></td>
                                                    <td align="right"><?= '$'.number_format($dashRefPcMesRow['recaudo'], 0, ".", ","); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#contSelectRefPc').hide();
    $('#contRefPc').show();
    $('#contRefPc').html("<?php echo $arrayMeses[$IDmes].' | Pacientes' ?>");
</script>