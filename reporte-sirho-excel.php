<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';
 
 $anioSirho = $_POST['anio'];
 $mesSirho = $_POST['mes'];

    $tituloReporte = "Reporte Sirho ".$anioSirho."-".$mesSirho;


// Se crea el objeto PHPExcel
 $objPHPExcel = new PHPExcel();
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("FaSe | MantizTechnology") // Nombre del autor
    ->setLastModifiedBy("FaSe | MantizTechnology") //Ultimo usuario que lo modificó
    ->setTitle("Reporte sirho") // Titulo
    ->setSubject("Reporte sirho") //Asunto
    ->setDescription("Reporte sirho") //Descripción
    ->setKeywords("reporte sirho") //Etiquetas
    ->setCategory("reporte excel sirho"); //Categorias


// ESTILOS
$estilotitulo = array(
    'font' => array(
        'bold'      => true
    ),
    'alignment' => array(
        'horizontal'    => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'      => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation'      => 0,
        'wrap'          => TRUE
    )
);

include'sirho_reporte-no_peligrosos.php';
include'sirho_reporte_infecciosos.php';
include'sirho_reporte_quimicos.php';
include'sirho_reporte_reactivos.php';
include'sirho_reporte_administrativos.php';



// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);


// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$tituloReporte.'.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>