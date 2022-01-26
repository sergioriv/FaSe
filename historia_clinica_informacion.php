<?php
    
	$ti = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$informacion[pc_idIdentificacion]'")->fetch_assoc();
	$sexo = $con->query("SELECT * FROM sexos WHERE IDSexo = '$informacion[pc_idSexo]'")->fetch_assoc();
	$esCivil = $con->query("SELECT * FROM estadosciviles WHERE IDEstadoCivil = '$informacion[pc_idEstadoCivil]'")->fetch_assoc();
	$escolaridad = $con->query("SELECT * FROM escolaridad WHERE IDEscolaridad = '$informacion[pc_idEscolaridad]'")->fetch_assoc();
	$afiliacion = $con->query("SELECT * FROM afiliacion WHERE IDAfiliacion = '$informacion[pc_idAfiliacion]'")->fetch_assoc();
	$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$informacion[pc_idCiudad]'")->fetch_assoc();
	$zResidencial = $con->query("SELECT * FROM zonaresidencial WHERE IDZonaRes = '$informacion[pc_idZona]'")->fetch_assoc();
	$eps = $con->query("SELECT * FROM eps WHERE IDEps = '$informacion[pc_idEps]'")->fetch_assoc();
	$regimen = $con->query("SELECT * FROM regimenes WHERE IDRegimen = '$informacion[pc_idRegimen]'")->fetch_assoc();
	$etnia = $con->query("SELECT * FROM etnias WHERE IDEtnia = '$informacion[pc_idEtnia]'")->fetch_assoc();
	$ocupacion = $con->query("SELECT * FROM ocupaciones WHERE IDOcupacion = '$informacion[pc_idOcupacion]'")->fetch_assoc();
    $referencia = $con->query("SELECT * FROM referencias WHERE IDReferencia = '$informacion[pc_idReferencia]'")->fetch_assoc();
    
    $referidoArr = explode('-', $informacion['pc_idReferido']);
    if( $referidoArr[0] == 'P' ){
        $referido = $con->query("SELECT pc_nombres AS nombre FROM pacientes WHERE IDPaciente = '$referidoArr[1]'")->fetch_assoc();
    }elseif( $referidoArr[0] == 'D' ){
        $referido = $con->query("SELECT dc_nombres AS nombre FROM doctores WHERE IDDoctor = '$referidoArr[1]'")->fetch_assoc();
    }elseif( $referidoArr[0] == 'V' ){
        $referido = $con->query("SELECT vn_nombre AS nombre FROM vendedores WHERE IDVendedor = '$referidoArr[1]'")->fetch_assoc();
    }

    $etiqueta = str_replace('\n', ' - ', $informacion['pc_etiqueta']);


// COMBINAR CELDAS
$objPHPExcel->setActiveSheetIndex(0)
	->mergeCells('A1:D2')
    ->mergeCells('B5:D5')
    ->mergeCells('B12:D12')
    ->mergeCells('B15:D15')
    ->mergeCells('B17:D19');

// TITULOS PARA CELDAS
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', $tituloReporte) // Titulo principal
    ->setCellValue('A4', "Primer Apellido")  /* Subtitulos */
    ->setCellValue('C4', "Segundo Apellido")
    ->setCellValue('A5', "Nombres")
    ->setCellValue('A6', "Tipo de Identificación")
    ->setCellValue('C6', "Número de Identificación")
    ->setCellValue('A7', "Sexo")
    ->setCellValue('C7', "Fecha de Nacimiento")
    ->setCellValue('A8', "Estado Civil")
    ->setCellValue('C8', "Escolaridad")
    ->setCellValue('A9', "Afiliación")
    ->setCellValue('C9', "Ciudad")
    ->setCellValue('A10', "Zona Residencial")
    ->setCellValue('C10', "Dirección")
    ->setCellValue('A11', "Teléfono Fijo")
    ->setCellValue('C11', "Teléfono Celular")
    ->setCellValue('A12', "Correo Electrónico")
    ->setCellValue('A13', "EPS")
    ->setCellValue('C13', "Régimen")
    ->setCellValue('A14', "Etnia")
    ->setCellValue('C14', "Ocupación")
    ->setCellValue('A15', "Acompañante")
    ->setCellValue('A16', "Referencia")
    ->setCellValue('C16', "Referente")
    ->setCellValue('A17', "Etiqueta");

// SE AGREGAN LOS DATOS
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B4', $informacion['pc_apellido1'])
    ->setCellValue('D4', $informacion['pc_apellido2'])
    ->setCellValue('B5', $informacion['pc_nombre'])
    ->setCellValue('B6', $ti['ti_nombre'])
    ->setCellValue('D6', $informacion['pc_identificacion'])
    ->setCellValue('B7', $sexo['sx_codigo'])
    ->setCellValue('D7', $informacion['pc_fechaNacimiento'])
    ->setCellValue('B8', $esCivil['ec_nombre'])
    ->setCellValue('D8', $escolaridad['es_nombre'])
    ->setCellValue('B9', $afiliacion['af_nombre'])
    ->setCellValue('D9', $ciudad['cd_nombre'])
    ->setCellValue('B10', $zResidencial['zr_nombre'])
    ->setCellValue('D10', $informacion['pc_direccion'])
    ->setCellValue('B11', $informacion['pc_telefonoFijo'])
    ->setCellValue('D11', $informacion['pc_telefonoCelular'])
    ->setCellValue('B12', $informacion['pc_correo'])
    ->setCellValue('B13', $eps['eps_nombre'])
    ->setCellValue('D13', $regimen['rg_nombre'])
    ->setCellValue('B14', $etnia['et_nombre'])
    ->setCellValue('D14', $ocupacion['ocu_nombre'])
    ->setCellValue('B15', $informacion['pc_responsable'])
    ->setCellValue('B16', $referencia['ref_nombre'])
    ->setCellValue('D16', $referido['nombre'])
    ->setCellValue('B17', $etiqueta);

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(TRUE);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(TRUE);



$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4:A17')->applyFromArray($estiloInformacion);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C4:C16')->applyFromArray($estiloInformacion);


// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('INFORMACION');

?>