<?php //include'config.php';

$odontogramaSql = $con->query("SELECT pc_nombres, pc_telefonoFijo, pc_telefonoCelular, pc_idIdentificacion, pc_identificacion, pc_idCiudad, pc_direccion, pc_idEps, pod_consecutivo, pod_fecha, pod_nota, pod_odontoImage
    FROM pacienteodontograma AS pod
        INNER JOIN pacientes AS pc ON pod.pod_idPaciente = pc.IDPaciente
        WHERE IDOdontograma='$odontogramaID'")->fetch_assoc();

$title = $odontogramaSql['pc_nombres'];

$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$clinicaRow[cl_idCiudad]'")->fetch_assoc();

$tipoDocumento = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion='$odontogramaSql[pc_idIdentificacion]'")->fetch_assoc();
$ciudadPaciente = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$odontogramaSql[pc_idCiudad]'")->fetch_assoc();

$telefonoPaciente = '';
if($odontogramaSql['pc_telefonoCelular']!=""){
    $telefonoPaciente = $odontogramaSql['pc_telefonoCelular'];
} else {
    $telefonoPaciente = $odontogramaSql['pc_telefonoFijo'];
}

$deuda = 0; $sumatoria = 0;
$deudaSql = $con->query("SELECT SUM(ct_costo) AS vt FROM citas WHERE ct_idPaciente = '$pacienteID' AND ct_inicial='1' AND ct_estado IN(0,1)")->fetch_assoc();
$abonosSql = $con->query("SELECT SUM(ab_abono) AS ab FROM abonos WHERE ab_idPaciente = '$pacienteID'  AND ab_idSucursal>0 AND ab_estado='1'")->fetch_assoc();
$deuda = $deudaSql['vt'] - $abonosSql['ab'];
$sumatoria = $abonosSql['ab'];


if( file_exists($clinicaRow['cl_logo']) ) {
    $imageLogo = '<img src="'.dirname(__FILE__)."/".$clinicaRow['cl_logo'] .'" max-width="45mm" max-height="25mm">';
}

$imageOdonto = '<img src="'.dirname(__FILE__)."/".$odontogramaSql['pod_odontoImage'] .'" max-width="25mm" max-height="25mm">';
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
  margin-bottom: 20px;
}
.newTable tbody th.th { font-size: 10px; width: 20mm; }
.newTable tbody td.td { font-size: 9px; width: 182mm; }
.newTable { text-align: left; }
.table_dual tbody th.th { padding: 0.5mm 0mm; font-size: 10px; width: 40mm; vertical-align: sub; }
.table_dual tbody td.td { padding: 0.5mm 0mm; font-size: 9px; width: 60mm; vertical-align: sub; }
.table_dual tbody td.full { padding: 0.5mm 0mm; font-size: 9px; width: 177mm; vertical-align: sub; }
.table_dual th { text-align: right; }

.odonto { vertical-align: middle; text-align: right; width: 206mm; }
.imageConvencion img {
    width: 10mm;
}
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
                <td class="tituloDoc">Odontograma</td>
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
    <table class="tableList newTable">
        <tbody>
            <tr>
                <th class="th">Nombres:</th>
                <td class="td"><?= $odontogramaSql['pc_nombres'] ?></td>
            </tr>
            <tr>
                <th class="th">Documento:</th>
                <td class="td"><?= $tipoDocumento['ti_nombre'].' '.$odontogramaSql['pc_identificacion'] ?></td>
            </tr>
            <tr>
                <th class="th">Direccion:</th>
                <td class="td"><?= $ciudadPaciente['cd_nombre'].' '.$odontogramaSql['pc_direccion'] ?></td>
            </tr>
            <tr>
                <th class="th">Teléfono:</th>
                <td class="td"><?= $telefonoPaciente ?></td>
            </tr>
        </tbody>
    </table>

    <div class="tituloSeccion linea">Odontograma # <?= $odontogramaSql['pod_consecutivo'] ?></div>

    <div><img class="odonto" src="<?= dirname(__FILE__)."/".$odontogramaSql['pod_odontoImage'] ?>" /></div>

    <div class="separacion"></div>
    <div class="tituloSeccion linea">Indicadores del Odontograma</div>

    <table class="tableList table_dual">
        <tbody>
            <tr>
                <td colspan="6" style="width: 202mm;"></td>
            </tr>
            <tr>
                <th>Caries</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/caries/IconoCaries.png' ?>" /></td>
                <th>Obturado</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/obturado/IconoObturado.png' ?>" /></td>
                <th>Ausente</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/ausente/Ausente.png' ?>" /></td>
            </tr>
            <tr>
                <th>Corona en buen estado</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/coronasbuenestado/IconoCoronaBuenEstado.png' ?>" /></td>
                <th>Corona en mal estado</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/coronasmalestado/IconoCoronaMalEstado.png' ?>" /></td>
                <th>Edentulo</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/edentulo/IconoEdentulo.png' ?>" /></td>
            </tr>
            <tr>
                <th>Endodoncia en buen estado</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/endodonciaenbuenestado/IconoEndodonciaBuenEstado.png' ?>" /></td>
                <th>Necesita Endodoncia</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/endodoncianecesita/endodoncianecesita.png' ?>" /></td>
                <th>Exodoncia</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/exodoncia/IconoExodoncia.png' ?>" /></td>
            </tr>
            <tr>
                <th>Implante</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/implante/IconoImplante.png' ?>" /></td>
                <th>Necesita Sellante</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/necesitasellante/IconoNecesitaSellante.png' ?>" /></td>
                <th>Obturado con caries</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/obturadoconcaries/IconoObturadoconCaries.png' ?>" /></td>
            </tr>
            <tr>
                <th>Obturado en resina</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/obturadoenresina/IconoObturadoenResina.png' ?>" /></td>
                <th>Prótesis fija total</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/prótesisfijatotal/IconoProtesisFijaTotal.png' ?>" /></td>
                <th>Prótesis parcial</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/prótesisparcial/ProtesisParcial.png' ?>" /></td>
            </tr>
            <tr>
                <th>Resina preventiva</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/resinapreventiva/IconoResinaPreventiva.png' ?>" /></td>
                <th>Diente Sano</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/sano/IconoDienteSano.png' ?>" /></td>
                <th>Sellante</th>
                <td class="imageConvencion"><img src="<?= dirname(__FILE__).'/img/IconosOdontograma/sellante/IconoSellante.png' ?>" /></td>
            </tr>
        </tbody>
    </table>
    
</page>