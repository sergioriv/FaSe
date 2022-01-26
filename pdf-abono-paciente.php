<?php //include'config.php';

$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$clinicaRow[cl_idCiudad]'")->fetch_assoc();

$abono = $con->query("SELECT * FROM abonos, pacientes, sucursales WHERE abonos.ab_idPaciente = pacientes.IDPaciente AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.IDAbono = '$abonoID'")->fetch_assoc();

$tipoDocumento = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion='$abono[pc_idIdentificacion]'")->fetch_assoc();
$ciudadPaciente = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$abono[pc_idCiudad]'")->fetch_assoc();

$telefonoPaciente = '';
if($abono['pc_telefonoCelular']!=""){
    $telefonoPaciente = $abono['pc_telefonoCelular'];
} else {
    $telefonoPaciente = $abono['pc_telefonoFijo'];
}

    $usuario = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$abono[ab_idUsuario]'")->fetch_assoc();
    $nombreUsuarioAbono = '';
    $IDusuarioAbono = $usuario['us_id'];
    if($usuario['us_idRol']==1){
        $usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")->fetch_assoc();
        $nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
    } elseif($usuario['us_idRol']==2){
        $usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")->fetch_assoc();
        $nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
    } elseif($usuario['us_idRol']==3){
        $usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")->fetch_assoc();
        $nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
    }

    $estadoAbono = '';
    $watermark = '';
    if($abono['ab_estado']==0) { $estadoAbono = '(anulado) '; $watermark = 'backimg="img/anulado.png"'; }

    $formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abono[ab_idFormaPago]'")->fetch_assoc();

    if($abono['ab_idFormaPago']==2){
        $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abono[ab_idBanco]'")->fetch_assoc();
        $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abono['ab_cheque'];
    } else {
        $abonoFormaPago = $formaPago['fp_nombre'];
    }


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
<page backtop="28mm" backbottom="14mm" backleft="5mm" backright="5mm" pagegroup="new" <?= $watermark ?>>
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
                <td class="tituloDoc">Recibo de Caja #<?php echo $abono['ab_consecutivo'] ?></td>
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
                <td class="td"><?= $abono['pc_nombres'] ?></td>
            </tr>
            <tr>
                <th class="th">Documento:</th>
                <td class="td"><?= $tipoDocumento['ti_nombre'].' '.$abono['pc_identificacion'] ?></td>
            </tr>
            <tr>
                <th class="th">Direccion:</th>
                <td class="td"><?= $ciudadPaciente['cd_nombre'].' '.$abono['pc_direccion'] ?></td>
            </tr>
            <tr>
                <th class="th">Teléfono:</th>
                <td class="td"><?= $telefonoPaciente ?></td>
            </tr>
        </tbody>
    </table>

    <div class="tituloSeccion linea">Abono Realizado</div>
        <table class="tableList">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Sucursal</th>
                    <th>Forma Pago</th>
                    <th>Comentario</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" style="width: 196mm;"></td>
                </tr>
                <tr>
                    <td align="center"><?php echo $abono['ab_consecutivo'] ?></td>
                    <td align="center"><?php echo $abono['ab_fechaCreacion']; ?></td>
                    <td class="fijo"><?php echo $nombreUsuarioAbono; ?></td>
                    <td class="fijo"><?php echo $abono['sc_nombre'] ?></td>
                    <td align="center"><?php echo $abonoFormaPago ?></td>
                    <td class="fijo"><?php echo $abono['ab_comentario'] ?></td>
                    <td align="right"><?php echo $estadoAbono.'$'.number_format($abono['ab_abono'], 0, ".", ","); ?></td>
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
                        <?php if(!empty($abono['ab_firmaPaciente'])){ ?>
                            <img width="150" src="<?php echo $abono['ab_firmaPaciente'] ?>">
                        <?php } ?>

                        <div class="option_signature_pad">
                            Paciente
                        </div>
                    </div>
                </td>
                <td>
                    <div class="content_signature right">
                        <?php if(!empty($abono['ab_firmaUsuario'])){ ?>
                            <img width="150" src="<?php echo $abono['ab_firmaUsuario'] ?>">
                        <?php } ?>

                        <div class="option_signature_pad">
                            Usuario
                        </div>
                    </div>
                </td>
            </tr>
        </table>
</page>