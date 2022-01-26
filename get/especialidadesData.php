<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $especialidadesQuery = "SELECT * FROM especialidades WHERE esp_idClinica='$sessionClinica' AND esp_estado='1' AND ( esp_nombre LIKE '%$busqueda%' ) ORDER BY esp_nombre";

    $rowCount = $con->query($especialidadesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationEspecialidades'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $especialidadesSql = $con->query($especialidadesQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($especialidadesRow = $especialidadesSql->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $especialidadesRow['esp_nombre'] ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $especialidadesRow['IDEspecialidad'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
                            <a title="Eliminar" id="<?php echo $especialidadesRow['IDEspecialidad'] ?>" t="especialidad" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>