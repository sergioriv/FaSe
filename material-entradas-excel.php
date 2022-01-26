<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';

    $id = $_GET['id'];

    $rangoDe = str_replace("-", "/", $_GET['de']);
    $rangoHasta = str_replace("-", "/", $_GET['hasta']);

    $materialRow = $con->query("SELECT mt_codigo, mt_nombre FROM materiales WHERE IDMaterial = '$id'")->fetch_assoc();

    if( !empty( $rangoDe ) ) {
        $searchRangoDe = " AND me_fechaCreacion >= '$rangoDe' "; }
        else { $searchRangoDe = NULL; }
        
    if( !empty( $rangoHasta ) ) {
        $searchRangoHasta = " AND me_fechaCreacion <= '$rangoHasta' "; }
        else { $searchRangoHasta = NULL; }

    $queryEntradasSession = '';
                if($sessionRol==2){
                    $queryEntradasSession = "AND IDSucursal = '$sessionUsuario'";
                } else if($sessionRol==4){
                    $usuarioInventario = $con->query("SELECT ui_idSucursal FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();
                    $queryEntradasSession = "AND IDSucursal = '$usuarioInventario[ui_idSucursal]'";
                }


 	$entradasSql = $con->query("SELECT * FROM materialesentrada AS me 
                        INNER JOIN ordenesentrada AS ore ON me.me_idOrdenEntrada = ore.IDOrdenEntrada  
                        INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor 
                        INNER JOIN sucursales AS sc ON me.me_idSucursal = sc.IDSucursal
                        WHERE me_idMaterial = '$id' AND me_estado = '1' $queryEntradasSession $searchRangoDe $searchRangoHasta ORDER BY IDMatEntrada DESC ");

    $tituloDe = !empty($_GET['de']) ? ' - desde '.$_GET['de'] : '' ;
    $tituloHasta = !empty($_GET['hasta']) ? ' - hasta '.$_GET['hasta'] : '' ;

 	$tituloReporte = 'Entradas '.$materialRow['mt_codigo'].$tituloDe.$tituloHasta; 	

//if($resultado->num_rows > 0 ){

// Se crea el objeto PHPExcel
 $objPHPExcel = new PHPExcel();
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("FaSe | MantizTechnology") // Nombre del autor
    ->setLastModifiedBy("FaSe | MantizTechnology") //Ultimo usuario que lo modificó
    ->setTitle("Inventario entradas") // Titulo
    ->setSubject("Inventario entradas") //Asunto
    ->setDescription("Inventario entradas") //Descripción
    ->setKeywords("inventario entradas") //Etiquetas
    ->setCategory("reporte excel inventario entradas"); //Categorias

if($sessionRol==1){
    $titulosColumnas = array('Fecha', 'Usuario', 'Proveedor', 'Sucursal', 'Orden', 'Factura', 'Lote', 'Reg. Invima', 'Fecha vencimiento');
 
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A1:I2');

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',$tituloReporte) // Titulo del reporte
        ->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
        ->setCellValue('B3',  $titulosColumnas[1])
        ->setCellValue('C3',  $titulosColumnas[2])
        ->setCellValue('D3',  $titulosColumnas[3])
        ->setCellValue('E3',  $titulosColumnas[4])
        ->setCellValue('F3',  $titulosColumnas[5])
        ->setCellValue('G3',  $titulosColumnas[6])
        ->setCellValue('H3',  $titulosColumnas[7])
        ->setCellValue('I3',  $titulosColumnas[8]);

} else {
    $titulosColumnas = array('Fecha', 'Usuario', 'Proveedor', 'Orden', 'Factura', 'Lote', 'Reg. Invima', 'Fecha vencimiento');
 
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A1:H2');

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',$tituloReporte) // Titulo del reporte
        ->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
        ->setCellValue('B3',  $titulosColumnas[1])
        ->setCellValue('C3',  $titulosColumnas[2])
        ->setCellValue('D3',  $titulosColumnas[3])
        ->setCellValue('E3',  $titulosColumnas[4])
        ->setCellValue('F3',  $titulosColumnas[5])
        ->setCellValue('G3',  $titulosColumnas[6])
        ->setCellValue('H3',  $titulosColumnas[7]);
}

//Se agregan los datos de los alumnos
 
 $i = 4; //Numero de fila donde se va a comenzar a rellenar
 while ($fila = $entradasSql->fetch_assoc()) {

        $rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$fila[me_idUsuario]'")->fetch_assoc();
            $usID = $rol['us_id'];
            if($rol['us_idRol']==1){
                $usuario = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$usID'")->fetch_assoc();
                $nombreUsuario = $usuario['cl_nombre'];
            } else if($rol['us_idRol']==2){
                $usuario = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$usID'")->fetch_assoc();
                $nombreUsuario = $usuario['sc_nombre'];
            } else {
                $usuario = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$usID'")->fetch_assoc();
                $nombreUsuario = $usuario['dc_nombres'];
            }

    if($fila['mt_vencimiento'] == 1){
        $fechaVencimiento = $fila['me_fechaVencimiento'];
    } else {
        $fechaVencimiento = 'N/A';
    }

    if($sessionRol == 1){
        $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $fila['me_fechaCreacion'])
         ->setCellValue('B'.$i, $nombreUsuario)
         ->setCellValue('C'.$i, $fila['pr_nombre'])
         ->setCellValue('D'.$i, $fila['sc_nombre'])
         ->setCellValue('E'.$i, $fila['ore_numeroOrden'])
         ->setCellValue('F'.$i, $fila['ore_numeroFactura'])
         ->setCellValue('G'.$i, $fila['me_numeroLote'])
         ->setCellValue('H'.$i, $fila['me_invima'])
         ->setCellValue('I'.$i, $fechaVencimiento);
    } else {
        $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $fila['me_fechaCreacion'])
         ->setCellValue('B'.$i, $nombreUsuario)
         ->setCellValue('C'.$i, $fila['pr_nombre'])
         ->setCellValue('D'.$i, $fila['ore_numeroOrden'])
         ->setCellValue('E'.$i, $fila['ore_numeroFactura'])
         ->setCellValue('F'.$i, $fila['me_numeroLote'])
         ->setCellValue('G'.$i, $fila['me_invima'])
         ->setCellValue('H'.$i, $fechaVencimiento);
    }

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

if($sessionRol == 1){
    $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($estiloTituloColumnas);
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:I".($i-1));
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "A4:A".($i-1));
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "E4:I".($i-1));

    for($i = 'A'; $i <= 'I'; $i++){
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
    }
} else {
    $objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($estiloTituloColumnas);
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:H".($i-1));
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "A4:A".($i-1));
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "D4:H".($i-1));

    for($i = 'A'; $i <= 'H'; $i++){
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
    }
}
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('entradas');
 
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