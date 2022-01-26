<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';
 
$tituloReporte = "Comprobantes egreso";

$abonosEgresosSql = $con->query("SELECT * FROM ordenesabonos AS pra
            INNER JOIN usuarios AS us ON pra.pra_idUsuario = us.IDUsuario
            INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada
            INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
            WHERE pra_idClinica = '$sessionClinica'
            ORDER BY IDOrdenAbono DESC");

//if($resultado->num_rows > 0 ){

// Se crea el objeto PHPExcel
 $objPHPExcel = new PHPExcel();
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("FaSe | MantizTechnology") // Nombre del autor
    ->setLastModifiedBy("FaSe | MantizTechnology") //Ultimo usuario que lo modificó
    ->setTitle("Comprobantes egreso") // Titulo
    ->setSubject("Comprobantes egreso") //Asunto
    ->setDescription("Comprobantes egreso") //Descripción
    ->setKeywords("recibos cajamenor abonos egreso") //Etiquetas
    ->setCategory("reporte cajamenor abonos egreso"); //Categorias

$titulosColumnas = array('#', 'Fecha', 'Usuario', 'Proveedor', '#Factura', 'Valor', 'Anulado');

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
 while ($abonoEgresoRow = $abonosEgresosSql->fetch_assoc()) {

 	$nombreUsuarioAbono = '';
    $IDusuarioAbono = $abonoEgresoRow['us_id'];
    if($abonoEgresoRow['us_idRol']==1){
        $usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")
        ->fetch_assoc();
        $nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
    } elseif($abonoEgresoRow['us_idRol']==2){
        $usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")
        ->fetch_assoc();
        $nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
    } elseif($abonoEgresoRow['us_idRol']==3){
        $usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")
        ->fetch_assoc();
        $nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
    }

    if($abonoEgresoRow['pra_estado']==1){
        $estadoAbono = 'No';
    } else {
        $estadoAbono = 'Si';
    }

     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $abonoEgresoRow['pra_consecutivo'])
         ->setCellValue('B'.$i, $abonoEgresoRow['pra_fechaCreacion'])
         ->setCellValue('C'.$i, $nombreUsuarioAbono)
         ->setCellValue('D'.$i, $abonoEgresoRow['pr_nombre'])
         ->setCellValue('E'.$i, $abonoEgresoRow['ore_numeroFactura'])
         ->setCellValue('F'.$i, $abonoEgresoRow['pra_abono'])
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
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "E4:E".($i-1));
$objPHPExcel->getActiveSheet()->getStyle("F4:F".($i-1))->getNumberFormat()->setFormatCode
("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");

// Aignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas
for($i = 'A'; $i <= 'G'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
}
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Comprobantes egreso');
 
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