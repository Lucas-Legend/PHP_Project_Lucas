<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materialübersicht</title>
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
            max-width: 1000px; 
            margin: 0 auto; 
            background-color: #fff; 
            padding: 20px; 
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        } 
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .back-link { 
            display: inline-block; 
            margin-top: 10px; 
            text-decoration: none; 
            color: #0066cc; 
        } 
        .user-info {
            text-align: right;
            margin-bottom: 10px;
            color: #4CAF50;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .add-btn {
            background-color: #4CAF50; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 5px; 
            text-decoration: none; 
            display: inline-block; 
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['benutzer_id'])) {
    header("Location: main.php?error=Bitte anmelden");
    exit();
}

$benutzer_id = $_SESSION['benutzer_id'];
$benutzername = $_SESSION['benutzername'];

// Datenbankverbindung
$pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');

// Erfolgsmeldung
$success = isset($_GET['login']) ? $_GET['login'] : '';
$add_success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<div class="form-container">
    <h1>Materiallagersystem</h1>
    
    <!-- BENUTZER-INFO -->
    <div class="user-info">
        👤 Eingeloggt als: <?php echo htmlspecialchars($benutzername); ?> (ID: <?php echo $benutzer_id; ?>)
        <a href="logout.php" style="color: #dc3545; margin-left: 20px;">🚪 Ausloggen</a>
    </div>

    <!-- ERFOLGSMELDUNGEN -->
    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if ($add_success): ?>
        <div class="success"><?php echo htmlspecialchars($add_success); ?></div>
    <?php endif; ?>

    <a href="add_material.php" class="add-btn">➕ Neues Material hinzufügen</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Artikelnummer</th>
                <th>Bezeichnung</th>
                <th>Menge</th>
                <th>Lagerort</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // ADMIN (ID 1) = ALLE SEHEN | ANDERE = NUR EIGENE
                if ($benutzer_id == 1) 
                {
                    $sql = "SELECT * FROM material ORDER BY id ASC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                }
                else 
                {
                        $sql = "SELECT * FROM material WHERE benutzer_id = :benutzer_id ORDER BY id ASC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['benutzer_id' => $benutzer_id]);
                }
            if ($stmt->rowCount() == 0): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #666;">
                        Noch keine Materialien vorhanden. <a href="add_material.php">Erstes Material hinzufügen</a>
                    </td>
                </tr>
            <?php else: 
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['artikelnummer']); ?></td>
                        <td><?php echo htmlspecialchars($row['beschreibung']); ?></td>
                        <td><?php echo $row['menge']; ?></td>
                        <td><?php echo htmlspecialchars($row['lagerort']); ?></td>
                        <td>
                            <a href="edit_material.php?id=<?php echo $row['id']; ?>" style="color: #007bff;">Bearbeiten</a> | 
                            <a href="delete_material.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Sind Sie sicher?')" style="color: #dc3545;">Löschen</a>
                        </td>
                    </tr>
            <?php } endif; ?>
        </tbody>
    </table>

    <a href="main.php" class="back-link">← Zurück zum Login</a>
</div>

</body>
</html>
<?php $pdo = null; ?>