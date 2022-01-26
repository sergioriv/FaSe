<?php include'config.php'; include('pagination-modal-params.php');

$comboTrata = $_POST['id'];
$IDCombo = $_POST['IDCombo'];

$query = $con->query("DELETE FROM combotratamientos WHERE IDComboTrata = '$comboTrata'");

if($query){
?>
  <script>$('#msj-comboTratamiento').html('<div class="contenedorAlerta"><input type="radio" id="alertExito"><label class="alerta exito" for="alertExito"><div>Se ha eliminado con exito.</div><div class="close">&times;</div></label></div>');</script>	
<?php } else { ?>
  <script>$('#msj-comboTratamiento').html('<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al guardar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label></div>');</script>	
<?php }


    $comboTratamientosQuery = "SELECT * FROM tratamientos AS tr 
        INNER JOIN combotratamientos AS cbt ON cbt.cbt_idTratamiento = tr.IDTratamiento 
        INNER JOIN fases AS fs ON tr.tr_idFase = fs.IDFase
        INNER JOIN cups ON tr.tr_idCups = cups.IDCups
        WHERE cbt_idCombo = '$IDCombo' AND tr_combo = '0' ORDER BY tr_nombre ASC";

          $rowCount = $con->query($comboTratamientosQuery)->num_rows;

      //Initialize Pagination class and create object
          $pagConfig = array(
          'totalRows' => $rowCount,
            'perPage' => $numeroResultados,
          'link_func' => 'paginationComboTratamientos'
        );
          $pagination =  new Pagination($pagConfig);

        $comboTratamientosSql = $con->query($comboTratamientosQuery." LIMIT $numeroResultados");

  ?>
                <table class="tableList">
                  <thead>
                    <tr>
                      <th class="columnaCorta">Precio</th>
                      <th>CUP</th>
                      <th>Fase</th>
                      <th>Tratamiento</th>
                      <th>&nbsp</th>
                    </tr>
                  </thead>
                    <tbody>
                <?php while($comboTratamientosRow = $comboTratamientosSql->fetch_assoc()){

                    if($comboTratamientosRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
                  } else { $iTR = ''; $cTR = ''; }
                ?>
                    <tr>
                      <td align="right"><?php echo '$'.number_format($comboTratamientosRow['cbt_precio'], 0, ".", ","); ?></td>
                      <td align="center"><?php echo $comboTratamientosRow['cup_codigo'] ?></td>
                      <td><?php echo $comboTratamientosRow['fs_nombre'] ?></td>
                      <td class="<?php echo $cTR ?>"><?php echo $iTR.$comboTratamientosRow['tr_nombre']; ?></td>
                      <td class="tableOption">
                        <a title="Eliminar" id="<?php echo $comboTratamientosRow['IDComboTrata'] ?>" t="comboTratamiento" class="eliminarTratamientoCombo eliminar" data-combo="<?php echo $IDCombo ?>"><?php echo $iconoEliminar ?></a>
                      </td>
                      </tr>
                <?php } ?>
                  </tbody>
                </table>
        <?php echo $pagination->createLinks(); ?>