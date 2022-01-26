<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    
    //get number of rows
    $unidadesSucursalQuery = "SELECT * FROM unidadesodontologicas WHERE uo_idSucursal = $id AND uo_estado = 1";

    $rowCountScUnidades = $con->query($unidadesSucursalQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountScUnidades,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationScUnidades'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $unidadesSucuralSql = $con->query($unidadesSucursalQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php while($unidadesRow = $unidadesSucuralSql->fetch_assoc()){ ?>
                                <tr>                                    
                                    <td><?php echo $unidadesRow['uo_nombre'] ?></td>
                                </tr>
                        <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>