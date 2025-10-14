<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material hinzufügen</title>
 
</head>
<body>
    <h1>Material hinzufügen</h1>
    <div class="form-container">
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
        <a href="material_table.php" class="back-link">Zurück zur Übersicht</a>
    </div>
</body>
</html>