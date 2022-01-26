<?php //include'config.php';

$pacienteRow = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$pacienteID'")->fetch_assoc();

$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$clinicaRow[cl_idCiudad]'")->fetch_assoc();

    $ti = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$pacienteRow[pc_idIdentificacion]'")->fetch_assoc();
	$sexo = $con->query("SELECT * FROM sexos WHERE IDSexo = '$pacienteRow[pc_idSexo]'")->fetch_assoc();
	$esCivil = $con->query("SELECT * FROM estadosciviles WHERE IDEstadoCivil = '$pacienteRow[pc_idEstadoCivil]'")->fetch_assoc();
	$escolaridad = $con->query("SELECT * FROM escolaridad WHERE IDEscolaridad = '$pacienteRow[pc_idEscolaridad]'")->fetch_assoc();
	$afiliacion = $con->query("SELECT * FROM afiliacion WHERE IDAfiliacion = '$pacienteRow[pc_idAfiliacion]'")->fetch_assoc();
	$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$pacienteRow[pc_idCiudad]'")->fetch_assoc();
	$zResidencial = $con->query("SELECT * FROM zonaresidencial WHERE IDZonaRes = '$pacienteRow[pc_idZona]'")->fetch_assoc();
	$eps = $con->query("SELECT * FROM eps WHERE IDEps = '$pacienteRow[pc_idEps]'")->fetch_assoc();
	$regimen = $con->query("SELECT * FROM regimenes WHERE IDRegimen = '$pacienteRow[pc_idRegimen]'")->fetch_assoc();
	$etnia = $con->query("SELECT * FROM etnias WHERE IDEtnia = '$pacienteRow[pc_idEtnia]'")->fetch_assoc();
	$ocupacion = $con->query("SELECT * FROM ocupaciones WHERE IDOcupacion = '$pacienteRow[pc_idOcupacion]'")->fetch_assoc();
    $referencia = $con->query("SELECT * FROM referencias WHERE IDReferencia = '$pacienteRow[pc_idReferencia]'")->fetch_assoc();
    
    $referidoArr = explode('-', $pacienteRow['pc_idReferido']);
    if( $referidoArr[0] == 'P' ){
        $referido = $con->query("SELECT pc_nombres AS nombre FROM pacientes WHERE IDPaciente = '$referidoArr[1]'")->fetch_assoc();
    }elseif( $referidoArr[0] == 'D' ){
        $referido = $con->query("SELECT dc_nombres AS nombre FROM doctores WHERE IDDoctor = '$referidoArr[1]'")->fetch_assoc();
    }elseif( $referidoArr[0] == 'V' ){
        $referido = $con->query("SELECT vn_nombre AS nombre FROM vendedores WHERE IDVendedor = '$referidoArr[1]'")->fetch_assoc();
    }

    $etiqueta = str_replace('\n', ' - ', $pacienteRow['pc_etiqueta']);

if( file_exists($clinicaRow['cl_logo']) ) {
    $imageLogo = '<img max-width="45mm" max-height="25mm" src="'.dirname(__FILE__)."/".$clinicaRow['cl_logo'] .'">';
}
?>
<style type="text/css">
	table.page_header { border: none; background-color: white; border-bottom: solid 1mm <?= $colorPrincipal ?>; padding: 2mm 5mm; }
    table.page_footer {width: 100%; border: none; background-color: white; border-top: solid 1mm <?= $colorPrincipal ?>; padding: 2mm; }

    .empresa{ font-size: 14px; text-transform: uppercase; font-weight: bold; }
    .logo { vertical-align: middle; text-align: right; width: 70mm; }
    .logo img { max-width: 45mm; max-height: 20mm; }
    .info { font-size: 10px; }
    .fecha{ font-size: 9px; width: 70mm; }
    .tituloDoc{ font-size: 12px; font-weight: bold; text-align: center; width: 64mm; }

