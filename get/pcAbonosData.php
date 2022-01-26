<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    include('../encrypt.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    
    //get number of rows
    $abonosPacienteQuery = "SELECT * FROM abonos, usuarios, sucursales WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.ab_idClinica='$sessionClinica' AND abonos.ab_idPaciente='$id' ORDER BY abonos.IDAbono DESC";

    $rowCountPcAbonos = $con->query($abonosPacienteQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountPcAbonos,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationPcAbonos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $abonosPacienteSql = $con->query($abonosPacienteQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th class="estado">&nbsp</th>
                                            <th>#</th>
                                            <th class="columnaCorta">Fecha</th>
                                            <th>Usuario</th>
                                            <th>Sucursal</th>
                                            <th>Comentario</th>
                                            <th>Forma Pago</th>
                                            <th align="right">Valor</th>
                                            <th>&nbsp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            while($abonosPacienteRow = $abonosPacienteSql->fetch_assoc()){
                                                $nombreUsuarioAbono = '';
                                                $IDusuarioAbono = $abonosPacienteRow['us_id'];
                                                if($abonosPacienteRow['us_idRol']==1){
                                                    $usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")->fetch_assoc();
                                                    $nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
                                                } elseif($abonosPacienteRow['us_idRol']==2){
                                                    $usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")->fetch_assoc();
                                                    $nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
                                                } elseif($abonosPacienteRow['us_idRol']==3){
                                                    $usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")->fetch_assoc();
                                                    $nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
                                                }

                                                if($abonosPacienteRow['ab_estado']==1){
                                                    $estadoAbono = 'estadoNeutro';
                                                } else {
                                                    $estadoAbono = 'estadoCancelado';
                                                }

                                                if($abonosPacienteRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
                                                } else { $iUS = ''; $cUS = ''; }

                                                if($abonosPacienteRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
                                                } else { $iSC = ''; $cSC = ''; }

                                                $formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosPacienteRow[ab_idFormaPago]'")->fetch_assoc();

                                                if($abonosPacienteRow['ab_idFormaPago']==2){
                                                    $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosPacienteRow[ab_idBanco]'")->fetch_assoc();
                                                    $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosPacienteRow['ab_cheque'];
                                                } else {
                                                    $abonoFormaPago = $formaPago['fp_nombre'];
                                                }
                                        ?>
                                        <tr>
                                            <td class="estado <?php echo $estadoAbono ?>"></td>
                                            <td align="right"><?php echo $abonosPacienteRow['ab_consecutivo'] ?></td>
                                            <td align="center"><?php echo $abonosPacienteRow['ab_fechaCreacion'] ?></td>
                                            <td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioAbono ?></td>
                                            <td class="<?php echo $cSC ?>"><?php echo $iSC.$abonosPacienteRow['sc_nombre'] ?></td>
                                            <td><?php echo $abonosPacienteRow['ab_comentario'] ?></td>
                                            <td align="center"><?php echo $abonoFormaPago ?></td>
                                            <td align="right"><?php echo '$'.number_format($abonosPacienteRow['ab_abono'], 0, ".", ","); ?></td>
                                            <td class="tableOption">
                                                <a title="Descargar PDF" href="paciente-abono-pdf.php?q=<?= encrypt( 'id='.$abonosPacienteRow['IDAbono'] ) ?>"><i class="fa fa-download"></i></a>
                                                <?php if($abonosPacienteRow['ab_estado']==1){ ?>
                                                    <a title="Editar"class="consultorioAbonoEditar" id="<?php echo $abonosPacienteRow['IDAbono'] ?>"><?php echo $iconoEditar ?></a>
                                                    <a title="Anular" class="anularAbono eliminar" id="<?php echo $abonosPacienteRow['IDAbono'] ?>" pc="<?php echo $id ?>"><?php echo $iconoEliminar ?></a>
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