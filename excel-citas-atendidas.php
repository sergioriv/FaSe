<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';

    $rangoDe = str_replace("-", "", $_GET['de']);
    $rangoHasta = str_replace("-", "", $_GET['hasta']);

 	$citasAtendidasSql = $con->query("SELECT 
                                ct.ct_anoCita,
                                ct.ct_mesCita,
                                ct.ct_diaCita,
                                pc.pc_nombres,
                                dc.dc_nombres,
                                tr.tr_nombre,
                                ct.ct_costo,
                                ct.ct_trataPorcentaje
                                    FROM citas AS ct
                                    INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
                                    INNER JOIN doctores AS dc ON ct.ct_idDoctor = dc.IDDoctor
                                    INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
                                    WHERE ct.ct_idClinica = '$sessionClinica' AND ct.ct_evolucionada=1 AND ct.ct_estado IN(0,1) AND ct.ct_fechaInicio BETWEEN '$rangoDe' AND '$rangoHasta' ORDER BY IDCita DESC");

    $tituloDe = !empty($_GET['de']) ? $_GET['de'] : '' ;
    $tituloHasta = !empty($_GET['hasta']) ? ' - '.$_GET['hasta'] : '' ;

 	$tituloReporte = 'Citas atendidas '.$materialRow['mt_codigo'].$tituloDe.$tituloHasta; 	

//if($resultado->num_rows > 0 ){

// Se crea el objeto PHPExcel
 $objPHPExcel = new PHPExcel();
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("FaSe | MantizTechnology") // Nombre del autor
    ->setLastModifiedBy("FaSe | MantizTechnology") //Ultimo usuario que lo modificó
    ->setTitle("Citas atendidas") // Titulo
    ->setSubject("Citas atendidas") //Asunto
    ->setDescription("Citas atendidas") //Descripción
    ->setKeywords("citas atendidas") //Etiquetas
    ->setCategory("reporte excel citas atendidas"); //Categorias


    $titulosColumnas = array('Fecha de cita', 'Paciente', 'Doctor', 'Tratamiento', 'Valor total tratamiento', 'Porcentaje', 'Valor cita');
 
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A1:G2');

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',$tituloReporte) // Titulo del reporte
        ->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
        ->setCellValue('B3',  $titulosColumnas[1])
        ->setCellValue('C3',  $titulosColumnas[2])
        ->setCellValue('D3',  $titulosColumnas[3])
        ->setCellValue('E3',  $titulosColumnas[4])
        ->setCellValue('F3',  $titulosColumnas[5])
        ->setCellValue('G3',  $titulosColumnas[5]);

//Se agregan los datos de los alumnos
 
 $i = 4; //Numero de fila donde se va a comenzar a rellenar
 while ($fila = $citasAtendidasSql->fetch_assoc()) {

    $fechaCitaAtendida = $fila['ct_anoCita'].'/'.$fila['ct_mesCita'].'/'.$fila['ct_diaCita'];

    $valorCitaAtendidaOP = ( $fila['ct_costo'] * $fila['ct_trataPorcentaje'] ) / 100;

        $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $fechaCitaAtendida)
         ->setCellValue('B'.$i, $fila['pc_nombres'])
         ->setCellValue('C'.$i, $fila['dc_nombres'])
         ->setCellValue('D'.$i, $fila['tr_nombre'])
         ->setCellValue('E'.$i, $fila['ct_costo'])
         ->setCellValue('F'.$i, $fila['ct_trataPorcentaje'])
         ->setCellValue('G'.$i, $valorCitaAtendidaOP);

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

    $objPHPExcel->getActiveSheet()->getStyle('A1:G2')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($estiloTituloColumnas);
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:G".($i-1));
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "A4:A".($i-1));
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "F4:F".($i-1));
    $objPHPExcel->getActiveSheet()->getStyle("E3:E".($i-1))->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
    $objPHPExcel->getActiveSheet()->getStyle("G3:G".($i-1))->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");

    for($i = 'A'; $i <= 'G'; $i++){
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
    }

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('atendidas');
 
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