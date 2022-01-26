<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $ciuo8Query = "SELECT * FROM ocupaciones WHERE (ocu_codigo LIKE '%$busqueda%' OR ocu_nombre LIKE '%$busqueda%') ORDER BY ocu_nombre ASC";

    $rowCountCiuo8 = $con->query($ciuo8Query)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountCiuo8,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationCiuo8'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $ciuo8Sql = $con->query($ciuo8Query." LIMIT $start,$numeroResultados");
    
?>
                    <table class="tableList">
                        <thead>
                            <tr>
                                <th>Cod.</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($ciuo8Row = $ciuo8Sql->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $ciuo8Row['ocu_codigo']; ?></td>
                                <td><?php echo $ciuo8Row['ocu_nombre']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#countCiuo8').html('Cantidad: [<?php echo $rowCountCiuo8 ?>]');
</script>