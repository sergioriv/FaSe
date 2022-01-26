<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';
 
 $pacienteID = $_GET['id'];

    $informacion = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$pacienteID'")->fetch_assoc();
    $tituloReporte = "Historia Clinica - ". $fechaHoy ." - ". $informacion['pc_nombres'];


// Se crea el objeto PHPExcel
 $objPHPExcel = new PHPExcel();
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("FaSe | MantizTechnology") // Nombre del autor
    ->setLastModifiedBy("FaSe | MantizTechnology") //Ultimo usuario que lo modificó
    ->setTitle("Reporte citas") // Titulo
    ->setSubject("Reporte citas") //Asunto
    ->setDescription("Reporte citas") //Descripción
    ->setKeywords("reporte citas") //Etiquetas
    ->setCategory("reporte excel citas"); //Categorias


// ESTILOS
$estiloEstomatologicos = array(
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

$estiloInformacion = array(
    'font' => array(
        'bold'      => true
    ),
    'alignment' => array(
        'horizontal'    => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical'      => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation'      => 0,
        'wrap'          => TRUE
    )
);


include'historia_clinica_informacion.php';
include'historia_clinica_antecedentes.php';
include'historia_clinica_estomatologico.php';
include'historia_clinica_citas.php';



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