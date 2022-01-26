<?php

$objPHPExcel->createSheet(1);

// COMBINAR CELDAS
$objPHPExcel->setActiveSheetIndex(1)
	->mergeCells('A1:E2');

// TITULOS PARA CELDAS
$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A1', $tituloReporte.' | Infecciosos o de riesgo biologico') // Titulo principal
    ->setCellValue('A4', "Fecha")
    ->setCellValue('B4', "Biosanitarios")
    ->setCellValue('C4', "Anatomopatologicos")
    ->setCellValue('D4', "Cortopunzantes")
    ->setCellValue('E4', "De animales");

// SE AGREGAN LOS DATOS

$i = 5;
	for ($dia=1; $dia <= 31 ; $dia++) {
		$diaSirho = $dia < 10 ? '0'.$dia : $dia ;
		
		$biosanitariosNum = $con->query("SELECT SUM(shcl_cantidad) as biosanitarios FROM sirhoclinica 
		WHERE shcl_idSirho = '5' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$biosanitarios = $biosanitariosNum['biosanitarios']>0 ? $biosanitariosNum['biosanitarios'] : 0;

		$anatomopatologicosNum = $con->query("SELECT SUM(shcl_cantidad) as anatomopatologicos FROM sirhoclinica 
		WHERE shcl_idSirho = '6' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$anatomopatologicos = $anatomopatologicosNum['anatomopatologicos']>0 ? $anatomopatologicosNum['anatomopatologicos'] : 0;

		$cortopunzantesNum = $con->query("SELECT SUM(shcl_cantidad) as cortopunzantes FROM sirhoclinica 
		WHERE shcl_idSirho = '7' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$cortopunzantes = $cortopunzantesNum['cortopunzantes']>0 ? $cortopunzantesNum['cortopunzantes'] : 0;

		$animalesNum = $con->query("SELECT SUM(shcl_cantidad) as animales FROM sirhoclinica 
		WHERE shcl_idSirho = '8' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$animales = $animalesNum['animales']>0 ? $animalesNum['animales'] : 0;

		$objPHPExcel->setActiveSheetIndex(1)
        		->setCellValue('A'.$i, $anioSirho.'/'.$mesSirho.'/'.$diaSirho)
                ->setCellValue('B'.$i, $biosanitarios)
        		->setCellValue('C'.$i, $anatomopatologicos)
        		->setCellValue('D'.$i, $cortopunzantes)
        		->setCellValue('E'.$i, $animales);

        $i++;
	}
    	

$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('C')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('D')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('E')->setAutoSize(TRUE);

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Infecciosos');

?>