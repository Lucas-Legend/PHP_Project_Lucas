<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Materialübersicht</title>
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


?>

<div class="form-container">
    <h1>Materiallagersystem</h1>
    
    <!-- BENUTZER-INFO -->
    <div class="user-info">
        👤 Eingeloggt als: <?php echo htmlspecialchars($benutzername); ?> (ID: <?php echo $benutzer_id; ?>)
        <a href="logout.php" style="color: #dc3545; margin-left: 20px;">🚪 Ausloggen</a>
    </div>

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
                        Noch keine Materialien vorhanden.</a>
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
                            <a href="edit_material.php?id=<?php echo $row['id']; ?>" style="color: blue;">Bearbeiten</a> | 
                            <a href="delete_material.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Sind Sie sicher?')" style="color: red;">Löschen</a>
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