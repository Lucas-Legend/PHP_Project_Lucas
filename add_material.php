<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Material hinzufügen</title>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['benutzer_id'])) {
    header("Location: main.php?error=Bitte anmelden");
    exit();
}

// BENUTZER-ID HOLEN
$benutzer_id = $_SESSION['benutzer_id'];
$benutzername = $_SESSION['benutzername'];

$error = '';

// Datenbankverbindung
$pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');

// Formular verarbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $material = $_POST['material'];
    $bezeichnung = $_POST['bezeichnung'];
    $artikelnummer = $_POST['artikelnummer'];
    $menge = $_POST['menge'];
    $lagerort = $_POST['lagerort'];

    if (empty($material) || empty($bezeichnung) || empty($artikelnummer) || empty($menge) || empty($lagerort)) {
        $error = "Bitte füllen Sie alle Felder aus.";
    } else {
        try {
            // ✅ + BEZEICHNUNG!
            $sql = "INSERT INTO material (name, beschreibung, artikelnummer, menge, lagerort, benutzer_id) 
                    VALUES (:name, :beschreibung, :artikelnummer, :menge, :lagerort, :benutzer_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $material,
                'beschreibung' => $bezeichnung,     // ← NEU!
                'artikelnummer' => $artikelnummer,
                'menge' => $menge,
                'lagerort' => $lagerort,
                'benutzer_id' => $benutzer_id
            ]);
            header("Location: material_table.php?success=Material erfolgreich hinzugefügt");
            exit();
        } catch(PDOException $e) {
            $error = "Fehler beim Hinzufügen: " . $e->getMessage();
        }
    }
}
?>

    <div class="form-container">
        <!-- BENUTZER-INFO -->
        <div class="user-info">
            👤 Eingeloggt als: <?php echo htmlspecialchars($benutzername); ?> (ID: <?php echo $benutzer_id; ?>)
        </div>
        
        <h1>Material hinzufügen</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="add_material.php">
            <label for="material">Material:</label>
            <input type="text" id="material" name="material" required 
                   value="<?php echo isset($_POST['material']) ? htmlspecialchars($_POST['material']) : ''; ?>">

            <!-- NEUES FELD: BEZEICHNUNG! -->
            <label for="bezeichnung">Bezeichnung:</label>
            <input type="text" id="bezeichnung" name="bezeichnung" required 
                   value="<?php echo isset($_POST['bezeichnung']) ? htmlspecialchars($_POST['bezeichnung']) : ''; ?>">

            <label for="artikelnummer">Artikelnummer:</label>
            <input type="text" id="artikelnummer" name="artikelnummer" required 
                   value="<?php echo isset($_POST['artikelnummer']) ? htmlspecialchars($_POST['artikelnummer']) : ''; ?>">

            <label for="menge">Menge:</label>
            <input type="number" id="menge" name="menge" min="0" required 
                   value="<?php echo isset($_POST['menge']) ? htmlspecialchars($_POST['menge']) : ''; ?>">

            <label for="lagerort">Lagerort:</label>
            <input type="text" id="lagerort" name="lagerort" required 
                   value="<?php echo isset($_POST['lagerort']) ? htmlspecialchars($_POST['lagerort']) : ''; ?>">

            <input type="submit" value="➕ Material hinzufügen">
        </form>

        <a href="material_table.php" class="back-link">← Zurück zur Übersicht</a><br>
        <a href="main.php" class="back">← Zurück zum Login</a>
    </div>

</body>
</html>
<?php $pdo = null; ?>