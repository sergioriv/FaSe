<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';

$fechaID = !empty($_GET['dia']) ? $_GET['dia'] : date('Y-m-d');
$fechaAR = explode('-', $fechaID);

   $formasPagoSql = $con->query("SELECT * FROM fomaspago");

   $tituloReporte = 'Egresos '.$fechaID;

//if($resultado->num_rows > 0 ){

// Se crea el objeto PHPExcel
 $objPHPExcel = new PHPExcel();
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("FaSe | MantizTechnology") // Nombre del autor
    ->setLastModifiedBy("FaSe | MantizTechnology") //Ultimo usuario que lo modificó
    ->setTitle("Reporte egresos") // Titulo
    ->setSubject("Reporte egresos") //Asunto
    ->setDescription("Reporte egresos") //Descripción
    ->setKeywords("reporte egresos") //Etiquetas
    ->setCategory("reporte excel egresos"); //Categorias

$titulosColumnas = array('Forma de pago', 'Valor');

// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
$objPHPExcel->setActiveSheetIndex(0)
	->mergeCells('A1:B2');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte) // Titulo del reporte
    ->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
    ->setCellValue('B3',  $titulosColumnas[1]);

//Se agregan los datos de los alumnos
 
 $i = 4; //Numero de fila donde se va a comenzar a rellenar
 while ($fila = $formasPagoSql->fetch_assoc()) {

    $abonosCE = $con->query("SELECT SUM(pra_abono) AS sumaCE FROM ordenesabonos WHERE pra_idClinica='$sessionClinica' AND pra_idFormaPago='$fila[IDFormaPago]' AND pra_estado=1 AND YEAR(pra_fechaCreacion)='$fechaAR[0]' AND MONTH(pra_fechaCreacion)='$fechaAR[1]' AND DAY(pra_fechaCreacion)='$fechaAR[2]'")->fetch_assoc();
    if($abonosCE['sumaCE'] == null){ $sumaCE = 0; }
        else { $sumaCE = $abonosCE['sumaCE']; }

     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $fila['fp_nombre'])
         ->setCellValue('B'.$i, $sumaCE);
     $i++;
 }

// ESTILOS
$estiloTituloReporte = array(
    'font' => array(
        'name'      => 'Calibri',
        'bold'      => true,
        'italic'    => false,
        'strike'    => false,
        'size' =>16,
        'color'     => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
      'type'  => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array(
            'argb' => 'f7f7f7')
  ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_NONE
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
);
 
$estiloTituloColumnas = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold'  => true,
        'size' =>11,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
      'type'  => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array(
            'argb' => 'f7f7f7')
    ),
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
            'color' => array(
                'rgb' => '000000'
            )
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
            'color' => array(
                'rgb' => '000000'
            )
        )
    ),
    'alignment' =>  array(
        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap'      => TRUE
    )
);
$estiloInformacion = new PHPExcel_Style();
$estiloInformacion->applyFromArray( array(
    'font' => array(
        'name'  => 'Calibri',
        'size' =>11,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN ,
      	'color' => array(
            'rgb' => '000000'
            )
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN ,
      	'color' => array(
            'rgb' => '000000'
            )
        )
    )
));
$estiloCentro = new PHPExcel_Style();
$estiloCentro->applyFromArray( array(
    'font' => array(
        'name'  => 'Calibri',
        'size' =>11,
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN ,
        'color' => array(
            'rgb' => '000000'
            )
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN ,
        'color' => array(
            'rgb' => '000000'
            )
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
));
//APLICAMOS LOS ESTILOS A CADA CELDA
$objPHPExcel->getActiveSheet()->getStyle('A1:B2')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:B".($i-1));
$objPHPExcel->getActiveSheet()->getStyle("B4:B".($i-1))->getNumberFormat()->setFormatCode
("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");

// Aignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas
for($i = 'A'; $i <= 'B'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
}
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle("$fechaID");
 
// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);
 
// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$tituloReporte.'.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>