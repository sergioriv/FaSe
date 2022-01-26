<?php
if(isset($_POST['page'])){
    //Include Pagination class file
    include('../pagination-modal-params.php');
    
    //Include database configuration file
    include('../config.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $ordenID = trim($_POST['id']);

        $inventarioQuery = "SELECT sc.sc_nombre, mt.mt_codigo, mt.mt_nombre, me.* FROM materialesentrada AS me
                    INNER JOIN materiales AS mt ON me.me_idMaterial = mt.IDMaterial
                    INNER JOIN sucursales AS sc ON me.me_idSucursal = sc.IDSucursal
                    WHERE me_idOrdenEntrada = '$ordenID' AND me_estado = '1' ORDER BY IDMatEntrada DESC ";

            $rowCount = $con->query($inventarioQuery)->num_rows;
                $pagConfig = array(
                    'currentPage' => $start,
                    'totalRows' => $rowCount,
                    'perPage' => $numeroResultados,
                    'link_func' => 'paginationInventarioOrden'
                );
                $pagination =  new Pagination($pagConfig);

            $inventarioSql = $con->query($inventarioQuery." LIMIT $start,$numeroResultados");
?>
                <table class="tableList">
                    <thead>
                        <th>Usuario</th>
                        <th>Sucursal</th>
                        <th>Item</th>
                        <th># Lote</th>
                        <th>Reg. Invima</th>
                        <th>Cant.</th>
                        <th class="columnaCorta">Vencimiento</th>
                    </thead>
                    <tbody>
                        <?php while($inventarioRow = $inventarioSql->fetch_assoc()){

                                $rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$inventarioRow[me_idUsuario]'")->fetch_assoc();
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
                        ?>
                            <tr>
                                <td><?= $nombreUsuario ?></td>
                                <td><?= $inventarioRow['sc_nombre'] ?></td>
                                <td><?= $inventarioRow['mt_codigo'].' | '.$inventarioRow['mt_nombre'] ?></td>
                                <td align="center"><?= $inventarioRow['me_numeroLote'] ?></td>
                                <td align="center"><?= $inventarioRow['me_invima'] ?></td>
                                <td align="center"><?= $inventarioRow['me_cantidad'] ?></td>
                                <td align="center"><?= $inventarioRow['me_fechaVencimiento'] ?></td>
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