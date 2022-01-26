<?php //include'config.php';

$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$clinicaRow[cl_idCiudad]'")->fetch_assoc();

$citaSql = $con->query("SELECT pc_nombres, pc_telefonoFijo, pc_telefonoCelular, pc_idIdentificacion, pc_identificacion, pc_idCiudad, pc_direccion, dc_nombres, sc_nombre, sc_idCiudad, sc_direccion, uo_nombre, tr_nombre, tr_idCups, ct.*
    FROM citas AS ct
        INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
        INNER JOIN doctores AS dc ON ct.ct_idDoctor = dc.IDDoctor
        INNER JOIN sucursales AS sc ON ct.ct_idSucursal = sc.IDSucursal
        INNER JOIN unidadesodontologicas AS uo ON ct.ct_idUnidad = uo.IDUnidadOdontologica
        INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
        WHERE ct.IDCita = $citaID")->fetch_assoc();

$telefonoPaciente = '';
if($citaSql['pc_telefonoCelular']!=""){
    $telefonoPaciente = $citaSql['pc_telefonoCelular'];
} else {
    $telefonoPaciente = $citaSql['pc_telefonoFijo'];
}

$tipoDocumento = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion='$citaSql[pc_idIdentificacion]'")->fetch_assoc();
$ciudadPaciente = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$citaSql[pc_idCiudad]'")->fetch_assoc();

$ciudadSucursal = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$citaSql[sc_idCiudad]'")->fetch_assoc();

if($citaSql['tr_idCups']!=0){
    $cups = $con->query("SELECT cup_codigo FROM cups WHERE IDCups = '$citaSql[tr_idCups]'")->fetch_assoc();
    $cup = $cups['cup_codigo'].' | ';
}

$citaFechaTitle = $citaSql['ct_anoCita'].'-'.$citaSql['ct_mesCita'].'-'.$citaSql['ct_diaCita'];
$citaFecha = $citaSql['ct_anoCita'].'/'.$citaSql['ct_mesCita'].'/'.$citaSql['ct_diaCita'];

if( file_exists($clinicaRow['cl_logo']) ) {
    $imageLogo = '<img max-width="45mm" max-height="25mm" src="'.dirname(__FILE__)."/".$clinicaRow['cl_logo'] .'">';
}


$externa = $con->query("SELECT * FROM causaexterna WHERE IDCausaExterna = '$citaSql[ct_idCausaExterna]'")->fetch_assoc();
$finalidad = $con->query("SELECT * FROM finalidadconsulta WHERE IDFinalidadConsulta = '$citaSql[ct_idFinalidad]'")->fetch_assoc();

$dx_ppal = $con->query("SELECT * FROM rips WHERE IDRips = '$citaSql[ct_idRip]'")->fetch_assoc();
$dx_rel1 = $con->query("SELECT * FROM rips WHERE IDRips = '$citaSql[ct_idRip1]'")->fetch_assoc();
$dx_rel2 = $con->query("SELECT * FROM rips WHERE IDRips = '$citaSql[ct_idRip2]'")->fetch_assoc();
$dx_rel3 = $con->query("SELECT * FROM rips WHERE IDRips = '$citaSql[ct_idRip3]'")->fetch_assoc();
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
                <td class="tituloDoc">Evolución Cita <?= $citaFecha ?></td>
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
    

    <table class="tableList twoSeccion">
        <tbody>
            <tr>
                <th class="tituloSeccion linea" colspan="2">Datos del Paciente</th>
                <th class="tituloSeccion linea" colspan="2">Datos de la Cita</th>
            </tr>
            <tr>
                <th class="th padd">Nombres:</th>
                <td class="td padd"><?= $citaSql['pc_nombres'] ?></td>

                <th class="th padd">Sucursal:</th>
                <td class="td padd"><?= $citaSql['sc_nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">Documento:</th>
                <td class="td"><?= $tipoDocumento['ti_nombre'].' '.$citaSql['pc_identificacion'] ?></td>

                <th class="th">Doctor:</th>
                <td class="td"><?= $citaSql['dc_nombres'] ?></td>
            </tr>
            <tr>
                <th class="th">Ciudad:</th>
                <td class="td"><?= $ciudadPaciente['cd_nombre'] ?></td>

                <th class="th">Tratamiento:</th>
                <td class="td"><?= $citaSql['tr_nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">Dirección:</th>
                <td class="td"><?= $citaSql['pc_direccion'] ?></td>

                <th class="th">Fecha:</th>
                <td class="td"><?= $citaFecha ?></td>
            </tr>
            <tr>
                <th class="th">Teléfono:</th>
                <td class="td"><?= $telefonoPaciente ?></td>

                <th class="th">Duración:</th>
                <td class="td"><?= $citaSql['ct_duracion'].' minutos' ?></td>
            </tr>
        </tbody>
    </table>
    
   
    <div class="tituloSeccion linea">Datos de la Evolución</div>
    <table class="tableList newTable">
        <tbody>
            <tr>
                <th class="th">Finalidad:</th>
                <td class="td"><?= $finalidad['fc_nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">Causa externa:</th>
                <td class="td"><?= $externa['ce_nombre'] ?></td>
            </tr>
        <?php if($citaSql['ct_idRip']>0){ ?>
            <tr>
                <th class="th">DX Ppal:</th>
                <td class="td"><?= $dx_ppal['rip_nombre'] ?></td>
            </tr>
        <?php } if($citaSql['ct_idRip1']>0){ ?>
            <tr>
                <th class="th">DX Rel. 1</th>
                <td class="td"><?= $dx_rel1['rip_nombre'] ?></td>
            </tr>
        <?php } if($citaSql['ct_idRip2']>0){ ?>
            <tr>
                <th class="th">DX Rel. 2</th>
                <td class="td"><?= $dx_rel2['rip_nombre'] ?></td>
            </tr>
        <?php } if($citaSql['ct_idRip3']>0){ ?>
            <tr>
                <th class="th">DX Rel. 3</th>
                <td class="td"><?= $dx_rel3['rip_nombre'] ?></td>
            </tr>
        <?php } ?>
            <tr>
                <th class="th">Descripción:</th>
                <td class="td"><?= $citaSql['ct_descripcion'] ?></td>
            </tr>
        </tbody>
    </table>


    <table class="tableList">
            <tr>
                <td colspan="2" style="width: 199mm;"></td>
            </tr>
            <tr>
                <td>
                    <div class="content_signature left">
                        <?php if(!empty($citaSql['ct_evoFirmaPaciente'])){ ?>
                            <img width="150" src="<?php echo $citaSql['ct_evoFirmaPaciente'] ?>">
                        <?php } ?>

                        <div class="option_signature_pad">
                            Paciente
                        </div>
                    </div>
                </td>
                <td>
                    <div class="content_signature right">
                        <?php if(!empty($citaSql['ct_evoFirmaUsuario'])){ ?>
                            <img width="150" src="<?php echo $citaSql['ct_evoFirmaUsuario'] ?>">
                        <?php } ?>

                        <div class="option_signature_pad">
                            Usuario
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    
    
</page>