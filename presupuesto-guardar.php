<?php include'config.php'; include'encrypt.php';

$pacienteID = $_POST['id'];
$presupuestoID = $_POST['presupuestoID'];
$planID = $_SESSION['consultorioTmpPlanID'];

$presupuestoConsecutivo = 0;

    $queryConsecutivo = $con->query("SELECT pp_consecutivo FROM presupuestos WHERE IDPresupuesto = '$presupuestoID'")->fetch_assoc();
    $presupuestoConsecutivo = $queryConsecutivo['pp_consecutivo'];

if($_POST['formulario']==1) {
    $totalPresupuesto = $_POST['pre-total'];
    
    if($presupuestoConsecutivo == 0){

        $consecutivoNuevoPresupesto = $con->query("SELECT pp_consecutivo FROM presupuestos WHERE pp_idClinica='$sessionClinica' AND pp_estado='1' ORDER BY IDPresupuesto DESC")->fetch_assoc();
        $presupuestoConsecutivo = $consecutivoNuevoPresupesto['pp_consecutivo']+1;

    }

    $firma_paciente = $_POST['firma_presupuesto_paciente'];
    $firma_usuario = $_POST['firma_presupuesto_usuario'];

    $query = $con->query("UPDATE presupuestos SET pp_consecutivo='$presupuestoConsecutivo', pp_firmaPaciente='$firma_paciente', pp_firmaUsuario='$firma_usuario', pp_valorTotal='$totalPresupuesto', pp_estado='1' WHERE IDPresupuesto='$presupuestoID'");



    $presupuestoTratamientos = $con->query("SELECT IDPlanTrataTrata, pltt_diente, pltt_precio, IDTratamiento, tr_nombre FROM plantratatratamientos AS pltt 
        INNER JOIN tratamientos AS tr ON pltt.pltt_idTratamiento = tr.IDTratamiento 
        WHERE pltt_idPlan = '$planID' ORDER BY pltt_diente ASC, tr_nombre ASC");
        while($presupuestoTratamientosRow = $presupuestoTratamientos->fetch_assoc()){
            
            if($_POST["tratamiento_".$presupuestoTratamientosRow['IDPlanTrataTrata']] == 1 ){
                // <?php echo $tratamientosRow['IDTratamiento']
                $query2 = $con->query("INSERT INTO presupuestotratamientos SET ppt_idPresupuesto='$presupuestoID', ppt_idTratamiento='$presupuestoTratamientosRow[IDTratamiento]', ppt_precio='$presupuestoTratamientosRow[pltt_precio]', ppt_dientes='$presupuestoTratamientosRow[pltt_diente]'");
            }
        }

    if( $query && $query2 ){ $_SESSION['consultoriosExito']=2; }
        else { $_SESSION['consultoriosExito']=1; }
}

$presupuestoURL = encrypt( 'id='.$presupuestoID );
?>
<script>
    //setTimeout("location.href = 'presupuesto-generar'",0);
    setTimeout(function() { window.open('presupuesto-generar?q=<?= $presupuestoURL ?>','_blank') },0);
</script>
<script>
    paginationPcPresupuestos(0);

    $('#msj-plan-tratamiento').html('<div class="contenedorAlerta"><input type="radio" id="alertExito"><label class="alerta exito" for="alertExito"><div>Presupuesto creado</div><div class="close">&times;</div></label></div>');

    $('#consultoriosModal').modal('hide');
    //setTimeout("location.href = '<?= $_SESSION[concultoriosAntes] ?>'",2000);
</script>