<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    include('../encrypt');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $id = $_POST['id'];
    
    //get number of rows
    $abonosSucuralQuery = "SELECT * FROM abonos, usuarios, pacientes WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idPaciente = pacientes.IDPaciente AND abonos.ab_idClinica='$sessionClinica' AND abonos.ab_idSucursal = '$id' ORDER BY abonos.IDAbono DESC";

    $rowCountScAbonos = $con->query($abonosSucuralQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountScAbonos,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationScAbonos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $abonosSucuralSql = $con->query($abonosSucuralQuery." LIMIT $start,$numeroResultados");
    
?>
                        <table class="tableList">
                            <thead>
                                <tr>
                                    <th class="estado">&nbsp</th>
                                    <th>#</th>
                                    <th class="columnaCorta">Fecha</th>
                                    <th>Usuario</th>
                                    <th>Paciente</th>
                                    <th>Forma Pago</th>
                                    <th align="right">Valor Abono</th>
                                    <th>&nbsp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    while($abonosSucuralRow = $abonosSucuralSql->fetch_assoc()){

                                        $nombreUsuarioAbono = '';
                                        $IDusuarioAbono = $abonosSucuralRow['us_id'];
                                        if($abonosSucuralRow['us_idRol']==1){
                                            $usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")->fetch_assoc();
                                            $nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
                                        } elseif($abonosSucuralRow['us_idRol']==2){
                                            $usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")->fetch_assoc();
                                            $nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
                                        } elseif($abonosSucuralRow['us_idRol']==3){
                                            $usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")->fetch_assoc();
                                            $nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
                                        }

                                        if($abonosSucuralRow['ab_estado']==1){
                                            $estadoAbono = 'estadoNeutro';
                                        } else {
                                            $estadoAbono = 'estadoCancelado';
                                        }

                                        if($abonosSucuralRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
                                        } else { $iUS = ''; $cUS = ''; }

                                        if($abonosSucuralRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
                                        } else { $iPC = ''; $cPC = ''; }

                                        $formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosSucuralRow[ab_idFormaPago]'")->fetch_assoc();

                                        if($abonosSucuralRow['ab_idFormaPago']==2){
                                            $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosSucuralRow[ab_idBanco]'")->fetch_assoc();
                                            $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosSucuralRow['ab_cheque'];
                                        } else {
                                            $abonoFormaPago = $formaPago['fp_nombre'];
                                        }
                                ?>
                                <tr>
                                    <td class="estado <?php echo $estadoAbono ?>"></td>
                                    <td align="right"><?php echo $abonosSucuralRow['ab_consecutivo'] ?></td>
                                    <td align="center"><?php echo $abonosSucuralRow['ab_fechaCreacion'] ?></td>
                                    <td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioAbono ?></td>
                                    <td class="<?php echo $cPC ?>"><?php echo $iPC.$abonosSucuralRow['pc_nombres'] ?></td>
                                    <td align="center"><?php echo $abonoFormaPago ?></td>
                                    <td align="right" class="columnaCorta"><?php echo '$'.number_format($abonosSucuralRow['ab_abono'], 0, ".", ","); ?></td>
                                    <td class="tableOption">
                                        <a href="paciente-abono-pdf.php?q=<?= encrypt( 'id='.$abonosSucuralRow['IDAbono'] ) ?>"><i class="fa fa-download"></i></a>
                                        <?php if($abonosSucuralRow['ab_estado']==1){ ?>
                                            <a class="consultorioAbonoEditar" id="<?php echo $abonosSucuralRow['IDAbono'] ?>" pc="<?php echo $abonosSucuralRow['IDPaciente'] ?>"><?php echo $iconoEditar ?></a>
                                            <a title="Anular" class="anularAbono eliminar" id="<?php echo $abonosSucuralRow['IDAbono'] ?>" pc="<?php echo $abonosSucuralRow['IDPaciente'] ?>"><?php echo $iconoEliminar ?></a>
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