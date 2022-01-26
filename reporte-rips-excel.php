<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';
 
 $anoRIP = $_POST['anio'];
 $mesRIP = $_POST['mes'];
 $cumplidas = $_POST['cumplidas'];
 if($cumplidas==1){
    $asistencia = 2;
    $tituloReporte = "Reporte RIPS $anoRIP-$mesRIP";
} else {
    $asistencia = 1;
    $tituloReporte = "Reporte RIPS $anoRIP-$mesRIP (citas no cumplidas)";
}

 $resultado = $con->query("SELECT * FROM citas, pacientes WHERE citas.ct_idPaciente = pacientes.IDPaciente AND pacientes.pc_idClinica='$sessionClinica' AND citas.ct_asistencia = '$asistencia' AND citas.ct_anoCita = '$anoRIP' AND citas.ct_mesCita = '$mesRIP'");

//if($resultado->num_rows > 0 ){

// Se crea el objeto PHPExcel
 $objPHPExcel = new PHPExcel();
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("FaSe | MantizTechnology") // Nombre del autor
    ->setLastModifiedBy("FaSe | MantizTechnology") //Ultimo usuario que lo modificó
    ->setTitle("Reporte RIPS") // Titulo
    ->setSubject("Reporte RIPS") //Asunto
    ->setDescription("Reporte RIPS") //Descripción
    ->setKeywords("reporte rips") //Etiquetas
    ->setCategory("reporte excel rips"); //Categorias

$titulosColumnas = array('Consecutivo', 'Dia', 'Cod. prestador de serv. de salud', 'Tipo Usuario', 'Tipo de Afiliado', 'Tipo de Ident.', 'No. Identificación', 'Primer Apellido', 'Segundo Apellido', 'Nombre', 'Ocupación', 'Edad', 'Sexo', 'Cod. Dpto.', 'Cod. Mpio.', 'Zona', 'Estado Civil', 'Código Consulta (CUP)', 'Finalidad de Consulta', 'Causa Externa', 'Cod. CIE 10 DX Ppal.', 'Cod CIE 10 DX Rel. 1', 'Cod CIE 10 DX Rel. 2', 'Cod CIE 10 DX Rel. 3', 'Firma');

// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
$objPHPExcel->setActiveSheetIndex(0)
	->mergeCells('A1:Y2');

// Se agregan los titulos del reporte
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
    ->setCellValue('I3',  $titulosColumnas[8])
    ->setCellValue('J3',  $titulosColumnas[9])
    ->setCellValue('K3',  $titulosColumnas[10])
    ->setCellValue('L3',  $titulosColumnas[11])
    ->setCellValue('M3',  $titulosColumnas[12])
    ->setCellValue('N3',  $titulosColumnas[13])
    ->setCellValue('O3',  $titulosColumnas[14])
    ->setCellValue('P3',  $titulosColumnas[15])
    ->setCellValue('Q3',  $titulosColumnas[16])
    ->setCellValue('R3',  $titulosColumnas[17])
    ->setCellValue('S3',  $titulosColumnas[18])
    ->setCellValue('T3',  $titulosColumnas[19])
    ->setCellValue('U3',  $titulosColumnas[20])
    ->setCellValue('V3',  $titulosColumnas[21])
    ->setCellValue('W3',  $titulosColumnas[22])
    ->setCellValue('X3',  $titulosColumnas[23])
    ->setCellValue('Y3',  $titulosColumnas[24]);


// rotacion de 90 grados para celdas
$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setTextRotation(90);
$objPHPExcel->getActiveSheet()->getStyle('L3')->getAlignment()->setTextRotation(90);
$objPHPExcel->getActiveSheet()->getStyle('M3')->getAlignment()->setTextRotation(90);
$objPHPExcel->getActiveSheet()->getStyle('P3')->getAlignment()->setTextRotation(90);


// alto de la fila para los titulos
$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(70);   //tiene 16 puntos menos

