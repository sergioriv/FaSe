<?php

$objPHPExcel->createSheet(2);

// COMBINAR CELDAS
$objPHPExcel->setActiveSheetIndex(2)
	->mergeCells('A1:E2')
	->mergeCells('A9:B9')
	->mergeCells('A18:B18')
	->mergeCells('D4:E4');

// TITULOS PARA CELDAS
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('A1', $tituloReporte) // Titulo principal
    ->setCellValue('A4', "Higiene Oral")  /* Subtitulos */
    ->setCellValue('A5', "Seda Dental")
    ->setCellValue('A6', "Cepillo Dental")
    ->setCellValue('A7', "Enjuagues Bucales")
    ->setCellValue('A8', "Cuántas veces al día")
    ->setCellValue('A9', "Examen Dental")
    ->setCellValue('A10', "Supernumerarios")
    ->setCellValue('A11', "Abrasion")
    ->setCellValue('A12', "Manchas - Canbio de Color")
    ->setCellValue('A13', "Patología Pulpar - Abcesos")
    ->setCellValue('A14', "Maloclusiones")
    ->setCellValue('A15', "Incluidos")
    ->setCellValue('A16', "Trauma")
    ->setCellValue('A17', "Habitos")
    ->setCellValue('A18', "Examen Periodontal")
    ->setCellValue('A19', "Bolsas - Movilidad")
    ->setCellValue('A20', "Placa Blanda")
    ->setCellValue('A21', "Calculos")
    ->setCellValue('D4', "Tejidos Blandos")
    ->setCellValue('D5', "A.T.M")
    ->setCellValue('D6', "Labios")
    ->setCellValue('D7', "Lengua")
    ->setCellValue('D8', "Paladar")
    ->setCellValue('D9', "Piso de Boca")
    ->setCellValue('D10', "Carrillos")
    ->setCellValue('D11', "Glandulas Salivares")
    ->setCellValue('D12', "Maxilares")
    ->setCellValue('D13', "Senos Maxilares")
    ->setCellValue('D14', "Musculos Masticadores")
    ->setCellValue('D15', "Ganglios")
    ->setCellValue('D16', "Oclusion")
    ->setCellValue('D17', "Frenillos")
    ->setCellValue('D18', "Mucosas")
    ->setCellValue('D19', "Encías")
    ->setCellValue('D20', "Amigdalas")
    ->setCellValue('A23', "Observaciones");


