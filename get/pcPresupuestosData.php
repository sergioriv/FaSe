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
    $presupuestosQuery = "SELECT * FROM presupuestos
        INNER JOIN plantratamientos ON presupuestos.pp_idPlan = plantratamientos.IDPlanTratamiento
        WHERE pp_idClinica='$sessionClinica' AND pp_idPaciente='$id' AND pp_estado='1' ORDER BY IDPresupuesto DESC";

    $rowCountPcPresupuestos = $con->query($presupuestosQuery)->num_rows;

//Initialize Pagination class and create object
                        $pagConfig = array(
                            'currentPage' => $start,
                            'totalRows' => $rowCountPcPresupuestos,
                            'perPage' => $numeroResultados,
                            'link_func' => 'paginationPcPresupuestos'
                        );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $presupuestosSql = $con->query($presupuestosQuery." LIMIT $start,$numeroResultados");
    
?>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                            <th class="estado"></th>
                                            <th class="columnaCorta">Fecha</th>
                                            <th>Creador</th>
                                            <th>Convenio</th>
                                            <th>No. Plan T/to.</th>
                                            <th>No. presupuesto</th>
                                            <th>Valor total</th>
                                            <th>&nbsp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($presupuestosRow = $presupuestosSql->fetch_assoc()){
                                            $presupuestoAprobado = 'estadoNeutro';
                                            $checkAprobado = '<i class="fa fa-check-square-o"></i>';
                                            if($presupuestosRow['pp_aprobado']==1){
                                                $presupuestoAprobado = 'estadoAprobado';
                                                $checkAprobado = '';
                                            }

                                            $convenioPresupuesto = $con->query("SELECT cnv_nombre, cnv_descuento FROM convenios WHERE IDConvenio = '$presupuestosRow[pp_idConvenio]'")->fetch_assoc();

                                            $creadorPresupuestoSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$presupuestosRow[pp_idUsuario]'")->fetch_assoc();

                                            $IDusuarioPresupuesto = $creadorPresupuestoSql['us_id'];
                                            $nombreUsuarioPresupuesto = '';
                                                if($creadorPresupuestoSql['us_idRol']==1){
                                                    $usuarioPresupuesto = $con->query("SELECT cl_nombre FROM clinicas WHERE IDClinica='$IDusuarioPresupuesto'")->fetch_assoc();
                                                    $nombreUsuarioPresupuesto = $usuarioPresupuesto['cl_nombre'];

                                                } elseif($creadorPresupuestoSql['us_idRol']==2){
                                                    $usuarioPresupuesto = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal='$IDusuarioPresupuesto'")->fetch_assoc();
                                                    $nombreUsuarioPresupuesto = $usuarioPresupuesto['sc_nombre'];

                                                } elseif($creadorPresupuestoSql['us_idRol']==3){
                                                    $usuarioPresupuesto = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor='$IDusuarioPresupuesto'")->fetch_assoc();
                                                    $nombreUsuarioPresupuesto = $usuarioPresupuesto['dc_nombres'];
                                                }

                                                $presupuestoEncodeGenerar = encrypt( 'id='.$presupuestosRow['IDPresupuesto'] );
                                        ?>
                                        <tr>
                                            <td class="estado <?php echo $presupuestoAprobado ?>">&nbsp</td>
                                            <td><?php echo $presupuestosRow['pp_fechaCreacion'] ?></td>
                                            <td><?php echo $nombreUsuarioPresupuesto ?></td>
                                            <td><?php echo $convenioPresupuesto['cnv_nombre'].' '.$convenioPresupuesto['cnv_descuento'].' %' ?></td>
                                            <td align="center"><?php echo $presupuestosRow['plt_consecutivo'] ?></td>
                                            <td align="center"><?php echo $presupuestosRow['pp_consecutivo'] ?></td>
                                            <td align="right"><?php echo '$'.number_format($presupuestosRow['pp_valorTotal'], 0, ".", ","); ?></td>
                                            <td class="tableOption">
                                                <a title="Aprobar" class="aprobarPresupuesto" id="<?php echo $presupuestosRow['IDPresupuesto'] ?>"><?php echo $checkAprobado ?></a>
                                                <a title="Descargar PDF" href="presupuesto-generar?q=<?= $presupuestoEncodeGenerar ?>"><i class="fa fa-download"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>
<?php
}
?>