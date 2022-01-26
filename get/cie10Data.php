<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $cie10Query = "SELECT * FROM rips WHERE rip_estado='1' AND (rip_codigo LIKE '%$busqueda%' OR rip_nombre LIKE '%$busqueda%') ORDER BY rip_nombre ASC";

    $rowCountCie10 = $con->query($cie10Query)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountCie10,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationCie10'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $cie10Sql = $con->query($cie10Query." LIMIT $start,$numeroResultados");
    
?>
                    <table class="tableList">
                        <thead>
                            <tr>
                                <th>Cod.</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($cie10Row = $cie10Sql->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $cie10Row['rip_codigo']; ?></td>
                                <td><?php echo $cie10Row['rip_nombre']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#countCie10').html('Cantidad: [<?php echo $rowCountCie10 ?>]');
</script>