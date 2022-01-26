<?php

$fechaHoyBarra = date('Y/m/d');

/*
 * Actualizacion de tareas
 */
	$con->query("UPDATE citas SET ct_tareaEstado = 1 WHERE ct_idClinica = '$sessionClinica' AND ct_tareaFecha < '$fechaHoyBarra' AND ct_tarea = 1 AND ct_tareaEstado = 0 ");


?>