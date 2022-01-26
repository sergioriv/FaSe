<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $sirhoQuery = "SELECT * FROM sirhoclinica, sirho, sirhocategorias WHERE sirhoclinica.shcl_idSirho = sirho.IDSirho AND sirho.sh_idCategoria = sirhocategorias.IDSirhoCategoria AND sirhoclinica.shcl_idClinica='$sessionClinica' AND sirhoclinica.shcl_estado='1' AND ( sirho.sh_nombre LIKE '%$busqueda%' OR sirhoclinica.shcl_fechaCreacion LIKE '%$busqueda%' OR sirhocategorias.shcg_nombre LIKE '%$busqueda%' ) ORDER BY sirhoclinica.IDSirhoClinica DESC";

    $rowCount = $con->query($sirhoQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationSirho'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $sirhoSql = $con->query($sirhoQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th class="columnaCorta">Fecha</th>
                        <th>Categoria</th>
                        <th>Sirho</th>
                        <th>Cant. (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($sirhoRow = $sirhoSql->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $sirhoRow['shcl_fechaCreacion'] ?></td>
                        <td><?php echo $sirhoRow['shcg_nombre'] ?></td>
                        <td><?php echo $sirhoRow['sh_nombre'] ?></td>
                        <td class="centro"><?php echo number_format($sirhoRow['shcl_cantidad'], 0, ".", ","); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>