//Se agregan los datos de los alumnos
 
 $i = 4; //Numero de fila donde se va a comenzar a rellenar
 $consecutivo = 1;
 while ($fila = $resultado->fetch_assoc()) {
    $pacienteCUPS = "";
    $pacienteCIE = "";
    $pacienteCIE1 = "";
    $pacienteCIE2 = "";
    $pacienteCIE3 = "";

 	$eTDoc = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$fila[pc_idIdentificacion]'")->fetch_assoc();
    $eOcup = $con->query("SELECT * FROM ocupaciones WHERE IDOcupacion = '$fila[pc_idOcupacion]'")->fetch_assoc();
 	$eCiudad = $con->query("SELECT * FROM ciudades, departamentos WHERE ciudades.cd_idDep = departamentos.IDDepartamento AND ciudades.IDCiudad = '$fila[pc_idCiudad]'")->fetch_assoc();
    if($fila['ct_idRip']>0){
        $eRIPS = $con->query("SELECT * FROM rips WHERE IDRips = '$fila[ct_idRip]'")->fetch_assoc();
        $pacienteCIE = $eRIPS['rip_codigo'];
    }
    if($fila['ct_idRip1']>0){
        $eRIPS = $con->query("SELECT * FROM rips WHERE IDRips = '$fila[ct_idRip1]'")->fetch_assoc();
        $pacienteCIE1 = $eRIPS['rip_codigo'];
    } 
    if($fila['ct_idRip2']>0){
        $eRIPS = $con->query("SELECT * FROM rips WHERE IDRips = '$fila[ct_idRip2]'")->fetch_assoc();
        $pacienteCIE2 = $eRIPS['rip_codigo'];
    } 
    if($fila['ct_idRip3']>0){
        $eRIPS = $con->query("SELECT * FROM rips WHERE IDRips = '$fila[ct_idRip3]'")->fetch_assoc();
        $pacienteCIE3 = $eRIPS['rip_codigo'];
    } 
    if($fila['ct_idTratamiento']>0){
 	  $eCUPS = $con->query("SELECT * FROM cups, tratamientos WHERE tratamientos.tr_idCups = cups.IDCups AND tratamientos.IDTratamiento = '$fila[ct_idTratamiento]'")->fetch_assoc();
      $pacienteCUPS = $eCUPS['cup_codigo'];
    }
    $eSexo = $con->query("SELECT * FROM sexos WHERE IDSexo = '$fila[pc_idSexo]'")->fetch_assoc();
    $eCivil = $con->query("SELECT * FROM estadosciviles WHERE IDEstadoCivil = '$fila[pc_idEstadoCivil]'")->fetch_assoc();
    $eZona = $con->query("SELECT * FROM zonaresidencial WHERE IDZonaRes = '$fila[pc_idZona]'")->fetch_assoc();
    $eFinalidad = $con->query("SELECT * FROM finalidadconsulta WHERE IDFinalidadConsulta = '$fila[ct_idFinalidad]'")->fetch_assoc();
    $eCausaExterna = $con->query("SELECT * FROM causaexterna WHERE IDCausaExterna = '$fila[ct_idCausaExterna]'")->fetch_assoc();
 	
 	$fechaCita = $fila['ct_anoCita'].'/'.$fila['ct_mesCita'].'/'.$fila['ct_diaCita'];

     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $consecutivo)
         ->setCellValue('B'.$i, $fechaCita)
         ->setCellValue('C'.$i, $clinicaRow['cl_codigo'])
         ->setCellValue('D'.$i, $fila['pc_idRegimen'])
         ->setCellValue('E'.$i, $fila['pc_idAfiliacion'])
         ->setCellValue('F'.$i, $eTDoc['ti_nombre'])
         ->setCellValue('G'.$i, $fila['pc_identificacion'])
         ->setCellValue('H'.$i, $fila['pc_apellido1'])
         ->setCellValue('I'.$i, $fila['pc_apellido2'])
         ->setCellValue('J'.$i, $fila['pc_nombre'])
         ->setCellValue('K'.$i, $eOcup['ocu_codigo'])
         ->setCellValue('L'.$i, $fila['pc_edad'])
         ->setCellValue('M'.$i, $eSexo['sx_codigo'])
         ->setCellValue('N'.$i, $eCiudad['dp_codigo'])
         ->setCellValue('O'.$i, $eCiudad['cd_codigo'])
         ->setCellValue('P'.$i, $eZona['zr_codigo'])
         ->setCellValue('Q'.$i, $eCivil['ec_nombre'])
         ->setCellValue('R'.$i, $pacienteCUPS)
         ->setCellValue('S'.$i, $eFinalidad['fc_codigo'])
         ->setCellValue('T'.$i, $eCausaExterna['ce_codigo'])
         ->setCellValue('U'.$i, $pacienteCIE)
         ->setCellValue('V'.$i, $pacienteCIE1)
         ->setCellValue('W'.$i, $pacienteCIE2)
         ->setCellValue('X'.$i, $pacienteCIE3)
         ->setCellValue('Y'.$i, "");

     $i++; $consecutivo++;
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
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb' => '000000'
            )
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
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
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
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
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb' => '000000'
            )
        )
    ),
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
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
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
$objPHPExcel->getActiveSheet()->getStyle('A1:Y2')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:Y3')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:Y".($i-1));
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "A4:A".($i-1));
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "D4:E".($i-1));
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "L4:P".($i-1));
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "S4:T".($i-1));

// Aignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas ->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(false)->setWidth(18.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(false)->setWidth(8.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(false)->setWidth(8.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(false)->setWidth(8.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(false)->setWidth(13.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(false)->setWidth(3.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(false)->setWidth(3.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(false)->setWidth(7.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(false)->setWidth(7.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(false)->setWidth(3.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(false)->setWidth(10.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('S')->setAutoSize(false)->setWidth(13.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(false)->setWidth(8.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(false)->setWidth(11.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(false)->setWidth(11.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(false)->setWidth(11.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('X')->setAutoSize(false)->setWidth(11.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('Y')->setAutoSize(false)->setWidth(30.71);


// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('RIPS');
 
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