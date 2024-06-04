<?php
include 'connect.php';

if (isset($_GET['cin'])) {
    $cin = $_GET['cin'];

    $client_sql = "SELECT cin, nom, prenom FROM client WHERE cin = :cin";
    $stmt = $conexion->prepare($client_sql);
    $stmt->execute([':cin' => $cin]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        echo "Client not found.";
        exit;
    }
} else {
    echo "CIN not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <title>Update Client</title>
    <style>
        body{
            background-color:#f8f9fa

        }
        .form{
            margin:20px;

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
    <form action="update.php" method="post" class='form'>
    <h2>Update Client</h2>

        <input type="hidden" name="cin" value="<?php echo ($client['cin']); ?>">
        <label for="nom" class="form-label">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?php echo ($client['nom']); ?>" required><br><br>
        <label for="prenom" class="form-label">Prenom:</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo ($client['prenom']); ?>" required><br><br>
        <input type="submit" value="Update" class="btn btn-success">
    </form>

    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
