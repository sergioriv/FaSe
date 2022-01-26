<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    //$busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $dashDoctorHoyQuery = "SELECT IDDoctor, dc_nombres FROM doctores WHERE dc_idClinica='$sessionClinica' AND dc_estado=1 ORDER BY dc_nombres ASC";

    $dashRowCountDoctorHoy = $con->query($dashDoctorHoyQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $dashRowCountDoctorHoy,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashDoctorHoy'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $dashDoctorHoySql = $con->query($dashDoctorHoyQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th>Cant.</th>
                                            <th>Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($dashDoctorHoyRow = $dashDoctorHoySql->fetch_assoc()){
                                            $dashCtDoctorHoy = $con->query("SELECT COUNT(*) AS cantCitas FROM citas WHERE ct_idDoctor='$dashDoctorHoyRow[IDDoctor]' AND ct_estado IN(0,1) AND ct_fechaInicio='$fechaHoySinEsp'")->fetch_assoc();
                                        ?>
                                            <tr>
                                                <td align="center"><?php echo $dashCtDoctorHoy['cantCitas'] ?></td>
                                                <td><a id="<?php echo $dashDoctorHoyRow['IDDoctor'] ?>" class="consultorioDashEditar" data-page="doctor"><?php echo $dashDoctorHoyRow['dc_nombres'] ?></a></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>