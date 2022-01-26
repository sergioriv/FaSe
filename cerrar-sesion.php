<?php
session_start();

unset($_SESSION['concultoriosClinica']);
unset($_SESSION['concultoriosUsuario']);
unset($_SESSION['concultoriosRol']);

header ("Location: ./");
?>