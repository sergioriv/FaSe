<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;

    $id = $_POST['id'];

    $rangoDe = str_replace("-", "/", $_POST['de']);
    $rangoHasta = str_replace("-", "/", $_POST['hasta'].' 24:00');

    if( !empty( $rangoDe ) ) {
        $searchRangoDe = " AND ms_fechaCreacion >= '$rangoDe' "; }
        else { $searchRangoDe = NULL; }
        
    if( !empty( $rangoHasta ) ) {
        $searchRangoHasta = " AND ms_fechaCreacion <= '$rangoHasta' "; }
        else { $searchRangoHasta = NULL; }

    //get number of rows
    
    $querySalidasSession = '';
        if($sessionRol==2){
            $querySalidasSession = "AND me_idSucursal = '$sessionUsuario'";
        } else if($sessionRol==4){
            $usuarioInventario = $con->query("SELECT ui_idSucursal FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();
            $querySalidasSession = "AND me_idSucursal = '$usuarioInventario[ui_idSucursal]'";
        }

    $salidasQuery = "SELECT * FROM materialessalida AS ms 
                        INNER JOIN materialesentrada AS me ON ms.ms_idMatEntrada = me.IDMatEntrada
                        WHERE ms_estado = '1' AND me_idMaterial = '$id' $querySalidasSession $searchRangoDe $searchRangoHasta
                        ORDER BY IDMatSalida DESC ";

    $rowSalidasCount = $con->query($salidasQuery)->num_rows;

//Initialize Pagination class and create object
    $pagConfig = array(
        'currentPage' => $start,
        'totalRows' => $rowSalidasCount,
        'perPage' => $numeroResultados,
        'link_func' => 'paginationMaterialSalidas'
    );
    $pagination =  new Pagination($pagConfig);

    //get rows
    $salidasSql = $con->query($salidasQuery." LIMIT $start,$numeroResultados");
    
?>
                    <table class="tableList">
                        <thead>
                            <tr>
                                <th>Cant.</th>
                                <th class="columnaCorta">Fecha</th>
                                <th>Usuario</th>
                                <th># Lote</th>
                                <th>Reg. Invima</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php while($salidasRow = $salidasSql->fetch_assoc()){

                            $rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$salidasRow[ms_idUsuario]'")->fetch_assoc();
                            $usID = $rol['us_id'];
                            if($rol['us_idRol']==1){
                                $usuario = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$usID'")->fetch_assoc();
                                $nombreUsuario = $usuario['cl_nombre'];
                            } else if($rol['us_idRol']==2){
                                $usuario = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$usID'")->fetch_assoc();
                                $nombreUsuario = $usuario['sc_nombre'];
                            } else {
                                $usuario = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$usID'")->fetch_assoc();
                                $nombreUsuario = $usuario['dc_nombres'];
                            }
                    ?>
                            <tr>
                                <td align="center"><?php echo $salidasRow['ms_cantidad'] ?></td>
                                <td class="columnaCorta"><?php echo $salidasRow['ms_fechaCreacion'] ?></td>
                                <td><?php echo $nombreUsuario ?></td>
                                <td align="center"><?php echo $salidasRow['me_numeroLote'] ?></td>
                                <td align="center"><?php echo $salidasRow['me_invima'] ?></td>
                                <td class="text-justify"><?php echo $salidasRow['ms_detalles'] ?></td>
                            </tr>
                    <?php } ?>
                        </tbody>
                    </table>
        <?php echo $pagination->createLinks(); ?>
<?php
} 
?>