<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $cupsQuery = "SELECT * FROM cups WHERE cup_estado='1' AND (cup_codigo LIKE '%$busqueda%' OR cup_nombre LIKE '%$busqueda%') ORDER BY cup_nombre ASC";

    $rowCountCups = $con->query($cupsQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountCups,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationCups'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $cupsSql = $con->query($cupsQuery." LIMIT $start,$numeroResultados");
    
?>
                    <table class="tableList">
                        <thead>
                            <tr>
                                <th>Cod.</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($cupsRow = $cupsSql->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $cupsRow['cup_codigo']; ?></td>
                                <td><?php echo $cupsRow['cup_nombre']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#countCups').html('Cantidad: [<?php echo $rowCountCups ?>]');
</script>