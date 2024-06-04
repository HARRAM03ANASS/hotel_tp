<?php
include 'connect.php';

$chambre_sql = "SELECT num, type, tarif, disponible FROM chambre";
$chambre_result = $conexion->query($chambre_sql);

$service_sql = "SELECT id, nom, prix FROM service";
$service_result = $conexion->query($service_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <title>Hotel Client Registration</title>
    <style>
        body{
            background-color:#f8f9fa
        }
    .oui {
            color: green;
        }
    .non {
            color: red;
        }
    .form{
        margin:20px
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
    <div class="form">
    <h2>Hotel Client Registration</h2>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
        </div>
    <?php endif; ?>
    <form action="register.php" method="post">
        <div class="mb-3">
            <label for="nom" class="form-label">CIN:</label>
            <input type="text" id="cin" name="cin" required><br><br>
        </div>
        
        <div class="mb-3">
            <label for="nom" class="form-label">Nom:</label>
            <input type="text" id="nom" name="nom" required><br><br>
        </div>
        
        <div class="mb-3"> 
            <label for="prenom" class="form-label">Prenom:</label>
            <input type="text" id="prenom" name="prenom" required><br><br>
        </div>

        <div class="mb-3">
            <label for="chambre">Chambre:</label> <br>
            <select name="chambre" id="chambre" required>
            <option value="" disabled selected class="form-select" aria-label="Default select example">Select a chambre</option>
                <?php while($row = $chambre_result->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php  
                    $disponible = $row['disponible'] == 'oui';
                    $colorClass = $disponible ? 'oui' : 'non';
                    ?>
                    <option value="<?php echo $row['num']; ?>" class="<?php echo $colorClass; ?>" >
                        <?php echo "Chambre " . $row['num'] . " - " . $row['type'] . " - " . $row['tarif'] . ' DH'; ?> 
                    </option>
                <?php endwhile; ?>
            </select>
            <br>
        </div>
<br>
        <label for="date_debut" class="form-label">Date debut:</label>
        <input type="date" id="date_debut" name="date_debut" required><br><br>
        <label for="date_fin" class="form-label">Date fin:</label>
        <input type="date" id="date_fin" name="date_fin" required><br><br>

        <label for="service" class="form-label">Services:</label> 
        <?php while($row=$service_result->fetch(PDO::FETCH_ASSOC)): ?>
            <br>
            <div>
                <input type="checkbox" id="service_<?php echo $row['id']; ?>" class="form-check-input" value="<?php echo $row['id']; ?>" name="services[]">
                    <label for="service_<?php echo $row['id']; ?>" class="form-check-label"><?php echo $row['nom'] . ' - ' . $row['prix'] . ' DH'; ?></label>
                </div>
        <?php endwhile; ?>
            <br>
        <input type="submit" value="Register"  class="btn btn-success">
    </form>
    </div>

    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conexion = null;
?>