$estomat = $con->query("SELECT * FROM evolucionpaciente WHERE ev_idPaciente = '$pacienteID'")->fetch_assoc();
	
	if($estomat['ev_higieneOral']==3) { $ev_higieneOral = "Bueno"; }
    else if($estomat['ev_higieneOral']==2) { $ev_higieneOral = "Regular"; }
    else if($estomat['ev_higieneOral']==1) { $ev_higieneOral = "Malo"; } 
    else { $ev_higieneOral = ''; }

    if($estomat['ev_seda']==2){ $ev_seda = "Si"; }
    else if($estomat['ev_seda']==1){ $ev_seda = "No"; }
    else { $ev_seda = ''; }

    if($estomat['ev_cepillo']==2){ $ev_cepillo = "Si"; }
    else if($estomat['ev_cepillo']==1){ $ev_cepillo = "No"; }
    else { $ev_cepillo = ''; }

    if($estomat['ev_enjuagues']==2){ $ev_enjuagues = "Si"; }
    else if($estomat['ev_enjuagues']==1){ $ev_enjuagues = "No"; }
    else { $ev_enjuagues = ''; }

    if($estomat['ev_superNumerarios']==2){ $ev_superNumerarios = "Si"; }
    else if($estomat['ev_superNumerarios']==1){ $ev_superNumerarios = "No"; }
    else { $ev_superNumerarios = ''; }

    if($estomat['ev_abrasion']==2){ $ev_abrasion = "Si"; }
    else if($estomat['ev_abrasion']==1){ $ev_abrasion = "No"; }
    else { $ev_abrasion = ''; }

    if($estomat['ev_manchas']==2){ $ev_manchas = "Si"; }
    else if($estomat['ev_manchas']==1){ $ev_manchas = "No"; }
    else { $ev_manchas = ''; }

    if($estomat['ev_patologiaPulpar']==2){ $ev_patologiaPulpar = "Si"; }
    else if($estomat['ev_patologiaPulpar']==1){ $ev_patologiaPulpar = "No"; }
    else { $ev_patologiaPulpar = ''; }

    if($estomat['ev_maloclusiones']==2){ $ev_maloclusiones = "Si"; }
    else if($estomat['ev_maloclusiones']==1){ $ev_maloclusiones = "No"; }
    else { $ev_maloclusiones = ''; }

    if($estomat['ev_incluidos']==2){ $ev_incluidos = "Si"; }
    else if($estomat['ev_incluidos']==1){ $ev_incluidos = "No"; }
    else { $ev_incluidos = ''; }

    if($estomat['ev_trauma']==2){ $ev_trauma = "Si"; }
    else if($estomat['ev_trauma']==1){ $ev_trauma = "No"; }
    else { $ev_trauma = ''; }

    if($estomat['ev_habitos']==2){ $ev_habitos = "Si"; }
    else if($estomat['ev_habitos']==1){ $ev_habitos = "No"; }
    else { $ev_habitos = ''; }

    if($estomat['ev_bolsas']==2){ $ev_bolsas = "Si"; }
    else if($estomat['ev_bolsas']==1){ $ev_bolsas = "No"; }
    else { $ev_bolsas = ''; }

    if($estomat['ev_placaBlanda']==2){ $ev_placaBlanda = "Si"; }
    else if($estomat['ev_placaBlanda']==1){ $ev_placaBlanda = "No"; }
    else { $ev_placaBlanda = ''; }

    if($estomat['ev_calculos']==2){ $ev_calculos = "Si"; }
    else if($estomat['ev_calculos']==1){ $ev_calculos = "No"; }
    else { $ev_calculos = ''; }

    if($estomat['ev_atm']==2){ $ev_atm = "Normal"; }
    else if($estomat['ev_atm']==1){ $ev_atm = "Anormal"; }
    else { $ev_atm = ''; }

    if($estomat['ev_labios']==2){ $ev_labios = "Normal"; }
    else if($estomat['ev_labios']==1){ $ev_labios = "Anormal"; }
    else { $ev_labios = ''; }

    if($estomat['ev_lengua']==2){ $ev_lengua = "Normal"; }
    else if($estomat['ev_lengua']==1){ $ev_lengua = "Anormal"; }
    else { $ev_lengua = ''; }

    if($estomat['ev_paladar']==2){ $ev_paladar = "Normal"; }
    else if($estomat['ev_paladar']==1){ $ev_paladar = "Anormal"; }
    else { $ev_paladar = ''; }

    if($estomat['ev_pisoBoca']==2){ $ev_pisoBoca = "Normal"; }
    else if($estomat['ev_pisoBoca']==1){ $ev_pisoBoca = "Anormal"; }
    else { $ev_pisoBoca = ''; }

    if($estomat['ev_carrillos']==2){ $ev_carrillos = "Normal"; }
    else if($estomat['ev_carrillos']==1){ $ev_carrillos = "Anormal"; }
    else { $ev_carrillos = ''; }

    if($estomat['ev_glandulasSalivares']==2){ $ev_glandulasSalivares = "Normal"; }
    else if($estomat['ev_glandulasSalivares']==1){ $ev_glandulasSalivares = "Anormal"; }
    else { $ev_glandulasSalivares = ''; }

    if($estomat['ev_maxilares']==2){ $ev_maxilares = "Normal"; }
    else if($estomat['ev_maxilares']==1){ $ev_maxilares = "Anormal"; }
    else { $ev_maxilares = ''; }

    if($estomat['ev_senosMaxilares']==2){ $ev_senosMaxilares = "Normal"; }
    else if($estomat['ev_senosMaxilares']==1){ $ev_senosMaxilares = "Anormal"; }
    else { $ev_senosMaxilares = ''; }

    if($estomat['ev_muscMasticadores']==2){ $ev_muscMasticadores = "Normal"; }
    else if($estomat['ev_muscMasticadores']==1){ $ev_muscMasticadores = "Anormal"; }
    else { $ev_muscMasticadores = ''; }

    if($estomat['ev_ganglios']==2){ $ev_ganglios = "Normal"; }
    else if($estomat['ev_ganglios']==1){ $ev_ganglios = "Anormal"; }
    else { $ev_ganglios = ''; }

    if($estomat['ev_oclusion']==2){ $ev_oclusion = "Normal"; }
    else if($estomat['ev_oclusion']==1){ $ev_oclusion = "Anormal"; }
    else { $ev_oclusion = ''; }

    if($estomat['ev_frenillos']==2){ $ev_frenillos = "Normal"; }
    else if($estomat['ev_frenillos']==1){ $ev_frenillos = "Anormal"; }
    else { $ev_frenillos = ''; }

    if($estomat['ev_mucosas']==2){ $ev_mucosas = "Normal"; }
    else if($estomat['ev_mucosas']==1){ $ev_mucosas = "Anormal"; }
    else { $ev_mucosas = ''; }

    if($estomat['ev_encias']==2){ $ev_encias = "Normal"; }
    else if($estomat['ev_encias']==1){ $ev_encias = "Anormal"; }
    else { $ev_encias = ''; }

    if($estomat['ev_amigdalas']==2){ $ev_amigdalas = "Normal"; }
    else if($estomat['ev_amigdalas']==1){ $ev_amigdalas = "Anormal"; }
    else { $ev_amigdalas = ''; }

    if($estomat['ev_amigdalas']==2){ $ev_amigdalas = "Normal"; }
    else if($estomat['ev_amigdalas']==1){ $ev_amigdalas = "Anormal"; }
    else { $ev_amigdalas = ''; }

