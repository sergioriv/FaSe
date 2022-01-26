<?php //include'config.php';
$pacienteRow = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$pacienteID'")->fetch_assoc();

$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$clinicaRow[cl_idCiudad]'")->fetch_assoc();

$tipoDocumento = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion='$pacienteRow[pc_idIdentificacion]'")->fetch_assoc();
$ciudadPaciente = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$pacienteRow[pc_idCiudad]'")->fetch_assoc();

$telefonoPaciente = '';
if($pacienteRow['pc_telefonoCelular']!=""){
    $telefonoPaciente = $pacienteRow['pc_telefonoCelular'];
} else {
    $telefonoPaciente = $pacienteRow['pc_telefonoFijo'];
}

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
.newTable tbody th.th { font-size: 10px; width: 20mm; }
.newTable tbody td.td { font-size: 9px; width: 182mm; }
.newTable { text-align: left; }
.valorTotal {
    text-align: right;
    font-size: 10px;
    font-weight: bold;
}

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
<!--
	Header/Footer: 196mm   | padding 5mm
	Content max: 196mm     | padding 5mm
-->
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
            	<td class="tituloDoc">Plan de Tratamiento No. <?= $planQuery['plt_consecutivo'] ?></td>
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
                <td class="td"><?= $pacienteRow['pc_nombres'] ?></td>
            </tr>
            <tr>
                <th class="th">Documento:</th>
                <td class="td"><?= $tipoDocumento['ti_nombre'].' '.$pacienteRow['pc_identificacion'] ?></td>
            </tr>
            <tr>
                <th class="th">Direccion:</th>
                <td class="td"><?= $ciudadPaciente['cd_nombre'].' '.$pacienteRow['pc_direccion'] ?></td>
            </tr>
            <tr>
                <th class="th">Teléfono:</th>
                <td class="td"><?= $telefonoPaciente ?></td>
            </tr>
        </tbody>
    </table>

    <?php
                        $fasesSql = $con->query("SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica)");
                            while($fasesRow = $fasesSql->fetch_assoc()){
                                    $tratamientosFase = $con->query("SELECT * FROM plantratatratamientos AS pltt INNER JOIN tratamientos AS tr ON pltt.pltt_idTratamiento = tr.IDTratamiento WHERE tr_idFase='$fasesRow[IDFase]' AND pltt_idPlan='$planID'")->num_rows;
                                    if($tratamientosFase>0){
                            ?>
                                <div class="titulo tituloSecundario top">Fase <?= $fasesRow['fs_nombre'] ?></div>
                                <table class="tableList">
                                    <thead>
                                        <tr>
                                          <th>Tratamiento</th>
                                          <th>Diente</th>
                                        </tr>
                                     </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="width: 202mm;"></td>
                                        </tr>
                                <?php 
                                    $tratamientosSql = $con->query("SELECT IDPlanTrataTrata, pltt_diente, pltt_combo, tr_nombre FROM plantratatratamientos AS pltt INNER JOIN tratamientos AS tr ON pltt.pltt_idTratamiento = tr.IDTratamiento WHERE pltt_idPlan = '$planID' AND tr_idFase = '$fasesRow[IDFase]' ORDER BY pltt_diente ASC, tr_nombre ASC");
                                        while($tratamientosRow = $tratamientosSql->fetch_assoc()){
                                            
                                            $comboTratamiento = ''; 
                                            if($tratamientosRow['pltt_combo']>0){

                                                $comboTratamientoQuery = $con->query("SELECT tr_nombre FROM tratamientos WHERE IDTratamiento = '$tratamientosRow[pltt_combo]'")->fetch_assoc();
                                                $comboTratamiento = '<i>'.$comboTratamientoQuery['tr_nombre'].'</i> | '; 
                                            }
                                ?>
                                        <tr>
                                            <td><?php echo $comboTratamiento.$tratamientosRow['tr_nombre']?></td>
                                            <td align="center"><?php echo $tratamientosRow['pltt_diente'] ?></td>
                                        </tr>
                                <?php } ?>
                                    </tbody>
                                </table>
                            <?php   }
                            }
                                ?>

        <table class="tableList">
                <tr>
                    <td style="width: 205mm;">
                        <div class="titulo tituloSecundario top">Comentario</div>
                    </td>
                </tr>
            <tbody>
                <tr>
                    <td><?php echo $planQuery['plt_comentario']?></td>
                </tr>
            </tbody>
        </table>

        <table class="tableList">
            <tr>
                <td colspan="2" style="width: 205mm;"></td>
            </tr>
            <tr>
                <td>
                    <div class="content_signature left">
                        <?php if(!empty($planQuery['plt_firmaPaciente'])){ ?>
                            <img width="150" src="<?php echo $planQuery['plt_firmaPaciente'] ?>">
                        <?php } ?>

                        <div class="option_signature_pad">
                            Paciente
                        </div>
                    </div>
                </td>
                <td>
                    <div class="content_signature right">
                        <?php if(!empty($planQuery['plt_firmaUsuario'])){ ?>
                            <img width="150" src="<?php echo $planQuery['plt_firmaUsuario'] ?>">
                        <?php } ?>

                        <div class="option_signature_pad">
                            Usuario
                        </div>
                    </div>
                </td>
            </tr>
        </table>
</page>