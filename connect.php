<?php
$db_server_name="localhost";
$db_name="hoteldb";
$user="root";
$mdp="";


$conexion=new pdo("mysql:host=$db_server_name;dbname=$db_name",$user,$mdp);
// if(isset($_POST['save'])){
//     $req="INSERT INTO client (nom,prenom,tele) VALUES ('".$_POST["nom"]."','".$_POST["prenom"]."','".$_POST["tele"]."')";
//     $conexion->query($req);
//     echo"<h1>insertion parfaite</h1>";
// };

if($conexion){
    echo'connexion parfaite';
}else{
    echo'la';
}