.titulo {
  color: rgb(30,30,30);
}
.tituloSecundario {
  font-size: 13px;
  font-weight: bold;
  display: inline-flex;
}
.top { margin: 1mm 0mm; }
.tituloSeccion{
    color:black;
    font-size: 11px;
    font-weight: bold;
    padding-bottom: 1mm;
    margin-bottom: 1mm;
}
.linea {
    border-bottom: 1px solid rgb(240,240,240);
}
.title_center { text-align: center; }
.paddingTop{
    padding-top: 1mm;
}
.tableList {
  width: 196mm;
  border-collapse: collapse;
  color: rgb(50,50,50);
  margin-bottom: 20px;
}
.tableList thead {
  text-align: center;
  font-size: 11px;
}
.tableList thead th {padding: 10px 5px;}
.tableList tbody {font-size: 10px;}
.tableList thead tr { background: rgb(230,230,230); }
.tableList tbody tr:nth-child(even){background: rgb(245,245,245); }
.tableList tbody tr:nth-child(odd){background: rgb(254,254,254); }
.tableList tbody td { padding: 0.5mm 1mm; }
.tableList tbody td.fijo{ width: 30mm; }
.alg-right { text-align: right; }
.separacion{
    background: rgb(240,240,240);
    width: 100%;
    height: 1px;
}
.newTable tbody th.th { font-size: 10px; width: 25mm; }
.newTable tbody td.td { font-size: 9px; width: 177mm; }
.newTable { text-align: left; }
.table_dual tbody th.th { padding: 0.5mm 0mm; font-size: 10px; width: 25mm; vertical-align: sub; }
.table_dual tbody td.td { padding: 0.5mm 0mm; font-size: 9px; width: 75mm; vertical-align: sub; }
.table_dual tbody td.full { padding: 0.5mm 0mm; font-size: 9px; width: 177mm; vertical-align: sub; }
.table_dual { text-align: left; }
</style>
<page backtop="28mm" backbottom="14mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <table class="page_header" cellspacing="0">
            <tr>                
                <td colspan="2" class="empresa"><?= $clinicaRow['cl_nombre'] ?></td>
                <td rowspan="3" class="logo"><?= $imageLogo ?></td>
            </tr>
            <tr>
                <td colspan="2" class="info">
                    NIT: <?= $clinicaRow['cl_nit'] ?>
                    <br>
                    <?= $ciudad['cd_nombre'].' '.$clinicaRow['cl_direccion'].'' ?>
                    <br>
                    <?= $clinicaRow['cl_correo'] ?>
                </td>
            </tr>
            <tr>
                <td class="fecha"><?= $hoyAno.'/'.$hoyMes.'/'.$hoyDia ?></td>
                <td class="tituloDoc">Historia Clínica</td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer" cellspacing="0">
            <tr>
                <td style="width: 100%; text-align: right">
                    page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>

    <div class="tituloSeccion linea">Datos del Paciente</div>
    <table class="tableList table_dual">
        <tbody>
            <tr>
                <th class="th">Primer apellido</th>
                <td class="td"><?= $pacienteRow['pc_apellido1'] ?></td>
                <th class="th">Segundo apellido</th>
                <td class="td"><?= $pacienteRow['pc_apellido2'] ?></td>
            </tr>
            <tr>
                <th class="th">Nombres</th>
                <td class="full" colspan="3"><?= $pacienteRow['pc_nombres'] ?></td>
            </tr>
            <tr>
                <th class="th">Tipo de Identificación</th>
                <td class="td"><?= $ti['ti_nombre'] ?></td>
                <th class="th">Número de Identificación</th>
                <td class="td"><?= $pacienteRow['pc_identificacion'] ?></td>
            </tr>
            <tr>
                <th class="th">Sexo</th>
                <td class="td"><?= $sexo['sx_codigo'] ?></td>
                <th class="th">Fecha de Nacimiento</th>
                <td class="td"><?= $pacienteRow['pc_fechaNacimiento'] ?></td>
            </tr>
            <tr>
                <th class="th">Estado Civil</th>
                <td class="td"><?= $esCivil['ec_nombre'] ?></td>
                <th class="th">Escolaridad</th>
                <td class="td"><?= $escolaridad['es_nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">Afiliación</th>
                <td class="td"><?= $afiliacion['af_nombre'] ?></td>
                <th class="th">Ciudad</th>
                <td class="td"><?= $ciudad['cd_nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">Zona Residencial</th>
                <td class="td"><?= $zResidencial['zr_nombre'] ?></td>
                <th class="th">Dirección</th>
                <td class="td"><?= $pacienteRow['pc_direccion'] ?></td>
            </tr>
            <tr>
                <th class="th">Teléfono Fijo</th>
                <td class="td"><?= $pacienteRow['pc_telefonoFijo'] ?></td>
                <th class="th">Teléfono Celular</th>
                <td class="td"><?= $pacienteRow['pc_telefonoCelular'] ?></td>
            </tr>
            <tr>
                <th class="th">Correo Electrónico</th>
                <td class="full" colspan="3"><?= $pacienteRow['pc_correo'] ?></td>
            </tr>
            <tr>
                <th class="th">EPS</th>
                <td class="td"><?= $eps['eps_nombre'] ?></td>
                <th class="th">Régimen</th>
                <td class="td"><?= $regimen['rg_nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">Etnia</th>
                <td class="td"><?= $etnia['et_nombre'] ?></td>
                <th class="th">Ocupación</th>
                <td class="td"><?= $ocupacion['ocu_nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">Acompañante</th>
                <td class="full" colspan="3"><?= $pacienteRow['pc_responsable'] ?></td>
            </tr>
            <tr>
                <th class="th">Referencia</th>
                <td class="td"><?= $referencia['ref_nombre'] ?></td>
                <th class="th">Referente</th>
                <td class="td"><?= $referido['nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">Etiqueta</th>
                <td class="td" colspan="3"><?= $etiqueta ?></td>
            </tr>
        </tbody>
    </table>

    <div class="tituloSeccion linea">Antecedentes Familiares</div>
        <table class="tableList">
            <thead>
                <tr>
                    <th>CIE-10</th>
                    <th>Comentario</th>
                    <th>Fecha asignación</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" style="width: 202mm;"></td>
                </tr>
            <?php $anteFamiSql = $con->query("SELECT * FROM pacientesrips, rips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.prip_idPaciente = '$pacienteID' AND pacientesrips.prip_estado='1' AND pacientesrips.prip_area='1' ORDER BY pacientesrips.IDPacRips DESC");
				while($anteFamiRow = $anteFamiSql->fetch_assoc()){
			?>
                <tr>
                    <td><?= $anteFamiRow['rip_codigo']?></td>
                    <td><?= $anteFamiRow['prip_comentario']?></td>
                    <td class="fijo" align="center"><?= $anteFamiRow['prip_fechaCreacion']?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="tituloSeccion linea">Antecedentes Patológicos</div>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>CIE-10</th>
                        <th>Comentario</th>
                        <th>Fecha asignación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="width: 202mm;"></td>
                    </tr>
                <?php $antePatoSql = $con->query("SELECT * FROM pacientesrips, rips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.prip_idPaciente = '$pacienteID' AND pacientesrips.prip_estado='1' AND pacientesrips.prip_area='2' ORDER BY pacientesrips.IDPacRips DESC");
                    while($antePatoRow = $antePatoSql->fetch_assoc()){
                ?>
                    <tr>
                        <td><?= $antePatoRow['rip_codigo']?></td>
                        <td><?= $antePatoRow['prip_comentario']?></td>
                        <td class="fijo" align="center"><?= $antePatoRow['prip_fechaCreacion']?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        <div class="tituloSeccion linea">Antecedentes No Patológicos</div>
            <table class="tableList">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Comentario</th>
                        <th>Fecha asignación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="width: 202mm;"></td>
                    </tr>
                <?php $anteNoPato = $con->query("SELECT * FROM pacientenopatologicos, nopatologicos WHERE pacientenopatologicos.pnp_idNoPatologico = nopatologicos.IDNoPatologico AND pacientenopatologicos.pnp_idPaciente = '$pacienteID' AND pacientenopatologicos.pnp_estado='1' ORDER BY pacientenopatologicos.IDpacNoPatologico DESC");
                    while($pacienteNoPatRow = $anteNoPato->fetch_assoc()){
                ?>
                    <tr>
                        <td><?= $anteNoPatoRow['np_nombre']?></td>
                        <td><?= $anteNoPatoRow['pnp_comentario']?></td>
                        <td class="fijo" align="center"><?= $anteNoPatoRow['pnp_fechaCreacion']?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php $estomat = $con->query("SELECT * FROM evolucionpaciente WHERE ev_idPaciente = '$pacienteID'")->fetch_assoc();
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
        ?>
        <div class="tituloSeccion linea title_center">Estomatológicos</div>
        <table class="tableList table_dual">
            <tbody>
                <tr>
                    <th class="th">Higiene Oral</th>
                    <td class="td"><?= $ev_higieneOral ?></td>
                    <th class="th">Seda Dental</th>
                    <td class="td"><?= $ev_seda ?></td>
                </tr>
                <tr>
                    <th class="th">Cepillo Dental</th>
                    <td class="td"><?= $ev_cepillo ?></td>
                    <th class="th">Enjuagues Bucales</th>
                    <td class="td"><?= $ev_enjuagues ?></td>
                </tr>
                <tr>
                    <th class="th">Cuántas veces al día</th>
                    <td class="td"><?= $estomat['ev_cantVeces'] ?></td>
                    <th class="th"></th>
                    <td class="td"></td>
                </tr>
            </tbody>
        </table>
        <div class="tituloSeccion linea">Exámen Dental</div>
        <table class="tableList table_dual">
            <tbody>
                <tr>
                    <th class="th">Supernumerarios</th>
                    <td class="td"><?= $ev_superNumerarios ?></td>
                    <th class="th">Abrasion</th>
                    <td class="td"><?= $ev_abrasion ?></td>
                </tr>
                <tr>
                    <th class="th">Manchas - Canbio de Color</th>
                    <td class="td"><?= $ev_manchas ?></td>
                    <th class="th">Patología Pulpar - Abcesos</th>
                    <td class="td"><?= $ev_patologiaPulpar ?></td>
                </tr>
                <tr>
                    <th class="th">Maloclusiones</th>
                    <td class="td"><?= $ev_maloclusiones ?></td>
                    <th class="th">Incluidos</th>
                    <td class="td"><?= $ev_incluidos ?></td>
                </tr>
                <tr>
                    <th class="th">Trauma</th>
                    <td class="td"><?= $ev_trauma ?></td>
                    <th class="th">Habitos</th>
                    <td class="td"><?= $ev_habitos ?></td>
                </tr>
            </tbody>
        </table>        
        <div class="tituloSeccion linea">Exámen Periodontal</div>
        <table class="tableList table_dual">
            <tbody>
                <tr>
                    <th class="th">Bolsas - Movilidad</th>
                    <td class="td"><?= $ev_bolsas ?></td>
                    <th class="th">Placa Blanda</th>
                    <td class="td"><?= $ev_placaBlanda ?></td>
                </tr>
                <tr>
                    <th class="th">Calculos</th>
                    <td class="td"><?= $ev_calculos ?></td>
                    <th class="th"></th>
                    <td class="td"></td>
                </tr>
            </tbody>
        </table>        
        <div class="tituloSeccion linea">Tejidos Blandos</div>
        <table class="tableList table_dual">
            <tbody>
                <tr>
                    <th class="th">A.T.M</th>
                    <td class="td"><?= $ev_atm ?></td>
                    <th class="th">Labios</th>
                    <td class="td"><?= $ev_labios ?></td>
                </tr>
                <tr>
                    <th class="th">Lengua</th>
                    <td class="td"><?= $ev_lengua ?></td>
                    <th class="th">Paladar</th>
                    <td class="td"><?= $ev_paladar ?></td>
                </tr>
                <tr>
                    <th class="th">Piso de Boca</th>
                    <td class="td"><?= $ev_pisoBoca ?></td>
                    <th class="th">Carrillos</th>
                    <td class="td"><?= $ev_carrillos ?></td>
                </tr>
                <tr>
                    <th class="th">Glandulas Salivares</th>
                    <td class="td"><?= $ev_glandulasSalivares ?></td>
                    <th class="th">Maxilares</th>
                    <td class="td"><?= $ev_maxilares ?></td>
                </tr>
                <tr>
                    <th class="th">Senos Maxilares</th>
                    <td class="td"><?= $ev_senosMaxilares ?></td>
                    <th class="th">Musculos Masticadores</th>
                    <td class="td"><?= $ev_muscMasticadores ?></td>
                </tr>
                <tr>
                    <th class="th">Ganglios</th>
                    <td class="td"><?= $ev_ganglios ?></td>
                    <th class="th">Oclusión</th>
                    <td class="td"><?= $ev_oclusion ?></td>
                </tr>
                <tr>
                    <th class="th">Frenillos</th>
                    <td class="td"><?= $ev_frenillos ?></td>
                    <th class="th">Mucosas</th>
                    <td class="td"><?= $ev_mucosas ?></td>
                </tr>
                <tr>
                    <th class="th">Encías</th>
                    <td class="td"><?= $ev_encias ?></td>
                    <th class="th">Amígdalas</th>
                    <td class="td"><?= $ev_amigdalas ?></td>
                </tr>
                <tr>
                    <th class="th">Observaciones estomatológico</th>
                    <td class="full" colspan="3"><?= $estomat['ev_observaciones'] ?></td>
                </tr>
            </tbody>
        </table>


        <div class="tituloSeccion title_center">Historíco citas</div>
        <?php $historialCitas = $con->query("SELECT * FROM citas, sucursales, doctores, tratamientos WHERE citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$pacienteID' ORDER BY citas.ct_fechaOrden DESC");
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
        ?>
            <div class="linea"></div>
            <table class="tableList table_dual">
                <tbody>
                    <tr>
                        <th class="th">Fecha</th>
                        <td class="td"><?= $historialCitasRow['ct_anoCita'].'/'.$historialCitasRow['ct_mesCita'].'/'.$historialCitasRow['ct_diaCita'].' '.$historialCitasRow['ct_horaCita'] ?></td>
                        <th class="th">Duración</th>
                        <td class="td"><?= $historialCitasRow['ct_duracion'] ?></td>
                    </tr>
                    <tr>
                        <th class="th">Sucursal</th>
                        <td class="td"><?= $historialCitasRow['sc_nombre'] ?></td>
                        <th class="th">Doctor</th>
                        <td class="td"><?= $historialCitasRow['dc_nombres'] ?></td>
                    </tr>
                    <tr>
                        <th class="th">Tratamiento</th>
                        <td class="td"><?= $historialCitasRow['tr_nombre'] ?></td>
                        <th class="th">Tipo de cita</th>
                        <td class="td"><?= $tipoCita ?></td>
                    </tr>
                    <tr>
                        <th class="th">Estado de cita</th>
                        <td class="td"><?= $estadoCita ?></td>
                        <th class="th">Asistencia</th>
                        <td class="td"><?= $citaAsistencia ?></td>
                    </tr>
                    <tr>
                        <th>Finalidad</th>
                        <td class="full" colspan="3"><?= $HSfinalidad['fc_nombre'] ?></td>
                    </tr>
                    <tr>
                        <th>Causa Externa</th>
                        <td class="full" colspan="3"><?= $HScausaExterna['ce_nombre'] ?></td>
                    </tr>
                    <tr>
                        <th>CIE 10 DX Ppal.</th>
                        <td class="full" colspan="3"><?= $HScie10['rip_nombre'] ?></td>
                    </tr>
                    <tr>
                        <th>CIE 10 DX Rel. 1</th>
                        <td class="full" colspan="3"><?= $HScie10_1['rip_nombre'] ?></td>
                    </tr>
                    <tr>
                        <th>CIE 10 DX Rel. 2</th>
                        <td class="full" colspan="3"><?= $HScie10_2['rip_nombre'] ?></td>
                    </tr>
                    <tr>
                        <th>CIE 10 DX Rel. 3</th>
                        <td class="full" colspan="3"><?= $HScie10_3['rip_nombre'] ?></td>
                    </tr>
                    <tr>
                        <th>Descipción cita</th>
                        <td class="full" colspan="3"><?= $historialCitasRow['ct_descripcion'] ?></td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>

            

</page>