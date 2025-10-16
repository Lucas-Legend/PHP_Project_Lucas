<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Material löschen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
$benutzer_id = $_SESSION['benutzer_id'];

// 1. DB verbinden
$pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');

// 2. Wenn Button geklickt
if (isset($_POST['loschen'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM material WHERE id = $id";
    $pdo->query($sql);
    header("Location: material_table.php?success=Gelöscht!");
    exit();
}

// 3. Material holen zum Anzeigen
$id = $_GET['id'];
$sql = "SELECT * FROM material WHERE id = $id";
$result = $pdo->query($sql);
$material = $result->fetch();
?>

<div class="box">
    <div class="user-info">
         Eingeloggt als: <?php echo $_SESSION['benutzername']; ?> (ID: <?php echo $benutzer_id; ?>)
    </div>
    
    <h1>Material löschen</h1>
    
    <div class="info">
        <div class="material-name">
            <?php echo $material['name']; ?> - <?php echo $material['beschreibung']; ?>
        </div>
        <div class="material-details">
            <strong>Artikelnummer:</strong> <?php echo $material['artikelnummer']; ?><br>
            <strong>Menge:</strong> <?php echo $material['menge']; ?> Stk.<br>
            <strong>Lagerort:</strong> <?php echo $material['lagerort']; ?><br>
            <strong>Erstellt von:</strong> Benutzer ID <?php echo $material['benutzer_id']; ?>
        </div>
    </div>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" name="loschen" value="🗑️ JA, ENDGÜLTIG LÖSCHEN!" class="red-btn">
    </form>

    <a href="material_table.php" class="back">← Zurück zur Übersicht</a>
</div>

</body>
</html>