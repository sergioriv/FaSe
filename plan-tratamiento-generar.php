<?php include'config.php'; include'encrypt.php';
require_once 'vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

//$presupuestoID = isset($_GET['id']) ? $_GET['id'] : $_SESSION['consultorioTmpPresupuestoID'];
$get = explode("=", decrypt( $_GET['q']) );
$planID = $get[1];

$planQuery = $con->query("SELECT plt_idPaciente, plt_consecutivo, plt_comentario, plt_firmaPaciente, plt_firmaUsuario FROM plantratamientos WHERE IDPlanTratamiento = '$planID'")->fetch_assoc();

$pacienteID = $planQuery['plt_idPaciente'];

if( $pacienteID > 0 && $pacienteID != null ){
	/**
	 * Html2Pdf Library - example
	 *
	 * HTML => PDF converter
	 * distributed under the OSL-3.0 License
	 *
	 * @package   Html2pdf
	 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
	 * @copyright 2017 Laurent MINGUET
	 */

	try {
	    $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
	    //$html2pdf->pdf->SetDisplayMode('fullpage');

	    ob_start();
	    include 'plan-tratamiento-pdf.php';
	    $content = ob_get_clean();

	    $html2pdf->writeHTML($content);
	//    $html2pdf->createIndex('Sommaire', 30, 12, true, true, 1, null, '10mm');
	    $html2pdf->output('Plan_tratamiento_'.$pacienteRow['pc_nombres'].'.pdf', 'D');

	} catch (Html2PdfException $e) {
	    $html2pdf->clean();

	    echo '<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Ocurrio un error al generar el PDF, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label></div>';
	}
} else {
    $_SESSION['consultoriosExito']=1;
    header("Location:$_SESSION[concultoriosAntes]");
}
?>