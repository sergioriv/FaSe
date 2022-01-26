<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    include'../encrypt.php';
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $ordenID = $_POST['id'];
    
    //get number of rows
    $abonosOrdenQuery = "SELECT * FROM ordenesabonos WHERE pra_idOrden = '$ordenID' AND pra_estado = 1 ORDER BY IDOrdenAbono DESC ";

    $rowCountAbonosOrden = $con->query($abonosOrdenQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountAbonosOrden,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationAbonosOrden'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $abonosOrdenSql = $con->query($abonosOrdenQuery." LIMIT $start,$numeroResultados");
    
?>
                <table class="tableList">
                    <thead>
                        <th>#</th>
                        <th class="columnaCorta">Fecha</th>
                        <th>Usuario</th>
                        <th>Comentario</th>
                        <th>Forma Pago</th>
                        <th>Valor</th>
                        <th>&nbsp</th>
                    </thead>
                    <tbody>
                        <?php while($abonosOrdenRow = $abonosOrdenSql->fetch_assoc()){

                                $rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$abonosOrdenRow[pra_idUsuario]'")->fetch_assoc();
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

                                $formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosOrdenRow[pra_idFormaPago]'")->fetch_assoc();

                                    if($abonosOrdenRow['pra_idFormaPago']==2){
                                        $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosOrdenRow[pra_idBanco]'")->fetch_assoc();
                                        $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosOrdenRow['pra_cheque'];
                                    } else {
                                        $abonoFormaPago = $formaPago['fp_nombre'];
                                    }
                        ?>
                            <tr>
                                <td align="right"><?= $abonosOrdenRow['pra_consecutivo'] ?></td>
                                <td><?= $abonosOrdenRow['pra_fechaCreacion'] ?></td>
                                <td><?= $nombreUsuario ?></td>
                                <td><?= $abonosOrdenRow['pra_comentario'] ?></td>
                                <td align="center"><?= $abonoFormaPago ?></td>
                                <td align="right"><?= '$'.number_format($abonosOrdenRow['pra_abono'], 0, '.', ',') ?></td>
                                <td class="tableOption">
                                    <a title="Descargar PDF" href="orden-abono-pdf.php?q=<?= encrypt( 'id='.$abonosPacienteRow['IDAbono'] ) ?>"><i class="fa fa-download"></i></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>