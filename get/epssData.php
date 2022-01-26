<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $epssQuery = "SELECT * FROM eps WHERE eps_estado='1' AND (eps_codigo LIKE '%$busqueda%' OR eps_nit LIKE '%$busqueda%' OR eps_nombre LIKE '%$busqueda%') ORDER BY eps_nombre ASC";

    $rowCountEps = $con->query($epssQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountEps,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationEpss'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $epssSql = $con->query($epssQuery." LIMIT $start,$numeroResultados");
    
?>
                    <table class="tableList">
                        <thead>
                            <tr>
                                <th>Cod.</th>
                                <th>NIT</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($epssRow = $epssSql->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $epssRow['eps_codigo']; ?></td>
                                <td><?php echo $epssRow['eps_nit']; ?></td>
                                <td><?php echo $epssRow['eps_nombre']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#countEpss').html('Cantidad: [<?php echo $rowCountEps ?>]');
</script>