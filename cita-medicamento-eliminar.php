<?php include'config.php'; include('pagination-modal-params.php');

$medicamentoID = $_POST['id'];
$citaID = $_POST['ct'];

$query = $con->query("DELETE FROM citamedicamentos WHERE IDCitaMedicamento = '$medicamentoID'");

if($query){
?>
	<div class="contenedorAlerta">
      <input type="radio" id="alertExito">
      <label class="alerta exito" for="alertExito">
        <div>Se ha eliminado con exito.</div>
        <div class="close">&times;</div>
      </label>
      <script type="text/javascript">
      $(document).ready(function() {
          setTimeout(function() {
              $(".alerta").fadeOut(500);
          },3000);
      });
      </script>
    </div>
<?php } else { ?>
	<div class="contenedorAlerta">
      <input type='radio' id='alertError'>
      <label class='alerta error' for='alertError'>
        <div>Error al eliminar, Intentelo nuevamente.</div>
        <div class="close">&times;</div>
      </label>
      <script type="text/javascript">
      $(document).ready(function() {
          setTimeout(function() {
              $(".alerta").fadeOut(500);
          },3000);
      });
      </script>
    </div>
<?php }


    $citaMedicamentosQuery = "SELECT * FROM citamedicamentos, vadecum WHERE citamedicamentos.cm_idVadecum = vadecum.IDVadecum AND citamedicamentos.cm_idCita='$citaID' ORDER BY citamedicamentos.IDCitaMedicamento ASC";

    $rowCountCitaMedicamentos = $con->query($citaMedicamentosQuery)->num_rows;

//Initialize Pagination class and create object
                $pagConfig = array(
                  'totalRows' => $rowCountCitaMedicamentos,
                    'perPage' => $numeroResultados,
                  'link_func' => 'paginationCitaMedicamentos'
                );

    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $citaMedicamentosSql = $con->query($citaMedicamentosQuery." LIMIT $numeroResultados");

  ?>
                                <table class="tableList tableSinheight tablePadding">
                                    <thead>
                                      <tr>
                                        <th class="columnaCorta">Fecha asignación</th>
                                        <th>Cant.</th>
                                        <th>Medicamento</th>
                                        <th>&nbsp</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($citaMedicamentosRow = $citaMedicamentosSql->fetch_assoc()){
                                        ?>
                                        <tr>
                                            <td><?php echo $citaMedicamentosRow['cm_fechaCreacion'] ?></td>   
                                            <td><?php echo $citaMedicamentosRow['cm_cantidad'] ?></td>   
                                            <td class="selectMedicamento"><?php echo '<span>'.$citaMedicamentosRow['vd_medicamento']
                                                    .'</span><i>'.$citaMedicamentosRow['vd_presentacion'].'</i>' ?></td>
                                            <td class="tableOption">
                                              <a title="Eliminar" class="eliminarMedic eliminar" id="<?php echo $citaMedicamentosRow['IDCitaMedicamento'] ?>" ct="<?php echo $citaID ?>"><?php echo $iconoEliminar ?></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
        <?php echo $pagination->createLinks(); ?>