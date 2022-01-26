<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $proveedorID = trim($_POST['proveedorID']);
    
    //get number of rows
    $facturasProveedorQuery = "SELECT IDMatEntrada, me_factura, me_facturaFecha, me_facturaValor, me_fechaCreacion FROM materialesentrada WHERE me_idProveedor = '$proveedorID' ORDER BY me_fechaCreacion DESC";

    $rowCount = $con->query($facturasProveedorQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationFacturasProveedor'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $facturasProveedorSql = $con->query($facturasProveedorQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th class="columnaCorta">Fecha de Factura</th>
                                    <th># Factura</th>
                                    <th>Valor Factura</th>
                                    <th>&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while( $facturasProveedorRow = $facturasProveedorSql->fetch_assoc() ){

                                    $facturaAbonos = $con->query("SELECT SUM(pra_abono) AS abonos FROM proveedores_abonos WHERE pra_idProveedor = '$proveedorID' AND pra_idFactura = '$facturasProveedorRow[IDMatEntrada]'")->fetch_assoc();

                                    $facturaDeuda = $facturasProveedorRow['me_facturaValor'] - $facturaAbonos['abonos'];

                                    $facturaValor = '$'.number_format($facturaDeuda, 2, ".", ",");
                                ?>
                                    <tr>
                                        <td><?= $facturasProveedorRow['me_facturaFecha'] ?></td>
                                        <td><?= $facturasProveedorRow['me_factura'] ?></td>
                                        <td><?= $facturaValor ?></td>
                                        <td class="tableOption">
                                            <a class="facturaAbono" data-id="<?= $facturasProveedorRow['IDMatEntrada'] ?>"><i class="fa fa-usd" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>