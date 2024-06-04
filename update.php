<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    try {
        $conexion->beginTransaction();

        $update_client_sql = "UPDATE client SET nom = :nom, prenom = :prenom WHERE cin = :cin";
        $stmt = $conexion->prepare($update_client_sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':cin' => $cin
        ]);

        $conexion->commit();

        header('Location: affichage.php');
    } catch (Exception $e) {
        $conexion->rollBack();
        echo "error: " . $e->getMessage();
    }

    $conexion = null;
}
?>
