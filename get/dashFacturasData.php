<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;

    //get number of rows
    $facturasQuery = "SELECT ore.ore_facturaFechaVencimiento, ore.ore_facturaValor, ore.IDOrdenEntrada, pr.IDProveedor, pr.pr_nombre FROM ordenesentrada AS ore
                                INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
                                WHERE ore.ore_idClinica = '$sessionClinica' AND ore.ore_pagada = 0 AND ore.ore_estado = 1 ORDER BY ore.ore_facturaFechaVencimiento ASC, ore.ore_numeroFactura ASC ";

    $rowCountFacturas = $con->query($facturasQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountFacturas,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashFacturas'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $facturasSql = $con->query($facturasQuery." LIMIT $start,$numeroResultados");
   
?>
                                <table class="tableList">
                                    <tbody>
                                        <?php while($facturasRow = $facturasSql->fetch_assoc()){

                                        $cuentaFactura = 0;
                                            $abonosFactura = $con->query("SELECT SUM(pra_abono) AS abonos FROM ordenesabonos WHERE pra_idOrden = '$facturasRow[IDOrdenEntrada]' AND pra_estado = 1")->fetch_assoc();

                                            $cuentaFactura = $facturasRow['ore_facturaValor'] - $abonosFactura['abonos'];

                                            $cuentaFactura = '$'.number_format($cuentaFactura, 0, ".", ",");
                                            
                                        $estadoFactura = 'estadoNeutro';

                                        $vencimientoFacturaInt = str_replace('/', '', $facturasRow['ore_facturaFechaVencimiento']);
                                        if( $vencimientoFacturaInt < $fechaHoySinEsp ){
                                            $estadoFactura = 'semaforoRojo';
                                        }
                                        ?>
                                        <tr>
                                            <td class="estado <?= $estadoFactura ?>"></td>
                                            <td><?php echo $facturasRow['ore_facturaFechaVencimiento'] ?></td>
                                            <td><a class="consultorioDashEditar" id="<?php echo $facturasRow['IDProveedor'] ?>" data-page="proveedor"><?php echo $facturasRow['pr_nombre'] ?></a></td>
                                            <td align="right"><?php echo $cuentaFactura ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>