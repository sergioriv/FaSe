<?php include'../config.php';

$ref = $_POST['ref'];
$id = explode('-', $_POST['id'] );

echo '<option value="0">-- Seleccionar --</option>';

$vendedores = $con->query("SELECT * FROM vendedores WHERE vn_idClinica = '$sessionClinica' AND vn_estado = '1' ORDER BY vn_nombre ASC ");
	
	echo '<optgroup label="Vendedores">';
	while($vendedoresRow = $vendedores->fetch_assoc()){
		if( $id[0] == 'V' && $id[1] == $vendedoresRow['IDVendedor'] ){
			$selected = 'selected';
		} else { $selected = ''; }
		echo '<option value="V-'.$vendedoresRow['IDVendedor'].'" '.$selected.'>'.$vendedoresRow['vn_nombre'].'</option>';

	}
	echo '</optgroup>';

$doctores = $con->query("SELECT * FROM doctores WHERE dc_idClinica = '$sessionClinica' AND dc_estado = '1' ORDER BY dc_nombres ASC ");
	
	echo '<optgroup label="Doctores">';
	while($doctoresRow = $doctores->fetch_assoc()){
		if( $id[0] == 'D' && $id[1] == $doctoresRow['IDDoctor'] ){
			$selected = 'selected';
		} else { $selected = ''; }
		echo '<option value="D-'.$doctoresRow['IDDoctor'].'" '.$selected.'>'.$doctoresRow['dc_nombres'].'</option>';

	}
	echo '</optgroup>';

if( $ref == 12 ){

	$pacientes = $con->query("SELECT * FROM pacientes WHERE pc_idClinica = '$sessionClinica' AND pc_estado = '1' ORDER BY pc_nombres ASC ");
	
		echo '<optgroup label="Pacientes">';
		while($pacientesRow = $pacientes->fetch_assoc()){
			if( $id[0] == 'P' && $id[1] == $pacientesRow['IDPaciente'] ){
				$selected = 'selected';
			} else { $selected = ''; }
			echo '<option value="P-'.$pacientesRow['IDPaciente'].'" '.$selected.'>'.$pacientesRow['pc_nombres'].'</option>';

		}
		echo '</optgroup>';
}
?>