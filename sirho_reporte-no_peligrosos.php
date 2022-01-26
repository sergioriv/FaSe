<?php

//$objPHPExcel->createSheet(0);

// COMBINAR CELDAS
$objPHPExcel->setActiveSheetIndex(0)
	->mergeCells('A1:E2');

// TITULOS PARA CELDAS
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', $tituloReporte.' | No Peligrosos') // Titulo principal
    ->setCellValue('A4', "Fecha")
    ->setCellValue('B4', "Biodegradables")
    ->setCellValue('C4', "Reciclables")
    ->setCellValue('D4', "Inertes")
    ->setCellValue('E4', "Ordinarios");

// SE AGREGAN LOS DATOS

$i = 5;
	for ($dia=1; $dia <= 31 ; $dia++) {
		$diaSirho = $dia < 10 ? '0'.$dia : $dia ;
		
		$biodegradablesNum = $con->query("SELECT SUM(shcl_cantidad) as biodegradables FROM sirhoclinica 
		WHERE shcl_idSirho = '1' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$biodegradables = $biodegradablesNum['biodegradables']>0 ? $biodegradablesNum['biodegradables'] : 0;

		$reciclablesNum = $con->query("SELECT SUM(shcl_cantidad) as reciclables FROM sirhoclinica 
		WHERE shcl_idSirho = '2' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$reciclables = $reciclablesNum['reciclables']>0 ? $reciclablesNum['reciclables'] : 0;

		$inertesNum = $con->query("SELECT SUM(shcl_cantidad) as inertes FROM sirhoclinica 
		WHERE shcl_idSirho = '3' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$inertes = $inertesNum['inertes']>0 ? $inertesNum['inertes'] : 0;

		$ordinariosNum = $con->query("SELECT SUM(shcl_cantidad) as ordinarios FROM sirhoclinica 
		WHERE shcl_idSirho = '4' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$ordinarios = $ordinariosNum['ordinarios']>0 ? $ordinariosNum['ordinarios'] : 0;

		$objPHPExcel->setActiveSheetIndex(0)
        		->setCellValue('A'.$i, $anioSirho.'/'.$mesSirho.'/'.$diaSirho)
                ->setCellValue('B'.$i, $biodegradables)
        		->setCellValue('C'.$i, $reciclables)
        		->setCellValue('D'.$i, $inertes)
        		->setCellValue('E'.$i, $ordinarios);

        $i++;
	}
    	

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(TRUE);

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('No peligrosos');

?>