<?php include'config.php';
/** Se agrega la libreria PHPExcel */
 require_once 'excel/PHPExcel.php';

$rangoDeQuery = str_replace('-', '', $_GET['de']);
$rangoHastaQuery = str_replace('-', '', $_GET['hasta']);

if(empty($_GET['de']) && empty($_GET['hasta'])){
    $rangoDe = " AND citas.ct_fechaInicio >= $fechaMesInicio "; 
    $rangoHasta = " AND citas.ct_fechaInicio <= $fechaHoySinEsp "; 

    $tituloRangoDe = date('Y/m').'/01';
    $tituloRangoHasta = date('Y/m/d');
} else {
    $rangoDe = !empty($_GET['de']) ? " AND citas.ct_fechaInicio >= $rangoDeQuery " : '' ; 
    $rangoHasta = !empty($_GET['hasta']) ? " AND citas.ct_fechaInicio <= $rangoHastaQuery " : '' ;

    $tituloRangoDe = $_GET['de'];
    $tituloRangoHasta = $_GET['hasta'];
}

if($sessionRol==1){
    $citasHistoricoQuery = $con->query("SELECT * FROM citas, sucursales, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' $rangoDe $rangoHasta AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC");
} else if($sessionRol==2){
    $citasHistoricoQuery = $con->query("SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$sessionUsuario' $rangoDe $rangoHasta AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente   AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC");
} else if($sessionRol==3){
    $citasHistoricoQuery = $con->query("SELECT * FROM citas, sucursales, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_idDoctor = '$sessionUsuario' AND citas.ct_terminado<='3' $rangoDe $rangoHasta AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idPaciente = pacientes.IDPaciente   AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC");
} else if($sessionRol==5){
    $userCitas = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas='$sessionUsuario'")->fetch_assoc();
    $citasHistoricoQuery = $con->query("SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$userCitas[uc_idSucursal]' $rangoDe $rangoHasta AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC");
}

    $tituloDe = !empty($tituloRangoDe) ? ' - desde '.$tituloRangoDe : '' ;
    $tituloHasta = !empty($tituloRangoHasta) ? ' - hasta '.$tituloRangoHasta : '' ;
 	$tituloReporte = 'Histórico Citas '.$tituloDe.$tituloHasta;

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

$titulosColumnas = array('Estado', 'Fecha de Cita', 'Duracion', 'Paciente', 'Sucursal', 'Doctor', 'Tratamiento', 'Terminado', 'Tipo de Cita');

// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
$objPHPExcel->setActiveSheetIndex(0)
	->mergeCells('A1:I2');

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
    ->setCellValue('I3',  $titulosColumnas[8]);

//Se agregan los datos de los alumnos
 
 $i = 4; //Numero de fila donde se va a comenzar a rellenar
 while ($fila = $citasHistoricoQuery->fetch_assoc()) {
    $cupCod="";
    
    if($fila['tr_idCups']!=0){
        $cup = $con->query("SELECT * FROM cups WHERE IDCups='$fila[tr_idCups]'")->fetch_assoc();
        $cupCod = $cup['cup_codigo'].' | ';
    }
 	$fechaCita = $fila['ct_anoCita'].'/'.$fila['ct_mesCita'].'/'.$fila['ct_diaCita'].' '.$fila['ct_horaCita'];


    if( $fila['ct_asistencia']==2){ $estadoCita = 'realizada'; }
    else
    if( $fila['ct_asistencia']==1){ $estadoCita = 'sin asistencia'; }
    else
    if( $fila['ct_evolucionada']==0 && ($fila['ct_fechaInicio'].str_replace(':','',$fila['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){ $estadoCita = 'sin evolucion'; }
    else
    if( $fila['ct_estado']==1){ $estadoCita = 'confirmada'; }
    else
    if( $fila['ct_estado']==2){ $estadoCita = 'cancelada'; }
    else { $estadoCita = 'creada'; }

    
    if($tratamientosRow['ct_terminado']==3){
		$trEstado = 'Terminado '.$fila['ct_terminadoFecha'];
	} else { $trEstado = 'Activo'; }

	if($fila['ct_control']==1){ $tipoCita = 'Primera'; }
	else { $tipoCita = 'Control'; }
     
     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, $estadoCita)
         ->setCellValue('B'.$i, $fechaCita)
         ->setCellValue('C'.$i, $fila['ct_duracion'])
         ->setCellValue('D'.$i, $fila['pc_nombres'])
         ->setCellValue('E'.$i, $fila['sc_nombre'])
         ->setCellValue('F'.$i, $fila['dc_nombres'])
         ->setCellValue('G'.$i, $cupCod.$fila['tr_nombre'])
         ->setCellValue('H'.$i, $trEstado)
         ->setCellValue('I'.$i, $tipoCita);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:I".($i-1));
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloCentro, "H4:I".($i-1));

// Aignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas
for($i = 'A'; $i <= 'I'; $i++){
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
}
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Histórico Citas');
 
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