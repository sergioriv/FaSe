<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $vademecumQuery = "SELECT * FROM vadecum WHERE vd_medicamento LIKE '%$busqueda%' ORDER BY vd_medicamento ASC";

    $rowCountVademecum = $con->query($vademecumQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountVademecum,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationVademecum'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $vademecumSql = $con->query($vademecumQuery." LIMIT $start,$numeroResultados");
    
?>
                    <table class="tableList">
                        <thead>
                            <tr>
                                <th>Medicamento</th>
                                <th>Presentación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($vademecumRow = $vademecumSql->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $vademecumRow['vd_medicamento']; ?></td>
                                <td><?php echo $vademecumRow['vd_presentacion']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#countVademecum').html('Cantidad: [<?php echo $rowCountVademecum ?>]');
</script>