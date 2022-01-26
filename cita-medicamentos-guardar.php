<?php include'config.php'; include('pagination-modal-params.php');

$valVadecum = $_POST['valVadecum'];
$citaID = $_POST['citaID'];
$cantMedicamento = $_POST['cantMedicamento'];

$validarMedicamento = $con->query("SELECT * FROM citamedicamentos WHERE cm_idCita='$citaID' AND cm_idVadecum='$valVadecum'");

if($validarMedicamento->num_rows == 0){
  $query = $con->query("INSERT INTO citamedicamentos SET cm_idCita='$citaID', cm_cantidad='$cantMedicamento', cm_idVadecum='$valVadecum', cm_fechaCreacion='$fechaHoy'");

  if(!$query){
  ?>
  	  <div class="contenedorAlerta">
        <input type='radio' id='alertError'>
        <label class='alerta error' for='alertError'>
          <div>Error al agregar, Intentelo nuevamente.</div>
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
  } 

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