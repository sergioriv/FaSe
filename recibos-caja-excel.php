<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';
 
$tituloReporte = "Recibos de caja";


$abonosSql = $con->query("SELECT * FROM abonos, usuarios, sucursales, pacientes WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.ab_idPaciente = pacientes.IDPaciente AND abonos.ab_idClinica='$sessionClinica' ORDER BY sucursales.sc_nombre ASC,abonos.IDAbono DESC");

//if($resultado->num_rows > 0 ){

// Se crea el objeto PHPExcel
 $objPHPExcel = new PHPExcel();
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("FaSe | MantizTechnology") // Nombre del autor
    ->setLastModifiedBy("FaSe | MantizTechnology") //Ultimo usuario que lo modificó
    ->setTitle("Recibos de caja") // Titulo
    ->setSubject("Recibos de caja") //Asunto
    ->setDescription("Recibos de caja") //Descripción
    ->setKeywords("recibos cajamenor abonos") //Etiquetas
    ->setCategory("reporte cajamenor abonos"); //Categorias

$titulosColumnas = array('#', 'Fecha', 'Usuario', 'Sucursal', 'Paciente', 'Valor', 'Anulado');

// Se combinan las celdas, para colocar ahí el titulo del reporte
$objPHPExcel->setActiveSheetIndex(0)
	->mergeCells('A1:G2');

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',$tituloReporte) // Titulo del reporte
    ->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
    ->setCellValue('B3',  $titulosColumnas[1])
    ->setCellValue('C3',  $titulosColumnas[2])
    ->setCellValue('D3',  $titulosColumnas[3])
    ->setCellValue('E3',  $titulosColumnas[4])
    ->setCellValue('F3',  $titulosColumnas[5])
    ->setCellValue('G3',  $titulosColumnas[6]);

//Se agregan los datos de los alumnos
 
 $i = 4; //Numero de fila donde se va a comenzar a rellenar
 while ($abonosRow = $abonosSql->fetch_assoc()) {

 	$nombreUsuarioAbono = '';
    $IDusuarioAbono = $abonosRow['us_id'];
    if($abonosRow['us_idRol']==1){
        $usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")
        ->fetch_assoc();
        $nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
    } elseif($abonosRow['us_idRol']==2){
        $usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")
        ->fetch_assoc();
        $nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
    } elseif($abonosRow['us_idRol']==3){
        $usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")
        ->fetch_assoc();
        $nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
    }

    if($abonosRow['ab_estado']==1){
        $estadoAbono = 'No';
    } else {
        $estadoAbono = 'Si';
    }

     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $abonosRow['ab_consecutivo'])
         ->setCellValue('B'.$i, $abonosRow['ab_fechaCreacion'])
         ->setCellValue('C'.$i, $nombreUsuarioAbono)
         ->setCellValue('D'.$i, $abonosRow['sc_nombre'])
         ->setCellValue('E'.$i, $abonosRow['pc_nombres'])
         ->setCellValue('F'.$i, $abonosRow['ab_abono'])
         ->setCellValue('G'.$i, $estadoAbono);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:G2')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:G".($i-1));
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "A4:B".($i-1));
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "G4:G".($i-1));
$objPHPExcel->getActiveSheet()->getStyle("F4:F".($i-1))->getNumberFormat()->setFormatCode
("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");

// Aignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas
for($i = 'A'; $i <= 'G'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
}
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Recibos de caja');
 
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