<?php

$objPHPExcel->createSheet(3);

// COMBINAR CELDAS
$objPHPExcel->setActiveSheetIndex(3)
	->mergeCells('A1:C2');

// TITULOS PARA CELDAS
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('A1', $tituloReporte.' | Reactivos') // Titulo principal
    ->setCellValue('A4', "Fecha")
    ->setCellValue('B4', "Fuentes abiertas")
    ->setCellValue('C4', "Fuentes cerradas");

// SE AGREGAN LOS DATOS

$i = 5;
	for ($dia=1; $dia <= 31 ; $dia++) {
		$diaSirho = $dia < 10 ? '0'.$dia : $dia ;
		
		$abiertasNum = $con->query("SELECT SUM(shcl_cantidad) as abiertas FROM sirhoclinica 
		WHERE shcl_idSirho = '15' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$abiertas = $abiertasNum['abiertas']>0 ? $abiertasNum['abiertas'] : 0;

		$cerradasNum = $con->query("SELECT SUM(shcl_cantidad) as cerradas FROM sirhoclinica 
		WHERE shcl_idSirho = '16' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$cerradas = $cerradasNum['cerradas']>0 ? $cerradasNum['cerradas'] : 0;

		$objPHPExcel->setActiveSheetIndex(3)
        		->setCellValue('A'.$i, $anioSirho.'/'.$mesSirho.'/'.$diaSirho)
                ->setCellValue('B'.$i, $abiertas)
        		->setCellValue('C'.$i, $cerradas);

        $i++;
	}
    	

$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension('C')->setAutoSize(TRUE);

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Reactivos');

?>