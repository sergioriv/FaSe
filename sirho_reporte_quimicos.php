<?php

$objPHPExcel->createSheet(2);

// COMBINAR CELDAS
$objPHPExcel->setActiveSheetIndex(2)
	->mergeCells('A1:G2');

// TITULOS PARA CELDAS
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('A1', $tituloReporte.' | Quimicos') // Titulo principal
    ->setCellValue('A4', "Fecha")
    ->setCellValue('B4', "Farmicos")
    ->setCellValue('C4', "Citotoxicos")
    ->setCellValue('D4', "Metales pesados")
    ->setCellValue('E4', "Reactivos")
    ->setCellValue('F4', "Contenedores presurizados")
    ->setCellValue('G4', "Aceites usados");

// SE AGREGAN LOS DATOS

$i = 5;
	for ($dia=1; $dia <= 31 ; $dia++) {
		$diaSirho = $dia < 10 ? '0'.$dia : $dia ;
		
		$farmicosNum = $con->query("SELECT SUM(shcl_cantidad) as farmicos FROM sirhoclinica 
		WHERE shcl_idSirho = '9' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$farmicos = $farmicosNum['farmicos']>0 ? $farmicosNum['farmicos'] : 0;

		$citotoxicosNum = $con->query("SELECT SUM(shcl_cantidad) as citotoxicos FROM sirhoclinica 
		WHERE shcl_idSirho = '10' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$citotoxicos = $citotoxicosNum['citotoxicos']>0 ? $citotoxicosNum['citotoxicos'] : 0;

		$metalesNum = $con->query("SELECT SUM(shcl_cantidad) as metales FROM sirhoclinica 
		WHERE shcl_idSirho = '11' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$metales = $metalesNum['metales']>0 ? $metalesNum['metales'] : 0;

		$reactivosNum = $con->query("SELECT SUM(shcl_cantidad) as reactivos FROM sirhoclinica 
		WHERE shcl_idSirho = '12' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$reactivos = $reactivosNum['reactivos']>0 ? $reactivosNum['reactivos'] : 0;

		$presurizadosNum = $con->query("SELECT SUM(shcl_cantidad) as presurizados FROM sirhoclinica 
		WHERE shcl_idSirho = '13' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$presurizados = $presurizadosNum['presurizados']>0 ? $presurizadosNum['presurizados'] : 0;

		$aceitesNum = $con->query("SELECT SUM(shcl_cantidad) as aceites FROM sirhoclinica 
		WHERE shcl_idSirho = '14' AND YEAR(shcl_fechaCreacion)='$anioSirho' AND MONTH(shcl_fechaCreacion)='$mesSirho' AND DAY(shcl_fechaCreacion)='$diaSirho' ")->fetch_assoc();
		$aceites = $aceitesNum['aceites']>0 ? $aceitesNum['aceites'] : 0;

		$objPHPExcel->setActiveSheetIndex(2)
        		->setCellValue('A'.$i, $anioSirho.'/'.$mesSirho.'/'.$diaSirho)
                ->setCellValue('B'.$i, $farmicos)
        		->setCellValue('C'.$i, $citotoxicos)
        		->setCellValue('D'.$i, $metales)
        		->setCellValue('E'.$i, $reactivos)
        		->setCellValue('F'.$i, $presurizados)
        		->setCellValue('G'.$i, $aceites);

        $i++;
	}
    	

$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('C')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('D')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('E')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('F')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('G')->setAutoSize(TRUE);

// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Quimicos');

?>