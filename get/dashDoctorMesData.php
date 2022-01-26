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
    $dashDoctorMesQuery = "SELECT IDDoctor, dc_nombres FROM doctores WHERE dc_idClinica='$sessionClinica' AND dc_estado=1 ORDER BY dc_nombres ASC";

    $dashRowCountDoctorMes = $con->query($dashDoctorMesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $dashRowCountDoctorMes,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashDoctorMes'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $dashDoctorMesSql = $con->query($dashDoctorMesQuery." LIMIT $start,$numeroResultados");

$arrayMeses = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$IDmes = (int) $mesID;
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th>Cant.</th>
                                            <th>Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($dashDoctorMesRow = $dashDoctorMesSql->fetch_assoc()){
                                            $dashCtDoctorMes = $con->query("SELECT COUNT(*) AS cantCitas FROM citas WHERE ct_idDoctor='$dashDoctorMesRow[IDDoctor]' AND ct_estado IN(0,1) AND ct_anoCita='$hoyAno' AND ct_mesCita='$mesID'")->fetch_assoc();
                                        ?>
                                            <tr>
                                                <td align="center"><?php echo $dashCtDoctorMes['cantCitas'] ?></td>
                                                <td><a id="<?php echo $dashDoctorMesRow['IDDoctor'] ?>" class="consultorioDashEditar" data-page="doctor"><?php echo $dashDoctorMesRow['dc_nombres'] ?></a></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#contSelectDCMes').hide();
    $('#contDCMes').show();
    $('#contDCMes').html("<?php echo $arrayMeses[$IDmes] ?>");
</script>