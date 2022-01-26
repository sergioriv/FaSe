<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;

    //get number of rows
    $proveedoresQuery = "SELECT DISTINCT IDProveedor, pr_nombre FROM proveedores AS pr
                            INNER JOIN ordenesentrada AS ore ON ore.ore_idProveedor = pr.IDProveedor
                            WHERE pr_estado = '1' AND ore_estado = 1 ORDER BY pr_nombre ASC ";

    $rowCountProveedores = $con->query($proveedoresQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountProveedores,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationDashProveedores'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $proveedoresSql = $con->query($proveedoresQuery." LIMIT $start,$numeroResultados");
   
?>
                            <table class="tableList">
                                    <tbody>
                                        <?php while($proveedoresRow = $proveedoresSql->fetch_assoc()){

                                        $cuentaProveedor = 0;
                                            $abonosCuenta = $con->query("SELECT SUM(pra_abono) AS abonos FROM ordenesabonos AS pra
                                                    INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada
                                                    WHERE ore_idProveedor = '$proveedoresRow[IDProveedor]' AND ore_estado = 1 AND pra_estado = 1")->fetch_assoc();

                                            $facturaCuenta = $con->query("SELECT SUM(ore_facturaValor) AS facturas FROM ordenesentrada WHERE ore_idProveedor = '$proveedoresRow[IDProveedor]' AND ore_estado = 1")->fetch_assoc();

                                            $cuentaProveedor = $facturaCuenta['facturas'] - $abonosCuenta['abonos'];

                                            $cuentaProveedor = '$'.number_format($cuentaProveedor, 0, ".", ",");
                                            
                                        ?>
                                        <tr>
                                            <td><a class="consultorioDashEditar" id="<?php echo $proveedoresRow['IDProveedor'] ?>" data-page="proveedor"><?php echo $proveedoresRow['pr_nombre'] ?></a></td>
                                            <td align="right"><?php echo $cuentaProveedor ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>