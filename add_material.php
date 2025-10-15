<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material hinzufügen</title>
    <style> 
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background-color: #48d243d3; 
        } 
        h1 { 
            text-align: center; 
            color: #333; 
        } 
        .form-container { 
            max-width: 500px; 
            margin: 0 auto; 
            background-color: #fff; 
            padding: 20px; 
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        } 
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
        } 
        input[type="text"], input[type="number"] { 
            width: 100%; 
            padding: 8px; 
            margin-bottom: 15px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
        } 
        input[type="submit"] { 
            background-color: #4CAF50; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        } 
        input[type="submit"]:hover { 
            background-color: #45a049; 
        } 
        .back-link { 
            display: inline-block; 
            margin-top: 10px; 
            text-decoration: none; 
            color: #0066cc; 
        } 
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: main.php?error=Bitte anmelden");
    exit();
}

$error = '';

// Datenbankverbindung
$pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');

// Formular verarbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $artikelnummer = $_POST['artikelnummer'];
    $bezeichnung = $_POST['bezeichnung'];
    $menge = $_POST['menge'];
    $lagerort = $_POST['lagerort'];

    if (empty($artikelnummer) || empty($bezeichnung) || empty($menge) || empty($lagerort)) {
        $error = "Bitte füllen Sie alle Felder aus.";
    } else {
        try {
            $sql = "INSERT INTO materialien (artikelnummer, bezeichnung, menge, lagerort) 
                    VALUES (:artikelnummer, :bezeichnung, :menge, :lagerort)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'artikelnummer' => $artikelnummer,
                'bezeichnung' => $bezeichnung,
                'menge' => $menge,
                'lagerort' => $lagerort
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
        <h1>Material hinzufügen</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="add_material.php">
            <label for="artikelnummer">Artikelnummer:</label>
            <input type="text" id="artikelnummer" name="artikelnummer" required>

            <label for="bezeichnung">Bezeichnung:</label>
            <input type="text" id="bezeichnung" name="bezeichnung" required>

            <label for="menge">Menge:</label>
            <input type="number" id="menge" name="menge" min="0" required>

            <label for="lagerort">Lagerort:</label>
            <input type="text" id="lagerort" name="lagerort" required>

            <input type="submit" value="Material hinzufügen">
        </form>

        <a href="material_table.php" class="back-link">← Zurück zur Übersicht</a><br>
        <a href="main.php" class="back-link">🚪 Logout</a>
    </div>

</body>
</html>
<?php $pdo = null; ?>