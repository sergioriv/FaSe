<?php include'config.php'; include'encrypt.php';
require'vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


$get = explode("=", decrypt( $_GET['q']) );
$abonoID = $get[1];

if( $abonoID > 0 && $abonoID != null ){

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
        $html2pdf = new Html2Pdf('L', 'A5', 'es', true, 'UTF-8', array(0, 0, 0, 0));
        //$html2pdf->pdf->SetDisplayMode('fullpage');

        ob_start();
        include 'pdf-abono-orden.php';
        $content = ob_get_clean();
        $filename = 'Comprobante Egreso '.$abonoPR['pra_consecutivo'];

        $html2pdf->writeHTML($content);
    //    $html2pdf->createIndex('Sommaire', 30, 12, true, true, 1, null, '10mm');
        $html2pdf->output($filename.'.pdf', 'D'); //, 'D'

    } catch (Html2PdfException $e) {
        $html2pdf->clean();

        echo '<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Ocurrio un error al generar el PDF, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label></div>';
    }
} else {
    $_SESSION['consultoriosExito']=1;
    header("Location:$_SESSION[concultoriosAntes]");
}
?>