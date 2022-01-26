<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;

    $id = $_POST['id'];

    $materialRow = $con->query("SELECT * FROM materiales WHERE IDMaterial = '$id'")->fetch_assoc();

    $semaforoRed = date ( 'Ymd' , strtotime ( '+30days' , strtotime ( $fechaHoy ) ) ) ;
    $semaforoYellow = date ( 'Ymd' , strtotime ( '+90days' , strtotime ( $fechaHoy ) ) ) ;


    $rangoDe = str_replace("-", "/", $_POST['de']);
    $rangoHasta = str_replace("-", "/", $_POST['hasta']);

    if( !empty( $rangoDe ) ) {
        $searchRangoDe = " AND me_fechaCreacion >= '$rangoDe' "; }
        else { $searchRangoDe = NULL; }
        
    if( !empty( $rangoHasta ) ) {
        $searchRangoHasta = " AND me_fechaCreacion <= '$rangoHasta' "; }
        else { $searchRangoHasta = NULL; }

    $queryEntradasSession = '';
                if($sessionRol==2){
                    $queryEntradasSession = "AND IDSucursal = '$sessionUsuario'";
                } else if($sessionRol==4){
                    $usuarioInventario = $con->query("SELECT ui_idSucursal FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();
                    $queryEntradasSession = "AND IDSucursal = '$usuarioInventario[ui_idSucursal]'";
                }
    
    $entradasQuery = "SELECT * FROM materialesentrada AS me 
                        INNER JOIN ordenesentrada AS ore ON me.me_idOrdenEntrada = ore.IDOrdenEntrada  
                        INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor 
                        INNER JOIN sucursales AS sc ON me.me_idSucursal = sc.IDSucursal
                        WHERE me_idMaterial = '$id' AND me_estado = '1' $queryEntradasSession $searchRangoDe $searchRangoHasta ORDER BY IDMatEntrada DESC ";

    $rowEntradasCount = $con->query($entradasQuery)->num_rows;

//Initialize Pagination class and create object
    $pagConfig = array(
        'currentPage' => $start,
        'totalRows' => $rowEntradasCount,
        'perPage' => $numeroResultados,
        'link_func' => 'paginationMaterialEntradas'
    );
    $pagination =  new Pagination($pagConfig);

    //get rows
    $entradasSql = $con->query($entradasQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <?php if($materialRow['mt_vencimiento'] == 1){ ?>
                                        <th class="estado">&nbsp</th>
                                    <?php } ?>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Proveedor</th>
                                <?php if($sessionRol==1){ ?>
                                    <th>Sucursal</th>
                                <?php } ?>
                                    <th># Orden</th>
                                    <th># Factura</th>
                                    <th># Lote</th>
                                    <th>Reg. Invima</th>
                                    <th>Cant.</th>
                                    <?php if($materialRow['mt_vencimiento']==1){ ?>
                                        <th class="columnaCorta">Vencimiento</th>
                                    <?php } ?>
                                    <th>&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php while($entradasRow = $entradasSql->fetch_assoc()){

                                $rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$entradasRow[me_idUsuario]'")->fetch_assoc();
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

                                if($materialRow['mt_vencimiento'] == 1){

                                    $fechaVencimiento = str_replace("/","", $entradasRow['me_fechaVencimiento']);                                    

                                    if($fechaVencimiento < $fechaHoySinEsp){ $estadoFechaVencimiento = 'estadoNeutro'; }
                                    else if($fechaVencimiento <= $semaforoRed) { $estadoFechaVencimiento = 'semaforoRojo'; }
                                    else if($fechaVencimiento <= $semaforoYellow) { $estadoFechaVencimiento = 'semaforoAmarillo'; }
                                    else { $estadoFechaVencimiento = 'semaforoVerde'; }
                                }

                                $cantidadActual = 0;
                                $cantidadSalidasSql = $con->query("SELECT SUM(ms_cantidad) AS cantSalida FROM materialessalida WHERE ms_idMatEntrada = '$entradasRow[IDMatEntrada]' ")->fetch_assoc();
                                $cantidadActual = $entradasRow['me_cantidad'] - $cantidadSalidasSql['cantSalida'];
                        ?>
                                <tr>
                                    <?php if($materialRow['mt_vencimiento'] == 1){ ?>
                                        <th class="estado <?php echo $estadoFechaVencimiento ?>">&nbsp</th>
                                    <?php } ?>
                                    <td><?php echo $entradasRow['me_fechaCreacion'] ?></td>
                                    <td><?php echo $nombreUsuario ?></td>
                                    <td><?php echo $entradasRow['pr_nombre'] ?></td>
                                <?php if($sessionRol==1){ ?>
                                    <td><?php echo $entradasRow['sc_nombre'] ?></td>
                                <?php } ?>
                                    <td align="center"><?php echo $entradasRow['ore_numeroOrden'] ?></td>
                                    <td align="center"><?php echo $entradasRow['ore_numeroFactura'] ?></td>
                                    <td align="center"><?php echo $entradasRow['me_numeroLote'] ?></td>
                                    <td align="center"><?php echo $entradasRow['me_invima'] ?></td>
                                    <td align="center" class="cantidadActual" id="<?php echo $entradasRow['IDMatEntrada'] ?>"><?php echo $cantidadActual .' / '. $entradasRow['me_cantidad'] ?></td>
                                    <?php if($materialRow['mt_vencimiento']==1){ ?>
                                        <td class="columnaCorta"><?php echo $entradasRow['me_fechaVencimiento'] ?></td>
                                    <?php } ?>
                                    <td class="tableOption">
                                        <a title="Ver Orden" class="ordenEntradaVer" data-id="<?= $entradasRow['IDOrdenEntrada'] ?>"><i class="fa fa-file-text"></i></a>
                                        <?php if( $cantidadActual > 0){ ?>
                                            <a title="Salida" id="<?php echo $entradasRow['IDMatEntrada'] ?>" class="consultorioSalida"><i class="fa fa-upload" aria-hidden="true"></i></a>
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