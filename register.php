<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $chambre = $_POST['chambre'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $services = isset($_POST['services']) ? $_POST['services'] : [];

    try {
        // Check if CIN already exists
        $check_cin_sql = "SELECT COUNT(*) FROM client WHERE cin = :cin";
        $stmt = $conexion->prepare($check_cin_sql);
        $stmt->execute([':cin' => $cin]);
        if ($stmt->fetchColumn() > 0) {
            $errorMessage = urlencode('Le CIN existe déjà. Veuillez utiliser un autre CIN.');
            header("Location: formulaire.php?error=$errorMessage");
            exit();
        }

        // Check if the room is available
        $check_room_sql = "SELECT disponible FROM chambre WHERE num = :num_chambre";
        $stmt = $conexion->prepare($check_room_sql);
        $stmt->execute([':num_chambre' => $chambre]);
        $room_available = $stmt->fetchColumn();
        if ($room_available === 'non') {
            $errorMessage = urlencode('La chambre est occupée. Veuillez choisir une autre chambre.');
            header("Location: formulaire.php?error=$errorMessage");
            exit();
        }

        $conexion->beginTransaction();

        // Insert client
        $client_sql = "INSERT INTO client (cin, nom, prenom) VALUES (:cin, :nom, :prenom)";
        $stmt = $conexion->prepare($client_sql);
        $stmt->execute([':cin' => $cin, ':nom' => $nom, ':prenom' => $prenom]);

        // Insert reservation
        $reservation_sql = "INSERT INTO reservation (date_debut, date_fin, cin_client, num_chambre) VALUES (:date_debut, :date_fin, :cin_client, :num_chambre)";
        $stmt = $conexion->prepare($reservation_sql);
        $stmt->execute([
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin,
            ':cin_client' => $cin,
            ':num_chambre' => $chambre
        ]);
        $reservation_id = $conexion->lastInsertId();

        // Calculate the total amount for the facture
        // Fetch the room tarif
        $chambre_tarif_sql = "SELECT tarif FROM chambre WHERE num = :num_chambre";
        $stmt = $conexion->prepare($chambre_tarif_sql);
        $stmt->execute([':num_chambre' => $chambre]);
        $chambre_tarif = $stmt->fetchColumn();

        // Calculate the number of days for the reservation
        $date_debut_obj = new DateTime($date_debut);
        $date_fin_obj = new DateTime($date_fin);
        $interval = $date_debut_obj->diff($date_fin_obj);
        $days = $interval->days;

        // Initialize the total amount with the room tarif
        $total_amount = $chambre_tarif * $days;

        foreach ($services as $service_id) {
            // Insert the service into reservation_service
            $service_sql = "INSERT INTO reservation_service (id_reservation, id_service) VALUES (:id_reservation, :id_service)";
            $stmt = $conexion->prepare($service_sql);
            $stmt->execute([':id_reservation' => $reservation_id, ':id_service' => $service_id]);

            // Fetch the service price
            $service_price_sql = "SELECT prix FROM service WHERE id = :id_service";
            $stmt = $conexion->prepare($service_price_sql);
            $stmt->execute([':id_service' => $service_id]);
            $service_price = $stmt->fetchColumn();

            // Add the service price to the total amount
            $total_amount += $service_price;
        }

        // Insert facture
        $facture_sql = "INSERT INTO facture (cin_client, montant, id_reservation) VALUES (:cin_client, :montant, :id_reservation)";
        $stmt = $conexion->prepare($facture_sql);
        $stmt->execute([
            ':cin_client' => $cin,
            ':montant' => $total_amount,
            ':id_reservation' => $reservation_id
        ]);

        // Mark the room as unavailable
        $update_chambre_sql = "UPDATE chambre SET disponible = 'non' WHERE num = :num_chambre";
        $stmt = $conexion->prepare($update_chambre_sql);
        $stmt->execute([':num_chambre' => $chambre]);

        // Commit transaction
        $conexion->commit();

        echo "Client registered successfully with room and services.";
    } catch (Exception $e) {
        // Rollback transaction on error
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        $errorMessage = urlencode($e->getMessage());
        header("Location: formulaire.php?error=$errorMessage");
        exit();
    }

    $conexion = null;
}
?>
