<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;

    //get number of rows
    $sinEvolucionQuery = "SELECT DISTINCT ct_idDoctor, dc_nombres FROM citas INNER JOIN doctores ON citas.ct_idDoctor = doctores.IDDoctor WHERE ct_idClinica='$sessionClinica' AND ct_evolucionada=0 AND ct_fechaInicio <= '$fechaHoySinEsp' ORDER BY dc_nombres ASC ";

    $rowCountSinEvolucion = $con->query($sinEvolucionQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountSinEvolucion,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashSinEvolucion'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $sinEvolucionSql = $con->query($sinEvolucionQuery." LIMIT $start,$numeroResultados");
   
?>
                            <table class="tableList">
                                <tbody>
                                    <?php while($sinEvolucionRow = $sinEvolucionSql->fetch_assoc()){
                                        $dcCountSinEvolucion = $con->query("SELECT COUNT(*) AS cont FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_idDoctor='$sinEvolucionRow[ct_idDoctor]' AND ct_evolucionada=0 AND ct_fechaInicio <= '$fechaHoySinEsp'")->fetch_assoc();
                                    ?>
                                    <tr>
                                        <td align="center"><?php echo $dcCountSinEvolucion['cont'] ?></td>
                                        <td align="left"><a id="<?php echo $sinEvolucionRow['ct_idDoctor'] ?>" class="consultorioDashEditar" data-page="doctor"><?php echo $sinEvolucionRow['dc_nombres'] ?></a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>