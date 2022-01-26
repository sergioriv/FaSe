<?php

$objPHPExcel->createSheet(3);

// COMBINAR CELDAS
$objPHPExcel->setActiveSheetIndex(3)
    ->mergeCells('A1:G2')
    ->mergeCells('A4:G4')
	->mergeCells('H4:O4');

// TITULOS PARA CELDAS
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('A1', $tituloReporte) // Titulo principal
    ->setCellValue('A4', "Información Cita") /* Subtitulos */
    ->setCellValue('H4', "Evolución")
    ->setCellValue('A5', "Fecha")
    ->setCellValue('B5', "Duración")
    ->setCellValue('C5', "Sucursal")
    ->setCellValue('D5', "Doctor")
    ->setCellValue('E5', "Tratamiento")
    ->setCellValue('F5', "Tipo")
    ->setCellValue('G5', "Estado")
    ->setCellValue('H5', "Asistencia")
    ->setCellValue('I5', "Finalidad")
    ->setCellValue('J5', "Causa Externa")
    ->setCellValue('K5', "CIE 10 DX Ppal.")
    ->setCellValue('L5', "CIE 10 DX Rel. 1")
    ->setCellValue('M5', "CIE 10 DX Rel. 2")
    ->setCellValue('N5', "CIE 10 DX Rel. 3")
    ->setCellValue('O5', "Descripción");

// SE AGREGAN LOS DATOS
$cit_i = 6;
	$historialCitas = $con->query("SELECT * FROM citas, sucursales, doctores, tratamientos WHERE citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$pacienteID' ORDER BY citas.ct_fechaOrden DESC");

	while($historialCitasRow = $historialCitas->fetch_assoc()){

        $HScie10 = $con->query("SELECT * FROM rips WHERE IDRips = '$historialCitasRow[ct_idRip]'")->fetch_assoc();
        $HScie10_1 = $con->query("SELECT * FROM rips WHERE IDRips = '$historialCitasRow[ct_idRip1]'")->fetch_assoc();
        $HScie10_2 = $con->query("SELECT * FROM rips WHERE IDRips = '$historialCitasRow[ct_idRip2]'")->fetch_assoc();
        $HScie10_3 = $con->query("SELECT * FROM rips WHERE IDRips = '$historialCitasRow[ct_idRip3]'")->fetch_assoc();
        $HScausaExterna = $con->query("SELECT * FROM causaexterna WHERE IDCausaExterna = '$historialCitasRow[ct_idCausaExterna]'")->fetch_assoc();
        $HSfinalidad = $con->query("SELECT * FROM finalidadconsulta WHERE IDFinalidadConsulta = '$historialCitasRow[ct_idFinalidad]'")->fetch_assoc();

		if($historialCitasRow['ct_control']==1){ $tipoCita = 'Primera'; }
		else { $tipoCita = 'Control'; }

		if( $historialCitasRow['ct_asistencia']==2){ $estadoCita = 'realizada'; }
        else
        if( $historialCitasRow['ct_asistencia']==1){ $estadoCita = 'sin asistencia'; }
        else
        if( $historialCitasRow['ct_evolucionada']==0 && ($historialCitasRow['ct_fechaInicio'].str_replace(':','',$historialCitasRow['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){ $estadoCita = 'sin evolucion'; }
        else
        if( $historialCitasRow['ct_estado']==1){ $estadoCita = 'confirmada'; }
        else
        if( $historialCitasRow['ct_estado']==2){ $estadoCita = 'cancelada'; }
        else { $estadoCita = 'creada'; }


        if($historialCitasRow['ct_asistencia'] > 0){ 
            if($historialCitasRow['ct_asistencia']==2){ $citaAsistencia = 'Si'; }
            else { $citaAsistencia = 'No'; }
        }
        else { $citaAsistencia = ''; }


		$objPHPExcel->setActiveSheetIndex(3)
        		->setCellValue('A'.$cit_i, $historialCitasRow['ct_anoCita'].'/'.$historialCitasRow['ct_mesCita'].'/'.$historialCitasRow['ct_diaCita'].' '.$historialCitasRow['ct_horaCita'])
                ->setCellValue('B'.$cit_i, $historialCitasRow['ct_duracion'])
        		->setCellValue('C'.$cit_i, $historialCitasRow['sc_nombre'])
        		->setCellValue('C'.$cit_i, $historialCitasRow['dc_nombres'])
        		->setCellValue('E'.$cit_i, $historialCitasRow['tr_nombre'])
        		->setCellValue('F'.$cit_i, $tipoCita)
                ->setCellValue('G'.$cit_i, $estadoCita)
                ->setCellValue('H'.$cit_i, $citaAsistencia)
                ->setCellValue('I'.$cit_i, $HSfinalidad['fc_nombre'])
                ->setCellValue('J'.$cit_i, $HScausaExterna['ce_nombre'])
                ->setCellValue('K'.$cit_i, $HScie10['rip_nombre'])
                ->setCellValue('L'.$cit_i, $HScie10_1['rip_nombre'])
                ->setCellValue('M'.$cit_i, $HScie10_2['rip_nombre'])
                ->setCellValue('N'.$cit_i, $HScie10_3['rip_nombre'])
        		->setCellValue('O'.$cit_i, $historialCitasRow['ct_descripcion']);
    	$cit_i++;
    }

$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('C')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('D')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('E')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('F')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('G')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('H')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('I')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('J')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('K')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('L')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('M')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('N')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('O')->setAutoSize(TRUE);


$objPHPExcel->setActiveSheetIndex(3)->getStyle('A4:O5')->applyFromArray($estiloEstomatologicos);

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('HISTORIAL CITAS');
?>