// SE AGREGAN LOS DATOS
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('B4', $ev_higieneOral)
    ->setCellValue('B5', $ev_seda)
    ->setCellValue('B6', $ev_cepillo)
    ->setCellValue('B7', $ev_enjuagues)
    ->setCellValue('B8', $estomat['ev_cantVeces'])
    ->setCellValue('B10', $ev_superNumerarios)
    ->setCellValue('B11', $ev_abrasion)
    ->setCellValue('B12', $ev_manchas)
    ->setCellValue('B13', $ev_patologiaPulpar)
    ->setCellValue('B14', $ev_maloclusiones)
    ->setCellValue('B15', $ev_incluidos)
    ->setCellValue('B16', $ev_trauma)
    ->setCellValue('B17', $ev_habitos)
    ->setCellValue('B19', $ev_bolsas)
    ->setCellValue('B20', $ev_placaBlanda)
    ->setCellValue('B21', $ev_calculos)
    ->setCellValue('E5', $ev_atm)
    ->setCellValue('E6', $ev_labios)
    ->setCellValue('E7', $ev_lengua)
    ->setCellValue('E8', $ev_paladar)
    ->setCellValue('E9', $ev_pisoBoca)
    ->setCellValue('E10', $ev_carrillos)
    ->setCellValue('E11', $ev_glandulasSalivares)
    ->setCellValue('E12', $ev_maxilares)
    ->setCellValue('E13', $ev_senosMaxilares)
    ->setCellValue('E14', $ev_muscMasticadores)
    ->setCellValue('E15', $ev_ganglios)
    ->setCellValue('E16', $ev_oclusion)
    ->setCellValue('E17', $ev_frenillos)
    ->setCellValue('E18', $ev_mucosas)
    ->setCellValue('E19', $ev_encias)
    ->setCellValue('E20', $ev_amigdalas)
    ->setCellValue('B23', $estomat['ev_observaciones']);


$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('C')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension('D')->setAutoSize(TRUE);


//APLICAMOS LOS ESTILOS A CADA CELDA
$objPHPExcel->setActiveSheetIndex(2)->getStyle('A9')->applyFromArray($estiloEstomatologicos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('A18')->applyFromArray($estiloEstomatologicos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('D4')->applyFromArray($estiloEstomatologicos);


// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('ESTOMATOLOGICO');
?>