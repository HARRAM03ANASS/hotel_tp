<?php
include 'connect.php';

$chambre_sql = "SELECT num, type, tarif FROM chambre";
$chambre_result = $conexion->query($chambre_sql);

$service_sql = "SELECT id, nom, prix FROM service";
$service_result = $conexion->query($service_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Client Registration</title>
</head>
<body>
    <h2>Hotel Client Registration</h2>
    <form action="register.php" method="post">
        <label for="nom">First Name:</label>
        <input type="text" id="nom" name="nom" required><br><br>
        <label for="prenom">Last Name:</label>
        <input type="text" id="prenom" name="prenom" required><br><br>
        <label for="tele">Phone:</label>
        <input type="text" id="tele" name="tele" required><br><br>

        <label for="chambre">Chambre:</label> <br>
        <select name="chambre" id="chambre" required>
        <option value="" disabled selected>Select a chambre</option>
            <?php while($row = $chambre_result->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $row['num']; ?>">
                    <?php echo "Chambre " . $row['num'] . " - " . $row['type'] . " - " . $row['tarif'] . ' DH'; ?> 
                </option>
            <?php endwhile; ?>
        </select>
        <br>
<br>
        <label for="service">Service:</label> 
        <?php while($row=$service_result->fetch(PDO::FETCH_ASSOC)): ?>
          <br>  <input type="checkbox" id="service_<?php echo $row['id']; ?>" value=" <?php $row['id']?>" name="services"> 
            <label for="service_<?php echo $row['id']; ?>"> <?php echo $row['nom'] . $row['prix'] .'DH'?> </label>
        <?php  endwhile;?>
            <br>

        <input type="submit" value="Register">
    </form>
</body>
</html>

<?php
$conexion = null;
?>
