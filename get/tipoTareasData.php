<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    //$busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $tiposTareaQuery = "SELECT * FROM tipotarea WHERE tpt_idClinica IN(0,$sessionClinica) ORDER BY tpt_idClinica DESC, tpt_nombre ASC";

    $rowCount = $con->query($tiposTareaQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationTiposTarea'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $tiposTareaSql = $con->query($tiposTareaQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($tiposTarea = $tiposTareaSql->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $tiposTarea['tpt_nombre']; ?></td>
                        <td class="tableOption">
                            <?php if( $tiposTarea['tpt_idClinica']==$sessionClinica ){ ?>
                                <a title="Editar" id="<?php echo $tiposTarea['IDTipoTarea'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
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