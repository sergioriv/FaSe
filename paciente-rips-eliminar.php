<?php include'config.php';

$pacienteID = $_POST['pc'];
$id = $_POST['id'];
$estado = $_POST['es'];
$tipo = $_POST['tipo'];
if($estado==0){ $estado=1; } else { $estado=0; }
$query = $con->query("UPDATE pacientesrips SET prip_estado='$estado' WHERE IDPacRips = '$id'");
if($query){
	if($tipo==1){
?>
		<div class="contenedorAlerta">
			<input type="radio" id="alertExito">
			<label class="alerta exito" for="alertExito">
				<div>Sus cambios han sido guardados con exito.</div>
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

<?php } else {
		$rip = $con->query("SELECT rips.IDRips, rips.rip_codigo, pacientesrips.IDPacRips FROM rips, pacientesrips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.IDPacRips = '$id'")->fetch_assoc();
?>
		<div class="contenedorAlerta">
			<input type='radio' id='alertError'>
			<label class='alerta error' for='alertError'>
				<div>Se ha eliminado el RIPS <b><?php echo $rip['rip_codigo'] ?></b>.<a class="eliminarRips deshacer" id="<?php echo $id ?>" es="0" pc="<?php echo $pacienteID ?>" tipo="1">Deshacer</a></div>
				<div class="close">&times;</div>
			</label>
		</div>
		
<?php }
} else { ?>
	<div class="contenedorAlerta">
		<input type='radio' id='alertError'>
		<label class='alerta error' for='alertError'>
			<div>Error al eliminar.</div>
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
<?php } ?>

					            <div class="titulo tituloSecundario">Familiares</div>
                      <table class="tableList">
                        <thead>
                        <tr>
                          <th class="columnaCorta">CIE-10</th>
                          <th>Comentario</th>
                          <th class="columnaCorta">Fecha asignación</th>
                          <th>&nbsp</th>
                        </tr>
                      </thead>
                        <tbody>
                      <?php $pacienteRipsSql = $con->query("SELECT * FROM pacientesrips, rips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.prip_idPaciente = '$pacienteID' AND pacientesrips.prip_estado='1' AND pacientesrips.prip_area='1' ORDER BY pacientesrips.IDPacRips DESC");
                        while($pacienteRipsRow = $pacienteRipsSql->fetch_assoc()){
                      ?>
                          <tr>
                            <!--<td class="columnaIcon"></td>-->
                            <td><?php echo $pacienteRipsRow['rip_codigo']?></td>
                            <td><?php echo $pacienteRipsRow['prip_comentario']?></td>
                            <td><?php echo $pacienteRipsRow['prip_fechaCreacion'] ?></td>
                            <td class="tableOption">
                              <a title="Eliminar" class="eliminarRips eliminar" id="<?php echo $pacienteRipsRow[IDPacRips] ?>" es="1" pc="<?php echo $pacienteID ?>" tipo="0"><?php echo $iconoEliminar ?></a>
                            </td>
                          </tr>
                      <?php } ?>
                        </tbody>
                      </table>
                      <p style="border-bottom: 1px solid var(--colorGray)">&nbsp</p>
                      <p>&nbsp</p>
                      <div class="titulo tituloSecundario">Patológicos</div>
                      <table class="tableList">
                        <thead>
                        <tr>
                          <th class="columnaCorta">CIE-10</th>
                          <th>Comentario</th>
                          <th class="columnaCorta">Fecha asignación</th>
                          <th>&nbsp</th>
                        </tr>
                     </thead>
                        <tbody>
                      <?php $pacienteRipsSql = $con->query("SELECT * FROM pacientesrips, rips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.prip_idPaciente = '$pacienteID' AND pacientesrips.prip_estado='1' AND pacientesrips.prip_area='2' ORDER BY pacientesrips.IDPacRips DESC");
                        while($pacienteRipsRow = $pacienteRipsSql->fetch_assoc()){
                      ?>
                          <tr>
                            <!--<td class="columnaIcon"></td>-->
                            <td><?php echo $pacienteRipsRow['rip_codigo']?></td>
                            <td><?php echo $pacienteRipsRow['prip_comentario']?></td>
                            <td><?php echo $pacienteRipsRow['prip_fechaCreacion'] ?></td>
                            <td class="tableOption">
                              <a title="Eliminar" class="eliminarRips eliminar" id="<?php echo $pacienteRipsRow[IDPacRips] ?>" es="1" pc="<?php echo $pacienteID ?>" tipo="0"><?php echo $iconoEliminar ?></a>
                            </td>
                          </tr>
                      <?php } ?>
                        </tbody>
                      </table>
                      <p style="border-bottom: 1px solid var(--colorGray)">&nbsp</p>
                      <p>&nbsp</p>
                      <div class="titulo tituloSecundario">No Patológicos</div>
                      <table class="tableList">
                        <thead>
                        <tr>
                          <th>Nombre</th>
                          <th>Comentario</th>
                          <th class="columnaCorta">Fecha asignación</th>
                          <th>&nbsp</th>
                        </tr>
                     </thead>
                        <tbody>
                      <?php $pacienteNoPatSql = $con->query("SELECT * FROM pacientenopatologicos, nopatologicos WHERE pacientenopatologicos.pnp_idNoPatologico = nopatologicos.IDNoPatologico AND pacientenopatologicos.pnp_idPaciente = '$pacienteID' AND pacientenopatologicos.pnp_estado='1' ORDER BY pacientenopatologicos.IDpacNoPatologico DESC");
                              while($pacienteNoPatRow = $pacienteNoPatSql->fetch_assoc()){
                      ?>
                          <tr>
                            <!--<td class="columnaIcon"></td>-->
                            <td><?php echo $pacienteNoPatRow['np_nombre']?></td>
                                    <td><?php echo $pacienteNoPatRow['pnp_comentario']?></td>
                                    <td><?php echo $pacienteNoPatRow['pnp_fechaCreacion'] ?></td>
                            <td class="tableOption">
                              <a title="Eliminar" class="eliminarNoPat eliminar" id="<?php echo $pacienteNoPatRow[IDpacNoPatologico] ?>" es="1" pc="<?php echo $pacienteID ?>" tipo="0"><?php echo $iconoEliminar ?></a>
                            </td>
                          </tr>
                      <?php } ?>
                        </tbody>
                      </table>