<?php //include'config.php';

$citaSql = $con->query("SELECT pc_nombres, pc_telefonoFijo, pc_telefonoCelular, pc_idIdentificacion, pc_identificacion, pc_idCiudad, pc_direccion, pc_idEps, ct_anoCita, ct_mesCita, ct_diaCita, ct_evoFirmaUsuario
    FROM citas AS ct
        INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
        WHERE ct.IDCita = $citaID")->fetch_assoc();

$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$clinicaRow[cl_idCiudad]'")->fetch_assoc();

$tipoDocumento = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion='$citaSql[pc_idIdentificacion]'")->fetch_assoc();
$ciudadPaciente = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$citaSql[pc_idCiudad]'")->fetch_assoc();
$EPSPaciente = $con->query("SELECT * FROM eps WHERE IDEps = '$citaSql[pc_idEps]'")->fetch_assoc();

$telefonoPaciente = '';
if($citaSql['pc_telefonoCelular']!=""){
    $telefonoPaciente = $citaSql['pc_telefonoCelular'];
} else {
    $telefonoPaciente = $citaSql['pc_telefonoFijo'];
}

$citaFechaTitle = $citaSql['ct_anoCita'].'-'.$citaSql['ct_mesCita'].'-'.$citaSql['ct_diaCita'];

if( file_exists($clinicaRow['cl_logo']) ) {
    $imageLogo = '<img max-width="45mm" max-height="25mm" src="'.dirname(__FILE__)."/".$clinicaRow['cl_logo'] .'">';
}
?>
<style type="text/css">
    table.page_header { border: none; background: white; border-bottom: solid 1mm <?= $colorPrincipal ?>; padding: 2mm 5mm; }
    table.page_footer { width: 100%; border: none; background: white; border-top: solid 1mm <?= $colorPrincipal ?>; padding: 2mm; }

    .empresa{ font-size: 14px; text-transform: uppercase; font-weight: bold; }
    .logo { vertical-align: middle; text-align: right; width: 70mm; }
    .logo img { max-width: 45mm; max-height: 20mm; }
    .info { font-size: 10px; }
    .fecha{ font-size: 9px; width: 63mm; }
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
.tableList tbody td { padding: 0.5mm 1mm; vertical-align: super; }
.tableList tbody td.fijo{ width: 15mm; }
.alg-right { text-align: right; }
.separacion{
    background: rgb(240,240,240);
    width: 100%;
    height: 1px;
}
.newTable tbody th.th { font-size: 10px; width: 20mm; }
.newTable tbody td.td { font-size: 9px; width: 182mm; }
.newTable { text-align: left; }


.twoSeccion tbody th.th { font-size: 10px; width: 20mm; }
.twoSeccion tbody td.td { font-size: 9px; width: 76mm; }
.twoSeccion { text-align: left; vertical-align: center; align-items: middle; }

.padd{ padding-top: 1mm; }

.content_signature{
  position: relative;
  width: max-content;
  width: 150px;
  height: 75px;
  left: 23mm;
}
.content_signature .option_signature_pad{
  position: absolute;
  bottom: 0;
  width: 100%;
  padding: 2px 4px;
  box-sizing: border-box;
  font-size: 10px;
  border-top: 1px solid black;
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
                <td class="tituloDoc">Formulación</td>
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
                <td class="td"><?= $citaSql['pc_nombres'] ?></td>
            </tr>
            <tr>
                <th class="th">Documento:</th>
                <td class="td"><?= $tipoDocumento['ti_nombre'].' '.$citaSql['pc_identificacion'] ?></td>
            </tr>
            <tr>
                <th class="th">Direccion:</th>
                <td class="td"><?= $ciudadPaciente['cd_nombre'].' '.$citaSql['pc_direccion'] ?></td>
            </tr>
            <tr>
                <th class="th">Teléfono:</th>
                <td class="td"><?= $telefonoPaciente ?></td>
            </tr>
            <tr>
                <th class="th">EPS:</th>
                <td class="td"><?= $EPSPaciente['eps_nombre'] ?></td>
            </tr>
        </tbody>
    </table>

    <div class="tituloSeccion linea">Receta medicamentos</div>
    <table class="tableList newTable">
        <tbody>
            <?php $medicamentosCita = $con->query("SELECT * FROM citamedicamentos AS cm INNER JOIN vadecum AS vd ON cm.cm_idVadecum = vd.IDVadecum WHERE cm.cm_idCita = '$citaID' ORDER BY cm.IDCitaMedicamento ASC ");
            while($medicamentosCitaRow = $medicamentosCita->fetch_assoc()){
            ?>
                <tr>
                    <th class="th">Medicamento:</th>
                    <td class="td"><?= $medicamentosCitaRow['vd_medicamento'] ?></td>
                </tr>
                <tr>
                    <th class="th">Cantidad:</th>
                    <td class="td"><?= '<b>'. $medicamentosCitaRow['cm_cantidad'] .'</b> '. $medicamentosCitaRow['vd_presentacion'] ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <table class="tableList">
            <tr>
                <td colspan="2" style="width: 199mm;"></td>
            </tr>
            <tr>
                <td style="width: 250px"></td>
                <td>
                    <div class="content_signature right">
                        <?php if(!empty($citaSql['ct_evoFirmaUsuario'])){ ?>
                            <img width="150" src="<?php echo $citaSql['ct_evoFirmaUsuario'] ?>">
                        <?php } ?>

                        <div class="option_signature_pad">
                            Doctor
                        </div>
                    </div>
                </td>
            </tr>
        </table>
</page>