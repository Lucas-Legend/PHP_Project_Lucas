<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Material bearbeiten</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
$benutzer_id = $_SESSION['benutzer_id'];
$benutzername = $_SESSION['benutzername'];

// 1. DB verbinden
$pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');

// 2. Wenn Formular abgeschickt
if (isset($_POST['speichern'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $bezeichnung = $_POST['beschreibung'];
    $artikelnummer = $_POST['artikelnummer'];
    $menge = $_POST['menge'];
    $lagerort = $_POST['lagerort'];
    
    // Update SQL
    $sql = "UPDATE material SET name='$name', beschreibung='$bezeichnung', artikelnummer='$artikelnummer', menge=$menge, lagerort='$lagerort' WHERE id=$id";
    $pdo->query($sql);
    header("Location: material_table.php");
    exit();
}

// 3. Material holen
$id = $_GET['id'];
$sql = "SELECT * FROM material WHERE id = $id";
$result = $pdo->query($sql);
$material = $result->fetch();
?>

<div class="box">
    <div class="user-info">
        👤 Eingeloggt als: <?php echo $benutzername; ?> (ID: <?php echo $benutzer_id; ?>)
    </div>
    
    <h1>✏️ Material bearbeiten</h1>
    
    <div class="info">
        <div class="material-name">
            ID: <?php echo $material['id']; ?> | Erstellt von: Benutzer ID <?php echo $material['benutzer_id']; ?>
        </div>
    </div>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <label for="name">Material:</label>
        <input type="text" name="name" value="<?php echo $material['name']; ?>" required>
        
        <label for="Beschreibung">Bezeichnung:</label>
        <input type="text" name="beschreibung" value="<?php echo $material['beschreibung']; ?>" required>
        
        <label for="artikelnummer">Artikelnummer:</label>
        <input type="text" name="artikelnummer" value="<?php echo $material['artikelnummer']; ?>" required>
        
        <label for="menge">Menge:</label>
        <input type="number" name="menge" value="<?php echo $material['menge']; ?>" required>
        
        <label for="lagerort">Lagerort:</label>
        <input type="text" name="lagerort" value="<?php echo $material['lagerort']; ?>" required>
        
        <input type="submit" name="speichern" value="💾 SPEICHERN" class="add-btn">
    </form>

    <a href="material_table.php" class="back">← Zurück zur Übersicht</a><br>
    <a href="main.php" class="back">← Zurück zum Login</a>
</div>

</body>
</html>