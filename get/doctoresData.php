<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $doctoresQuery = "SELECT * FROM doctores WHERE dc_idClinica='$sessionClinica' AND dc_estado='1' AND ( dc_nombres LIKE '%$busqueda%' OR dc_identificacion LIKE '%$busqueda%' ) ORDER BY dc_nombres";

    $rowCount = $con->query($doctoresQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDoctores'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $doctoresSql = $con->query($doctoresQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th colspan="2">Doctor</th>
                        <th>Identificación</th>
                        <th>Teléfono</th>
                        <th>Email</th>
						<th>Horario</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($doctoresRow = $doctoresSql->fetch_assoc()){
                        $tipoIdentiRow = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$doctoresRow[dc_idIdentificacion]'")->fetch_assoc();
                    ?>
                    <tr>
                        <td class="imgUser">
                            <?php
                            if($doctoresRow['dc_foto']!=''){ echo "<img src='$doctoresRow[dc_foto]'>"; }
                            else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
                            ?>
                        </td>
                        <td><a id="<?php echo $doctoresRow['IDDoctor'] ?>" class="consultorioEditar"><?php echo $doctoresRow['dc_nombres']; ?></a></td>
                        <td><?php echo $tipoIdentiRow['ti_nombre'].' '.$doctoresRow['dc_identificacion']; ?></td>
                        <td><?php echo $doctoresRow['dc_telefonoCelular']; ?></td>
                        <td><?php echo $doctoresRow['dc_correo']; ?></td>
						<td class="centro"><?php echo $doctoresRow['dc_atencionDe'].' / '.$doctoresRow['dc_atencionHasta']; ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $doctoresRow['IDDoctor'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
                            <a title="Horario" id="<?php echo $doctoresRow['IDDoctor'] ?>" class="consultorioHorario"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></a>
                            <a title="Eliminar" id="<?php echo $doctoresRow['IDDoctor'] ?>" t="doctor" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>
<script type="text/javascript">
    $('#countDoctores').html('[<?php echo $rowCount ?>]');
</script>