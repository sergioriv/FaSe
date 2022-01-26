<?php include('config.php');

$busqueda=trim(strtolower($_POST['buscar']));

if($busqueda!=""){

	 //se recibe la cadena que queremos buscar

	$busquedaMenuSql = $con->query("SELECT * FROM pacientes WHERE pc_idClinica='$sessionClinica' AND pc_estado='1' 
		AND ( pc_nombres LIKE '%".$busqueda."%' OR pc_identificacion LIKE '%".$busqueda."%' )");
	$numBusquedaMenu = $busquedaMenuSql->num_rows;
	if($numBusquedaMenu>0){
		echo'<div class="contenedorLista">';

		while($busquedaMenuRow = $busquedaMenuSql->fetch_assoc()){
			$menuPacienteUrl = str_replace(" ","-", $busquedaMenuRow['pc_nombres']);
	?>

			<div class="contenedorBusqueda">
				<div class="busquedaImagen">
					<?php
					    if($busquedaMenuRow['pc_foto']!=''){ echo "<img src='$busquedaMenuRow[pc_foto]'>"; }
					    else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
					?>
				</div>
				<div class="busquedaInfo">
					<div class="busquedaNombre"><a class="menuEditarPaciente" id="<?php echo $busquedaMenuRow['IDPaciente'] ?>"><?php echo $busquedaMenuRow ['pc_nombres']; ?></a></div>
					<div class="busquedaOpciones">
						<div><a onClick="location.href='cita.php?id=<?php echo $busquedaMenuRow["IDPaciente"] ?>&paciente=<?php echo $menuPacienteUrl ?>'">Nueva Cita</a></div>
					</div>
				</div>
			</div>


<?php
		}
	}

else{echo'<div style="display: none;">';}


echo "</div>";
}
?>


