<?php

$objPHPExcel->createSheet(1);

// COMBINAR CELDAS
$objPHPExcel->setActiveSheetIndex(1)
	->mergeCells('A1:C2')
    ->mergeCells('A4:C4');

// TITULOS PARA CELDAS
$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A1', $tituloReporte) // Titulo principal
    ->setCellValue('A4', "FAMILIARES")  /* Subtitulos */
    ->setCellValue('A5', "Fecha")
    ->setCellValue('B5', "CIE-10")
    ->setCellValue('C5', "Comentario");

$objPHPExcel->setActiveSheetIndex(1)->getStyle('A4:C4')->applyFromArray($estiloInformacion);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('A5:C5')->applyFromArray($estiloEstomatologicos);

// SE AGREGAN LOS DATOS
$ant_i = 6;
	$antFamiliares = $con->query("SELECT * FROM pacientesrips, rips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.prip_idPaciente = '$pacienteID' AND pacientesrips.prip_estado='1' AND pacientesrips.prip_area='1' ORDER BY pacientesrips.IDPacRips DESC");
	while($antFamiliaresRow = $antFamiliares->fetch_assoc()){

		$objPHPExcel->setActiveSheetIndex(1)
        		->setCellValue('A'.$ant_i, $antFamiliaresRow['prip_fechaCreacion'])
        		->setCellValue('B'.$ant_i, $antFamiliaresRow['rip_nombre'])
        		->setCellValue('C'.$ant_i, $antFamiliaresRow['prip_comentario']);
    	$ant_i++;
	}

	$ant_i+=2 ;

	$objPHPExcel->setActiveSheetIndex(1)
	    ->mergeCells('A'.$ant_i.':C'.$ant_i);

	// TITULOS PARA CELDAS
	$objPHPExcel->setActiveSheetIndex(1)
	    ->setCellValue('A'.$ant_i, "PATOLOGICOS");  /* Subtitulos */
	    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$ant_i.':C'.$ant_i.'')->applyFromArray($estiloInformacion);

	$ant_i++;
	$objPHPExcel->setActiveSheetIndex(1)
	    ->setCellValue('A'.$ant_i, "Fecha")
	    ->setCellValue('B'.$ant_i, "CIE-10")
	    ->setCellValue('C'.$ant_i, "Comentario");
	    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$ant_i.':C'.$ant_i.'')->applyFromArray($estiloEstomatologicos);

	$ant_i++;
	$antPatologicos = $con->query("SELECT * FROM pacientesrips, rips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.prip_idPaciente = '$pacienteID' AND pacientesrips.prip_estado='1' AND pacientesrips.prip_area='2' ORDER BY pacientesrips.IDPacRips DESC");
	while($antPatologicosRow = $antPatologicos->fetch_assoc()){

		$objPHPExcel->setActiveSheetIndex(1)
        		->setCellValue('A'.$ant_i, $antPatologicosRow['prip_fechaCreacion'])
        		->setCellValue('B'.$ant_i, $antPatologicosRow['rip_nombre'])
        		->setCellValue('C'.$ant_i, $antPatologicosRow['prip_comentario']);
    	$ant_i++;
	}

	$ant_i+=2 ;

	$objPHPExcel->setActiveSheetIndex(1)
	    ->mergeCells('A'.$ant_i.':C'.$ant_i);

	// TITULOS PARA CELDAS
	$objPHPExcel->setActiveSheetIndex(1)
	    ->setCellValue('A'.$ant_i, "NO PATOLOGICOS");  /* Subtitulos */
	    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$ant_i.':C'.$ant_i.'')->applyFromArray($estiloInformacion);

	$ant_i++;
	$objPHPExcel->setActiveSheetIndex(1)
	    ->setCellValue('A'.$ant_i, "Fecha")
	    ->setCellValue('B'.$ant_i, "Nombre")
	    ->setCellValue('C'.$ant_i, "Comentario");
	    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$ant_i.':C'.$ant_i.'')->applyFromArray($estiloEstomatologicos);

	$ant_i++;
	$antNoPatologicos = $con->query("SELECT * FROM pacientenopatologicos, nopatologicos WHERE pacientenopatologicos.pnp_idNoPatologico = nopatologicos.IDNoPatologico AND pacientenopatologicos.pnp_idPaciente = '$pacienteID' AND pacientenopatologicos.pnp_estado='1' ORDER BY pacientenopatologicos.IDpacNoPatologico DESC");
	while($antNoPatologicosRow = $antNoPatologicos->fetch_assoc()){

		$objPHPExcel->setActiveSheetIndex(1)
        		->setCellValue('A'.$ant_i, $antNoPatologicosRow['pnp_fechaCreacion'])
        		->setCellValue('B'.$ant_i, $antNoPatologicosRow['np_nombre'])
        		->setCellValue('C'.$ant_i, $antNoPatologicosRow['pnp_comentario']);
    	$ant_i++;
	}


$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('C')->setAutoSize(TRUE);

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('ANTECEDENTES');

?>