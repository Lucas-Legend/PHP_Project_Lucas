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
            <label for="artikelbezeichnung">Artikelbezeichnung:</label>
            <input type="text" id="artikelbezeichnung" name="artikelbezeichnung" required>

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
    <!-- Kombination aus Ki mit eigener Arbeit für den Style -->
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

        input[type="text"], 

        input[type="number"] { 

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

    </style> 
</html>
<?php

// Datenbankverbindung herstellen 
$pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');


?>