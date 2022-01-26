<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    $semaforoRed = date ( 'Y-m-d' , strtotime ( '+30days' , strtotime ( $fechaHoy ) ) ) ;
    $semaforoYellow = date ( 'Y-m-d' , strtotime ( '+90days' , strtotime ( $fechaHoy ) ) ) ;
    $semaforoNeutro = $hoyAno.'-'.$hoyMes.'-'.$hoyDia;


    $materialesQuery = "SELECT * FROM materiales WHERE mt_idClinica = '$sessionClinica' AND mt_estado='1' AND (mt_codigo LIKE '%$busqueda%' OR mt_nombre LIKE '%$busqueda%') ORDER BY mt_codigo";
    /*

    //get number of rows
    if($sessionRol==1){
        $materialesQuery = "SELECT * FROM materiales, sucursales WHERE materiales.mt_idSucursal = sucursales.IDSucursal AND sucursales.sc_idClinica = '$sessionClinica' AND materiales.mt_estado='1' AND (materiales.mt_codigo LIKE '%$busqueda%' OR materiales.mt_nombre LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%') ORDER BY materiales.mt_codigo";
    } else if($sessionRol==2){
        $materialesQuery = "SELECT * FROM materiales, sucursales WHERE sucursales.sc_idClinica = '$sessionClinica' AND sucursales.IDSucursal='$sessionUsuario' AND materiales.mt_idSucursal = sucursales.IDSucursal AND materiales.mt_estado='1' AND (materiales.mt_codigo LIKE '%$busqueda%' OR materiales.mt_nombre LIKE '%$busqueda%') ORDER BY materiales.mt_codigo";
    } else if($sessionRol==4){
        $usuarioInventario = $con->query("SELECT * FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();

        $materialesQuery = "SELECT * FROM materiales, sucursales WHERE sucursales.sc_idClinica = '$sessionClinica' AND sucursales.IDSucursal='$usuarioInventario[ui_idSucursal]' AND materiales.mt_idSucursal = sucursales.IDSucursal AND materiales.mt_estado='1' AND (materiales.mt_codigo LIKE '%$busqueda%' OR materiales.mt_nombre LIKE '%$busqueda%') ORDER BY materiales.mt_codigo";
    }
    */

    $rowCount = $con->query($materialesQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationMateriales'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $materialesSql = $con->query($materialesQuery." LIMIT $start,$numeroResultados");
    
    $queryEntradaSession = '';
        if($sessionRol==2){
            $queryEntradaSession = "AND me_idSucursal = '$sessionUsuario'";
        } else if($sessionRol==4){
            $usuarioInventario = $con->query("SELECT ui_idSucursal FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();
            $queryEntradaSession = "AND me_idSucursal = '$usuarioInventario[ui_idSucursal]'";
        }
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th class="estado"></th>
                        <th>Cod.</th>
                        <th>Item</th>
                        <th>Cant.</th>
                        <th>Temp.</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($materialesRow = $materialesSql->fetch_assoc()){
                        $cantidadActual = 0;
                        $cantEntradas = 0;
                        $cantSalidas = 0;
                        $entradasSql = $con->query("SELECT IDMatEntrada, me_cantidad FROM materialesentrada WHERE me_idMaterial = '$materialesRow[IDMaterial]' $queryEntradaSession AND me_estado='1'");
                        while($entradasRow = $entradasSql->fetch_assoc()){
                            $cantEntradas += $entradasRow['me_cantidad'];

                            $salidasSql = $con->query("SELECT SUM(ms_cantidad) AS cantSalida FROM materialessalida WHERE ms_idMatEntrada = '$entradasRow[IDMatEntrada]' AND ms_estado='1'")->fetch_assoc();
                            $cantSalidas += $salidasSql['cantSalida'];
                        }

                        $cantidadActual = $cantEntradas - $cantSalidas;


                        if($materialesRow['mt_vencimiento'] == 0) { $estadoVendimiento = 'estadoNeutro'; }
                        else {
                            $querySemaforoNeutro = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial='$materialesRow[IDMaterial]' AND me_cero = 0 AND me_fechaVencimiento < '$semaforoNeutro' ORDER BY IDMatEntrada DESC")->num_rows;
                            $querySemaforoRed = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial='$materialesRow[IDMaterial]' AND me_cero = 0 AND me_fechaVencimiento <= '$semaforoRed' AND me_fechaVencimiento >= '$semaforoNeutro' ORDER BY IDMatEntrada DESC")->num_rows;
                            $querySemaforoYellow = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial='$materialesRow[IDMaterial]' AND me_cero = 0 AND me_fechaVencimiento <= '$semaforoYellow' AND me_fechaVencimiento >= '$semaforoNeutro' ORDER BY IDMatEntrada DESC")->num_rows;

                            if($querySemaforoRed >= 1 ) { $estadoVendimiento = 'semaforoRojo'; }
                            else if($querySemaforoYellow >= 1) { $estadoVendimiento = 'semaforoAmarillo'; }
                            else if($querySemaforoNeutro >= 1) { $estadoVendimiento = 'estadoNeutro'; }
                        }
                    ?>
                    <tr>
                        <td class="estado <?php echo $estadoVendimiento ?>">&nbsp</td>
                        <td><?php echo $materialesRow['mt_codigo']; ?></td>
                        <td><a id="<?php echo $materialesRow['IDMaterial'] ?>" class="consultorioEditar"><?php echo $materialesRow['mt_nombre']; ?></a></td>
                        <td class="centro"><?php echo $cantidadActual; ?></td>
                        <td><?php echo $materialesRow['mt_temperatura']; ?></td>
                        <td class="tableOption">
                            <a title="Editar" id="<?php echo $materialesRow['IDMaterial'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
                            <a title="Eliminar" id="<?php echo $materialesRow['IDMaterial'] ?>" t="material" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>