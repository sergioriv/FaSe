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
    $dashSCQuery = "SELECT IDSucursal, sc_nombre FROM sucursales WHERE sc_idClinica='$sessionClinica' AND sc_estado=1 ORDER BY sc_nombre";

    $rowCountDashSC = $con->query($dashSCQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountDashSC,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashSucursales'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $dashSCSql = $con->query($dashSCQuery." LIMIT $start,$numeroResultados");

$arrayMeses = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$IDmes = (int) $mesID;
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th>Sucursal</th>
                                    <th>Citas</th>
                                    <th>Ventas</th>
                                    <th>Recaudos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($dashSCRow = $dashSCSql->fetch_assoc()){

                                    $dashSCCitas = $con->query("SELECT COUNT(*) AS citasSC FROM citas WHERE ct_idSucursal='$dashSCRow[IDSucursal]' AND ct_anoCita='$hoyAno' AND ct_mesCita='$mesID' AND ct_estado IN(0,1)")->fetch_assoc();

                                    $dashSCVentas = $con->query("SELECT SUM(ct_costo) AS ventasSC FROM citas WHERE ct_idSucursal='$dashSCRow[IDSucursal]' AND ct_anoCita='$hoyAno' AND ct_mesCita='$mesID' AND ct_inicial='1' AND ct_estado IN(0,1)")->fetch_assoc();

                                    $dashSCRecaudos = $con->query("SELECT SUM(ab_abono) AS recaudosSC FROM abonos WHERE ab_idSucursal='$dashSCRow[IDSucursal]' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='$mesID' AND ab_estado='1'")->fetch_assoc();
                                ?>
                                    <tr>
                                        <td><a id="<?php echo $dashSCRow['IDSucursal'] ?>" class="consultorioDashEditar" data-page="sucursal"><?php echo $dashSCRow['sc_nombre'] ?></a></td>
                                        <td align="center"><?php echo $dashSCCitas['citasSC'] ?></td>
                                        <td align="right"><?php echo '$'.number_format($dashSCVentas['ventasSC'], 0, ".", ",") ?></td>
                                        <td align="right"><?php echo '$'.number_format($dashSCRecaudos['recaudosSC'], 0, ".", ",") ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#contSelectSCMes').hide();
    $('#contSCMes').show();
    $('#contSCMes').html("Consolidado <?php echo $arrayMeses[$IDmes] ?>");
</script>