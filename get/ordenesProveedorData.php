<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $proveedorID = trim($_POST['proveedorID']);
    
    //get number of rows
    $ordenesProveedorQuery = "SELECT * FROM ordenesentrada WHERE ore_idProveedor = '$proveedorID' AND ore_estado = 1 ORDER BY ore_pagada ASC, IDOrdenEntrada DESC ";

    $rowCountOrdenesProveedor = $con->query($ordenesProveedorQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountOrdenesProveedor,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationOrdenesProveedor'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $ordenesProveedorSql = $con->query($ordenesProveedorQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th class="estado"></th>
                                    <th class="columnaCorta">Fecha de Factura</th>
                                    <th class="columnaCorta">Vto. Factura</th>
                                    <th># Orden</th>
                                    <th># Factura</th>
                                    <th>Saldo</th>
                                    <th>Abonos</th>
                                    <th>Valor Factura</th>
                                    <th>&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while( $ordenesProveedorRow = $ordenesProveedorSql->fetch_assoc() ){
                                        $cuentaOrden = 0;
                                        $abonosOrden = $con->query("SELECT SUM(pra_abono) as abonos FROM ordenesabonos WHERE pra_idOrden = '$ordenesProveedorRow[IDOrdenEntrada]'")->fetch_assoc();
                                        $cuentaOrden = $ordenesProveedorRow['ore_facturaValor'] - $abonosOrden['abonos'];

                                        $valorSaldo = '$'.number_format($cuentaOrden, 0, ".", ",");
                                        $valorAbonos = '$'.number_format($abonosOrden['abonos'], 0, ".", ",");
                                        $valorFactura = '$'.number_format($ordenesProveedorRow['ore_facturaValor'], 0, ".", ",");

                                        $vencimientoFacturaInt = str_replace('/', '', $ordenesProveedorRow['ore_facturaFechaVencimiento']);

                                        $estadoOrden = 'estadoNeutro';
                                        $titleOrden = 'Pendiente';

                                        if( $ordenesProveedorRow['ore_pagada'] == 1 ){
                                            $estadoOrden = 'semaforoVerde';
                                            $titleOrden = 'Pagada';
                                        }

                                        if( $ordenesProveedorRow['ore_pagada'] == 0 && $vencimientoFacturaInt < $fechaHoySinEsp ){
                                            $estadoOrden = 'semaforoRojo';
                                            $titleOrden = 'Vencida';
                                        }
                                    ?>
                                    <tr>
                                        <td class="estado <?= $estadoOrden ?>" title="<?= $titleOrden ?>"></td>
                                        <td align="center"><?= $ordenesProveedorRow['ore_facturaFecha'] ?></td>
                                        <td align="center"><?= $ordenesProveedorRow['ore_facturaFechaVencimiento'] ?></td>
                                        <td align="center"><?= $ordenesProveedorRow['ore_numeroOrden'] ?></td>
                                        <td align="center"><?= $ordenesProveedorRow['ore_numeroFactura'] ?></td>
                                        <td align="right"><?= $valorSaldo ?></td>
                                        <td align="right"><?= $valorAbonos ?></td>
                                        <td align="right"><?= $valorFactura ?></td>
                                        <td class="tableOption">
                                            <?php if($ordenesProveedorRow['ore_pagada'] == 0){ ?>
                                                <a class="facturaAbono" data-id="<?= $ordenesProveedorRow['IDOrdenEntrada'] ?>"><?= $iconoNuevo ?></a>
                                            <?php } ?>
                                            <a class="ordenAbonosVer" data-id="<?= $ordenesProveedorRow['IDOrdenEntrada'] ?>"><i class="fa fa-usd" aria-hidden="true"></i></a>
                                            <a title="Ver Orden" class="ordenEntradaVer" data-id="<?= $ordenesProveedorRow['IDOrdenEntrada'] ?>"><i class="fa fa-file-text"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>