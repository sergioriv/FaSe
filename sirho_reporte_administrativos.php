<?php

$objPHPExcel->createSheet(4);

// COMBINAR CELDAS
$objPHPExcel->setActiveSheetIndex(4)
	->mergeCells('A1:G2');

// TITULOS PARA CELDAS
$objPHPExcel->setActiveSheetIndex(4)
    ->setCellValue('A1', $tituloReporte.' | Administrativos') // Titulo principal
    ->setCellValue('A4', "Fecha")
    ->setCellValue('B4', "Toner")
    ->setCellValue('C4', "Luminarias")
    ->setCellValue('D4', "Baterias")
    ->setCellValue('E4', "Raee")
    ->setCellValue('F4', "Balastos")
    ->setCellValue('G4', "Otros");

// SE AGREGAN LOS DATOS

$i = 5;
	for ($dia=1; $dia <= 31 ; $dia++) {
		$diaSirho = $dia < 10 ? '0'.$dia : $dia ;
		
		$tonerNum = $con->query("SELECT SUM(shcl_cantidad) as toner FROM sirhoclinica 
		WHERE shcl_idSirho = '17' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$toner = $tonerNum['toner']>0 ? $tonerNum['toner'] : 0;

		$luminariasNum = $con->query("SELECT SUM(shcl_cantidad) as luminarias FROM sirhoclinica 
		WHERE shcl_idSirho = '18' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$luminarias = $luminariasNum['luminarias']>0 ? $luminariasNum['luminarias'] : 0;

		$bateriasNum = $con->query("SELECT SUM(shcl_cantidad) as baterias FROM sirhoclinica 
		WHERE shcl_idSirho = '19' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$baterias = $bateriasNum['baterias']>0 ? $bateriasNum['baterias'] : 0;

		$raeeNum = $con->query("SELECT SUM(shcl_cantidad) as raee FROM sirhoclinica 
		WHERE shcl_idSirho = '20' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$raee = $raeeNum['raee']>0 ? $raeeNum['raee'] : 0;

		$balastosNum = $con->query("SELECT SUM(shcl_cantidad) as balastos FROM sirhoclinica 
		WHERE shcl_idSirho = '21' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$balastos = $balastosNum['balastos']>0 ? $balastosNum['balastos'] : 0;

		$otrosNum = $con->query("SELECT SUM(shcl_cantidad) as otros FROM sirhoclinica 
		WHERE shcl_idSirho = '22' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$otros = $otrosNum['otros']>0 ? $otrosNum['otros'] : 0;

		$objPHPExcel->setActiveSheetIndex(4)
        		->setCellValue('A'.$i, $anioSirho.'/'.$mesSirho.'/'.$diaSirho)
                ->setCellValue('B'.$i, $toner)
        		->setCellValue('C'.$i, $luminarias)
        		->setCellValue('D'.$i, $baterias)
        		->setCellValue('E'.$i, $raee)
        		->setCellValue('F'.$i, $balastos)
        		->setCellValue('G'.$i, $otros);

        $i++;
	}
    	

$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension('C')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension('D')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension('E')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension('F')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension('G')->setAutoSize(TRUE);

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Administrativos');

?>