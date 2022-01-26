<?php
function returnSimbolo(&$Fcon,$Fpaciente,$Fodontograma,$Fdiente,$Fsector){

	$odontograma = $Fcon->query("SELECT * FROM pacienteodontogramasector WHERE ods_idOdontograma='$Fodontograma' AND ods_diente='$Fdiente'")->fetch_assoc();
	$sectorDiente = $odontograma['ods_sector'.$Fsector];
	$convencion = $Fcon->query("SELECT * FROM convenciones WHERE IDConvencion='$sectorDiente'")->fetch_assoc();

	$return = "<span style='font-weight: bold; color:$convencion[cv_color]'>$convencion[cv_simbolo]</span>";
	return $return;	
}
function odontoDiente(&$Fcon,$Fpaciente,$Fodontograma,$Fdiente){

	$fondoDiente = $Fcon->query("SELECT * FROM pacienteodontogramasector AS ods INNER JOIN pacienteodontograma AS pod ON ods.ods_idOdontograma = pod.IDOdontograma WHERE pod_idPaciente='$Fpaciente' AND ods_diente='$Fdiente' AND ods_idOdontograma = '$Fodontograma'")->fetch_assoc();

echo '
						<table class="tableOdonto '.$fondoDiente['ods_classConvencion'].'" id="Fdiente-'.$Fdiente.'" border="0">
							<tr class="tituloDiente">
								<td colspan="3">'.$Fdiente.'</td>
							</tr>
							<tr>
								<td colspan="3" id="odonto-D'.$Fdiente.'-S1" class="odontogramaPart" diente="'.$Fdiente.'" sector="1">
									'.returnSimbolo($Fcon,$Fpaciente,$Fodontograma,$Fdiente,1).'
								</td>
							</tr>
							<tr>
								<td></td>
								<td id="odonto-D'.$Fdiente.'-S2" class="odontogramaPart" diente="'.$Fdiente.'" sector="2">
									'.returnSimbolo($Fcon,$Fpaciente,$Fodontograma,$Fdiente,2).'
								</td>
								<td></td>
							</tr>
							<tr>
								<td id="odonto-D'.$Fdiente.'-S3" class="odontogramaPart" diente="'.$Fdiente.'" sector="3">
									'.returnSimbolo($Fcon,$Fpaciente,$Fodontograma,$Fdiente,3).'
								</td>
								<td id="odonto-D'.$Fdiente.'-S4" class="odontogramaPart" diente="'.$Fdiente.'" sector="4">
									'.returnSimbolo($Fcon,$Fpaciente,$Fodontograma,$Fdiente,4).'
								</td>
								<td id="odonto-D'.$Fdiente.'-S5" class="odontogramaPart" diente="'.$Fdiente.'" sector="5">
									'.returnSimbolo($Fcon,$Fpaciente,$Fodontograma,$Fdiente,5).'
								</td>
							</tr>
							<tr>
								<td></td>
								<td id="odonto-D'.$Fdiente.'-S6" class="odontogramaPart" diente="'.$Fdiente.'" sector="6">
									'.returnSimbolo($Fcon,$Fpaciente,$Fodontograma,$Fdiente,6).'
								</td>
								<td></td>
							</tr>
							<tr>
								<td id="odonto-D'.$Fdiente.'-S7" colspan="3" class="odontogramaPart" diente="'.$Fdiente.'" sector="7">
									'.returnSimbolo($Fcon,$Fpaciente,$Fodontograma,$Fdiente,7).'
								</td>
							</tr>
						</table>
					';

}
?>