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
    $dashRefVnMesQuery = "SELECT SUM(ct.ct_costo) AS recaudo, vn.IDVendedor, vn.vn_nombre FROM vendedores AS vn
        INNER JOIN pacientes AS pcref ON pcref.pc_idReferido = CONCAT('V-', vn.IDVendedor)
        INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
        WHERE vn.vn_idClinica = '$sessionClinica' AND vn.vn_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$mesID' AND ct.ct_anoCita = '$hoyAno'
        GROUP BY vn.IDVendedor
        ORDER BY vn.vn_nombre ASC";

    $dashRowCountRefVnMes = $con->query($dashRefVnMesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $dashRowCountRefVnMes,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashRefVn'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $dashRefVnMesSql = $con->query($dashRefVnMesQuery." LIMIT $start,$numeroResultados");

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
                                            <?php while($dashRefVnMesRow = $dashRefVnMesSql->fetch_assoc()){                                            
                                            ?>
                                                <tr>
                                                    <td><?= $dashRefVnMesRow['vn_nombre'] ?></td>
                                                    <td align="right"><?= '$'.number_format($dashRefVnMesRow['recaudo'], 0, ".", ","); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#contSelectRefVn').hide();
    $('#contRefVn').show();
    $('#contRefVn').html("<?php echo $arrayMeses[$IDmes].' | Vendedores' ?>");
</script>