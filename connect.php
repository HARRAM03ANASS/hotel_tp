<?php
$db_server_name = "localhost";
$db_name = "tp_hotel";
$user = "root";
$mdp = "";

$conexion = new PDO("mysql:host=$db_server_name;dbname=$db_name", $user, $mdp);
?>