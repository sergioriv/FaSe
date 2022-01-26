<?php
include'config-lobby.php';

$sql = $con->query("SELECT * FROM citas");
while($row = $sql->fetch_assoc()){
    $nuevaHora = $row['ct_horaCitaHasta']+15;
    echo $row['IDCita'].'\t'.$row['ct_horaCitaHasta'].'\t +15: '.$nuevaHora.'<br>';
    $con->query("UPDATE citas SET ct_horaCitaHasta = '$nuevaHora' WHERE IDCita = '$row[IDCita]'");
}

?>