<?php
include 'connect.php';

if (isset($_GET['delete'])) {
    $cin = $_GET['delete'];
    try {
        // Begin transaction
        $conexion->beginTransaction();

        // Delete related records from the reservation_service table
        $stmt = $conexion->prepare("DELETE FROM reservation_service WHERE id_reservation IN (SELECT id FROM reservation WHERE cin_client = :cin)");
        $stmt->execute([':cin' => $cin]);

        // Delete related records from the facture table
        $stmt = $conexion->prepare("DELETE FROM facture WHERE cin_client = :cin");
        $stmt->execute([':cin' => $cin]);

        // Delete related records from the reservation table
        $stmt = $conexion->prepare("DELETE FROM reservation WHERE cin_client = :cin");
        $stmt->execute([':cin' => $cin]);

        // Delete the client from the client table
        $stmt = $conexion->prepare("DELETE FROM client WHERE cin = :cin");
        $stmt->execute([':cin' => $cin]);

        // Commit transaction
        $conexion->commit();

        // Redirect back to the client list page
        header('Location: affichage.php');
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conexion->rollBack();
        echo "Failed to delete client: " . $e->getMessage();
    }
} else {
    echo "No client ID specified for deletion.";
}

$conexion = null;
?>
