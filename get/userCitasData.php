<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $userCitasQuery = "SELECT * FROM usuarioscitas, sucursales WHERE usuarioscitas.uc_idSucursal = sucursales.IDSucursal AND usuarioscitas.uc_idClinica='$sessionClinica' AND sucursales.sc_estado='1' AND usuarioscitas.uc_estado='1' AND (usuarioscitas.uc_nombres LIKE '%$busqueda%' OR usuarioscitas.uc_correo LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%') ORDER BY usuarioscitas.uc_nombres";


    $rowCount = $con->query($userCitasQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationUserCitas'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $userCitasSql = $con->query($userCitasQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Correo</th>
                        <th>Sucursal</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($userCitasRow = $userCitasSql->fetch_assoc()){

                        if($userCitasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
                        } else { $iSC = ''; $cSC = ''; }
                    ?>
                    <tr>
                        <td><?php echo $userCitasRow['uc_nombres']; ?></td>
                        <td><?php echo $userCitasRow['uc_correo']; ?></td>
                        <td class="<?php echo $cSC ?>"><?php echo $iSC.$userCitasRow['sc_nombre']; ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $userCitasRow['IDUserCitas'] ?>" class="consultorioEditar" page="usuario-citas"><?php echo $iconoEditar ?></a>
                            <a title="Eliminar" id="<?php echo $userCitasRow['IDUserCitas'] ?>" t="usCitas" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>