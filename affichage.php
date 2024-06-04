<?php
include 'connect.php';

try {
    $client_sql = "
    SELECT c.cin, c.nom, c.prenom, r.date_debut, r.date_fin, r.num_chambre, f.montant, GROUP_CONCAT(s.nom SEPARATOR ', ') AS services
    FROM client c
    LEFT JOIN reservation r ON c.cin = r.cin_client
    LEFT JOIN facture f ON r.id = f.id_reservation
    LEFT JOIN reservation_service rs ON r.id = rs.id_reservation
    LEFT JOIN service s ON rs.id_service = s.id
    GROUP BY c.cin, c.nom, c.prenom, r.date_debut, r.date_fin, r.num_chambre, f.montant
";

    $stmt = $conexion->query($client_sql);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Failed to fetch clients: " . $e->getMessage();
    $clients = [];
}

$conexion = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <title>Client List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        body{
            background-color:#f8f9fa
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="formulaire.php">Hotel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="affichage.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="formulaire.php">Formulaire</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    <h2>Client List</h2>
    <table class="table table-dark">
        <thead>
            <tr>
                <th>CIN</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Date Debut</th>
                <th>Date Fin</th>
                <th>Chambre</th>
                <th>Montant Total</th>
                <th>Services</th>
                <th colspan="2"><center>Action</center></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="8">Il n'y a aucun client.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($client['cin']); ?></td>
                        <td><?php echo htmlspecialchars($client['nom']); ?></td>
                        <td><?php echo htmlspecialchars($client['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($client['date_debut']); ?></td>
                        <td><?php echo htmlspecialchars($client['date_fin']); ?></td>
                        <td><?php echo htmlspecialchars($client['num_chambre']); ?></td>
                        <?php if (empty($client['services'])): ?>
                        <td>aucun service</td>
                        <?php else:  ?>
                        <td><?php echo htmlspecialchars($client['services']); ?></td>
                        <?php endif ?>
                        <td><?php echo htmlspecialchars($client['montant']); ?> DH</td>
                        <td >
                            <a href="supprimer.php?delete=<?php echo urlencode($client['cin']); ?>" id="delete" onclick="return confirm('Are you sure you want to delete this client?');" class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                        <td>
                            <a href="modifier.php?cin=<?php echo urlencode($client['cin']); ?>" class='btn btn-warning btn-sm'>Editer</a>
                        </td>
                    </tr>                   
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
