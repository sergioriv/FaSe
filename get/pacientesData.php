<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $pacientesQuery = "SELECT * FROM pacientes WHERE pc_idClinica='$sessionClinica' AND pc_estado='1' AND ( pc_nombres LIKE '%$busqueda%' OR pc_identificacion LIKE '%$busqueda%' ) ORDER BY pc_nombres";

    $rowCount = $con->query($pacientesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationPacientes'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $pacientesSql = $con->query($pacientesQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th colspan="2">Paciente</th>
                        <th>Identificación</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($pacientesRow = $pacientesSql->fetch_assoc()){

                        $tipoIdentiRow = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$pacientesRow[pc_idIdentificacion]'")->fetch_assoc();

                        $pacienteUrl = str_replace(" ","-", $pacientesRow['pc_nombres']);

                        if($pacientesRow['pc_telefonoCelular']>0){
                            $pacienteTelefono = $pacientesRow['pc_telefonoCelular'];
                        } else {
                            $pacienteTelefono = $pacientesRow['pc_telefonoFijo'];
                        }
                    ?>
                    <tr>
                        <td class="imgUser">
                            <?php
                            if($pacientesRow['pc_foto']!=''){ echo "<img src='$pacientesRow[pc_foto]'>"; }
                            else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
                            ?>
                        </td>
                        <td><a title="<?php echo $pacienteUrl ?>" id="<?php echo $pacientesRow['IDPaciente'] ?>" class="consultorioEditar"><?php echo $pacientesRow['pc_nombres'] ?></a>
                        </td>
                        <td><?php echo $tipoIdentiRow['ti_nombre'].' '.$pacientesRow['pc_identificacion']; ?></td>
                        <td><?php echo $pacienteTelefono; ?></td>
                        <td><?php echo $pacientesRow['pc_correo']; ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $pacientesRow['IDPaciente'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
                            <a title="Nueva Cita" onClick="location.href='cita?id=<?php echo $pacientesRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></a>
                            <a title="Eliminar" id="<?php echo $pacientesRow['IDPaciente'] ?>" t="paciente" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
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
    $('#countPacientes').html('[<?php echo $rowCount ?>]');
</script>