<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    include('../encrypt.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $busqueda = trim($_POST['busqueda']);
    
    //get number of rows
    $egresosQuery = "SELECT * FROM ordenesabonos AS pra
            INNER JOIN usuarios AS us ON pra.pra_idUsuario = us.IDUsuario
            INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada
            INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
            WHERE pra_idClinica = '$sessionClinica'
            AND ( pr.pr_nombre LIKE '%$busqueda%' OR ore.ore_numeroFactura LIKE '%$busqueda%' )
            ORDER BY IDOrdenAbono DESC";

    $rowCount = $con->query($egresosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationEgresos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $egresosSql = $con->query($egresosQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th class="estado">&nbsp</th>
                        <th>#</th>
                        <th class="columnaCorta">Fecha</th>
                        <th>Usuario</th>
                        <th>Proveedor</th>
                        <th># Factura</th>
                        <th>Forma Pago</th>
                        <th align="right">Valor</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($egresosRow = $egresosSql->fetch_assoc()){

                            $nombreUsuarioEgreso = '';
                            $IDusuarioEgreso = $egresosRow['us_id'];
                            if($egresosRow['us_idRol']==1){
                                $usuarioEgreso = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioEgreso'")
                                ->fetch_assoc();
                                $nombreUsuarioEgreso = $usuarioEgreso['cl_nombre'];
                            } elseif($egresosRow['us_idRol']==2){
                                $usuarioEgreso = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioEgreso'")
                                ->fetch_assoc();
                                $nombreUsuarioEgreso = $usuarioEgreso['sc_nombre'];
                            } elseif($egresosRow['us_idRol']==3){
                                $usuarioEgreso = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioEgreso'")
                                ->fetch_assoc();
                                $nombreUsuarioEgreso = $usuarioEgreso['dc_nombres'];
                            }

                            if($egresosRow['pra_estado']==1){
                                $estadoEgreso = 'estadoNeutro';
                            } else {
                                $estadoEgreso = 'estadoCancelado';
                            }

                            if($egresosRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
                            } else { $iUS = ''; $cUS = ''; }

                            $formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$egresosRow[pra_idFormaPago]'")->fetch_assoc();

                                    if($egresosRow['pra_idFormaPago']==2){
                                        $bancoEgreso = $con->query("SELECT * FROM bancos WHERE IDBanco = '$egresosRow[pra_idBanco]'")->fetch_assoc();
                                        $egresoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoEgreso['bnc_codigo'].' | '.$egresosRow['pra_cheque'];
                                    } else {
                                        $egresoFormaPago = $formaPago['fp_nombre'];
                                    }
                    ?>
                    <tr>
                        <td class="estado <?php echo $estadoEgreso ?>"></td>
                        <td align="right"><?php echo $egresosRow['pra_consecutivo'] ?></td>
                        <td align="center"><?php echo $egresosRow['pra_fechaCreacion'] ?></td>
                        <td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioEgreso ?></td>
                        <td><?php echo $egresosRow['pr_nombre'] ?></td>
                        <td align="center"><?php echo $egresosRow['ore_numeroFactura'] ?></td>
                        <td align="center"><?= $egresoFormaPago ?></td>
                        <td align="right"><?php echo '$'.number_format($egresosRow['pra_abono'], 0, ".", ","); ?></td>
                        <td class="tableOption">
                            <a title="Descargar" href="orden-abono-pdf.php?q=<?= encrypt( 'id='.$egresosRow['IDOrdenAbono'] ) ?>"><i class="fa fa-download"></i></a>
                            <?php if($egresosRow['pra_estado']==1){ ?>
                                <a title="Editar" class="consultorioEgresoEditar" id="<?php echo $egresosRow['IDOrdenAbono'] ?>"><?php echo $iconoEditar ?></a>
                                <a title="Anular" class="anularEgreso eliminar" id="<?php echo $egresosRow['IDOrdenAbono'] ?>"><?php echo $iconoEliminar ?></a>
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