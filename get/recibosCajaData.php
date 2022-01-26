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
    $abonosQuery = "SELECT * FROM abonos, usuarios, sucursales, pacientes WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.ab_idPaciente = pacientes.IDPaciente AND abonos.ab_idClinica='$sessionClinica' AND (sucursales.sc_nombre LIKE '%$busqueda%' OR pacientes.pc_nombres LIKE '%$busqueda%')  ORDER BY abonos.IDAbono DESC";

    $rowCount = $con->query($abonosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCount,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationRecibosCaja'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $abonosSql = $con->query($abonosQuery." LIMIT $start,$numeroResultados");
    
?>
            <table class="tableList">
                <thead>
                    <tr>
                        <th class="estado">&nbsp</th>
                        <th>#</th>
                        <th class="columnaCorta">Fecha</th>
                        <th>Usuario</th>
                        <th>Sucursal</th>
                        <th>Paciente</th>
                        <th>Forma Pago</th>
                        <th align="right">Valor</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($abonosRow = $abonosSql->fetch_assoc()){

                            $nombreUsuarioAbono = '';
                            $IDusuarioAbono = $abonosRow['us_id'];
                            if($abonosRow['us_idRol']==1){
                                $usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")
                                ->fetch_assoc();
                                $nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
                            } elseif($abonosRow['us_idRol']==2){
                                $usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")
                                ->fetch_assoc();
                                $nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
                            } elseif($abonosRow['us_idRol']==3){
                                $usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")
                                ->fetch_assoc();
                                $nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
                            }

                            if($abonosRow['ab_estado']==1){
                                $estadoAbono = 'estadoNeutro';
                            } else {
                                $estadoAbono = 'estadoCancelado';
                            }

                            if($abonosRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
                            } else { $iUS = ''; $cUS = ''; }
                            if($abonosRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
                            } else { $iSC = ''; $cSC = ''; }
                            if($abonosRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
                            } else { $iPC = ''; $cPC = ''; }

                            $formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosRow[ab_idFormaPago]'")->fetch_assoc();

                            if($abonosRow['ab_idFormaPago']==2){
                                $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosRow[ab_idBanco]'")->fetch_assoc();
                                $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosRow['ab_cheque'];
                            } else {
                                $abonoFormaPago = $formaPago['fp_nombre'];
                            }
                    ?>
                    <tr>
                        <td class="estado <?php echo $estadoAbono ?>"></td>
                        <td align="right"><?php echo $abonosRow['ab_consecutivo'] ?></td>
                        <td align="center"><?php echo $abonosRow['ab_fechaCreacion'] ?></td>
                        <td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioAbono ?></td>
                        <td class="<?php echo $cSC ?>"><?php echo $iSC.$abonosRow['sc_nombre'] ?></td>
                        <td class="<?php echo $cPC  ?>"><?php echo $iPC.$abonosRow['pc_nombres'] ?></td>
                        <td align="center"><?php echo $abonoFormaPago ?></td>
                        <td align="right"><?php echo '$'.number_format($abonosRow['ab_abono'], 0, ".", ","); ?></td>
                        <td class="tableOption">
                            <a title="Descargar" href="paciente-abono-pdf.php?q=<?= encrypt( 'id='.$abonosRow['IDAbono'] ) ?>"><i class="fa fa-download"></i></a>
                            <?php if($abonosRow['ab_estado']==1){ ?>
                                <a title="Editar" class="consultorioAbonoEditar" id="<?php echo $abonosRow['IDAbono'] ?>"><?php echo $iconoEditar ?></a>
                                <a title="Anular" class="anularAbono eliminar" id="<?php echo $abonosRow['IDAbono'] ?>"><?php echo $iconoEliminar ?></a>
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