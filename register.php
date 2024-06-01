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
        $conexion->beginTransaction();

        $client_sql = "INSERT INTO client (cin, nom, prenom) VALUES (:cin, :nom, :prenom)";
        $stmt = $conexion->prepare($client_sql);
        $stmt->execute([':cin' => $cin, ':nom' => $nom, ':prenom' => $prenom]);

        $reservation_sql = "INSERT INTO reservation (date_debut, date_fin, cin_client, num_chambre) VALUES (:date_debut, :date_fin, :cin_client, :num_chambre)";
        $stmt = $conexion->prepare($reservation_sql);
        $stmt->execute([
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin,
            ':cin_client' => $cin,
            ':num_chambre' => $chambre
        ]);
        $reservation_id = $conexion->lastInsertId();

        $update_chambre_sql = "UPDATE chambre SET disponible = 'non' WHERE num = :num_chambre";
        $stmt = $conexion->prepare($update_chambre_sql);
        $stmt->execute([':num_chambre' => $chambre]);

        $chambre_tarif_sql = "SELECT tarif FROM chambre WHERE num = :num_chambre";
        $stmt = $conexion->prepare($chambre_tarif_sql);
        $stmt->execute([':num_chambre' => $chambre]);
        $chambre_tarif = $stmt->fetchColumn();

        $total_amount = $chambre_tarif;

        foreach ($services as $service_id) {
            try {
                $service_sql = "INSERT INTO reservation_service (id_reservation, id_service) VALUES (:id_reservation, :id_service)";
                $stmt = $conexion->prepare($service_sql);
                $stmt->execute([':id_reservation' => $reservation_id, ':id_service' => $service_id]);
        
                $service_price_sql = "SELECT prix FROM service WHERE id = :id_service";
                $stmt = $conexion->prepare($service_price_sql);
                $stmt->execute([':id_service' => $service_id]);
                $service_price = $stmt->fetch(PDO::FETCH_ASSOC)['prix'];
        
                if ($service_price !== false) {
                    $total_amount += $service_price;
                } else {
                    echo "Failed to fetch price for service with ID: $service_id";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        

        $facture_sql = "INSERT INTO facture (cin_client, montant, id_reservation) VALUES (:cin_client, :montant, :id_reservation)";
        $stmt = $conexion->prepare($facture_sql);
        $stmt->execute([
            ':cin_client' => $cin,
            ':montant' => $total_amount,
            ':id_reservation' => $reservation_id
        ]);

        $conexion->commit();

        header('Location: http://localhost/hotel_tp/formulaire.php');
        exit();
    } catch (Exception $e) {
        $conexion->rollBack();
        echo "Erruer: " . $e->getMessage();
    }

    $conexion = null;
}
?>
