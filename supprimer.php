<?php
include 'connect.php';

if (isset($_GET['delete'])) {
    $cin = $_GET['delete'];
    try {
        $conexion->beginTransaction();

        $stmt = $conexion->prepare("DELETE FROM reservation_service WHERE id_reservation IN (SELECT id FROM reservation WHERE cin_client = :cin)");
        $stmt->execute([':cin' => $cin]);

        $stmt = $conexion->prepare("DELETE FROM facture WHERE cin_client = :cin");
        $stmt->execute([':cin' => $cin]);

        $stmt = $conexion->prepare("DELETE FROM reservation WHERE cin_client = :cin");
        $stmt->execute([':cin' => $cin]);

        $stmt = $conexion->prepare("DELETE FROM client WHERE cin = :cin");
        $stmt->execute([':cin' => $cin]);

        $conexion->commit();

        header('Location: affichage.php');
        exit();
    } catch (Exception $e) {
        $conexion->rollBack();
        echo "Failed to delete client: " . $e->getMessage();
    }
} else {
    echo "No client ID specified for deletion.";
}

$conexion = null;
?>
