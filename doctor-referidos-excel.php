<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';

$id = $_GET['id'];

    $dcReferidoRangoDe = str_replace("-", "", $_GET['de']);
    $dcReferidoRangoHasta = str_replace("-", "", $_GET['hasta']);

    if( !empty( $dcReferidoRangoDe ) ) {
        $searchRangoDe = " AND ct.ct_fechaInicio >= '$dcReferidoRangoDe' "; }
        else { $searchRangoDe = NULL; }
        
    if( !empty( $dcReferidoRangoHasta ) ) {
        $searchRangoHasta = " AND ct.ct_fechaInicio <= '$dcReferidoRangoHasta' "; }
        else { $searchRangoHasta = NULL; }

    $doctorRow = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor = '$id'")->fetch_assoc();

    $dcReferidosSql = $con->query("SELECT * FROM citas AS ct
                                    INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
                                    INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
                                    WHERE pc.pc_idReferido = 'D-$id' AND ct.ct_inicial = '1' $searchRangoDe $searchRangoHasta ORDER BY ct.ct_fechaOrden DESC ");

    $tituloDe = !empty($dcReferidoRangoDe) ? ' - desde '.$_GET['de'] : '' ;
    $tituloHasta = !empty($dcReferidoRangoHasta) ? ' - hasta '.$_GET['hasta'] : '' ;
 	$tituloReporte = 'Referidos Doctor '.$doctorRow['dc_nombres'].$tituloDe.$tituloHasta;

//if($resultado->num_rows > 0 ){

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

$titulosColumnas = array('Fecha', 'Paciente', 'Tratamiento', 'Valor');

// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
$objPHPExcel->setActiveSheetIndex(0)
	->mergeCells('A1:D2');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte) // Titulo del reporte
    ->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2])
    ->setCellValue('D3',  $titulosColumnas[3]);

//Se agregan los datos de los alumnos
 
 $i = 4; //Numero de fila donde se va a comenzar a rellenar
 while ($fila = $dcReferidosSql->fetch_assoc()) {

    $fechaDcReferido = $fila['ct_anoCita'].'/'.$fila['ct_mesCita'].'/'.$fila['ct_diaCita'];
     
     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $fechaDcReferido)
         ->setCellValue('B'.$i, $fila['pc_nombres'])
         ->setCellValue('C'.$i, $fila['tr_nombre'])
         ->setCellValue('D'.$i, $fila['ct_costo']);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:D".($i-1));
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "A4:A".($i-1));
$objPHPExcel->getActiveSheet()->getStyle("D4:D".($i-1))->getNumberFormat()->setFormatCode
("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");

// Aignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas
for($i = 'A'; $i <= 'D'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
}
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Referidos');
 
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