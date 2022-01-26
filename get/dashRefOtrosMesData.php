<?php
    include('../config.php');

    $mesID = $_POST['mes'];
    if($mesID == ""){
        $mesID = $hoyMes;
    }
    
    //get number of rows
    $dashRefOtrosMesSql = $con->query("SELECT SUM(ct.ct_costo) AS recaudo, ref.IDReferencia, ref.ref_nombre FROM referencias AS ref
                                INNER JOIN pacientes AS pcref ON pcref.pc_idReferencia = ref.IDReferencia
                                INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
                                WHERE pcref.pc_idClinica = '$sessionClinica' AND pcref.pc_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$mesID' AND ct.ct_anoCita = '$hoyAno' AND ref.IDReferencia IN(10,11)
                                GROUP BY ref.IDReferencia
                                ORDER BY ref.ref_nombre ASC");

$arrayMeses = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$IDmes = (int) $mesID;
    
?>
                                    <table class="tableList">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($dashRefOtrosMesRow = $dashRefOtrosMesSql->fetch_assoc()){

                                            ?>
                                                <tr>
                                                    <td><?= $dashRefOtrosMesRow['ref_nombre'] ?></td>
                                                    <td align="right"><?= '$'.number_format($dashRefOtrosMesRow['recaudo'], 0, ".", ","); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

<script type="text/javascript">
    $('#contSelectRefOtros').hide();
    $('#contRefOtros').show();
    $('#contRefOtros').html("<?php echo $arrayMeses[$IDmes].' | Eventos y Otros' ?>");
</script>