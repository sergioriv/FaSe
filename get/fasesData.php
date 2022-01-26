<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    //$busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $fasesQuery = "SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica) ORDER BY fs_idClinica DESC, fs_nombre ASC";

    $rowCount = $con->query($fasesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationFases'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $fasesSql = $con->query($fasesQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($fasesRow = $fasesSql->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $fasesRow['fs_nombre']; ?></td>
                        <td class="tableOption">
                            <?php if( $fasesRow['fs_idClinica']==$sessionClinica ){ ?>
                                <a title="Editar" id="<?php echo $fasesRow['IDFase'] ?>" class="consultorioEditar" data-page="fase"><?php echo $iconoEditar ?></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>