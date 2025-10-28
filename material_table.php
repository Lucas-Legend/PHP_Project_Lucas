<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Materialübersicht</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
$benutzer_id = $_SESSION['benutzer_id'];
$benutzername = $_SESSION['benutzername'];

// 1. DB verbinden
$pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');

// 2. Suche holen
$suche = isset($_GET['suche']) ? $_GET['suche'] : '';
$attribut = isset($_GET['attribut']) ? $_GET['attribut'] : 'all';

// 3. SQL: Admin=ALLE | User=EIGENE
$sql = "SELECT * FROM material";
$admin_id = 1;
$where = [];
if ($suche) {
    if ($attribut == 'all') {
        $where[] = "(id LIKE '%$suche%' OR name LIKE '%$suche%' OR beschreibung LIKE '%$suche%' OR artikelnummer LIKE '%$suche%' OR menge LIKE '%$suche%' OR lagerort LIKE '%$suche%' OR benutzer_id LIKE '%$suche%')";
    } else {
        $where[] = "$attribut LIKE '%$suche%'";
    }
}
if ($benutzer_id != $admin_id) {
    $where[] = "benutzer_id = $benutzer_id";
}
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY id DESC";

$result = $pdo->query($sql);
?>

<div class="box">
    <div class="user-info">
        👤 Eingeloggt als: <?php echo $benutzername; ?> (ID: <?php echo $benutzer_id; ?>)
    </div>
    
    <h1>🏭 Materialübersicht</h1>

    <form method="GET" action="material_table.php">
        <?php echo '<div style="text-align: right;"> Anmeldung: ' . (new DateTime())->format('d-m-Y H:i') . '</div>';?>
        <label for="attribut">Suche nach: </label>
        <select id="attribut" name="attribut">
            <option value="all" <?php echo $attribut == 'all' ? 'selected' : ''; ?>>Alle Felder</option>
            <option value="id" <?php echo $attribut == 'id' ? 'selected' : ''; ?>>ID</option>
            <option value="name" <?php echo $attribut == 'name' ? 'selected' : ''; ?>>Material</option>
            <option value="beschreibung" <?php echo $attribut == 'beschreibung' ? 'selected' : ''; ?>>Beschreibung</option>
            <option value="artikelnummer" <?php echo $attribut == 'artikelnummer' ? 'selected' : ''; ?>>Artikelnummer</option>
            <option value="menge" <?php echo $attribut == 'menge' ? 'selected' : ''; ?>>Menge</option>
            <option value="lagerort" <?php echo $attribut == 'lagerort' ? 'selected' : ''; ?>>Lagerort</option>
            <option value="benutzer_id" <?php echo $attribut == 'benutzer_id' ? 'selected' : ''; ?>>Benutzer ID</option>
        </select>
        <label for="suche">Suchbegriff:</label>
        <input type="text" id="suche" name="suche" value="<?php echo htmlspecialchars($suche); ?>">
        <input type="submit" value="🔍 Suchen" class="add-btn">
    </form>
    <br>

    <a href="add_material.php" class="add-btn">➕ Neues Material</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Artikel</th>
            <th>Material</th>
            <th>Menge</th>
            <th>Ort</th>
            <th>Aktionen</th>
        </tr>
        <?php if ($result->rowCount() == 0): ?>
            <tr>
                <td colspan="7" style="text-align: center; color: #666;">
                    Keine Materialien gefunden. <a href="add_material.php">Erstes Material hinzufügen</a>
                </td>
            </tr>
        <?php else: ?>
            <?php while ($row = $result->fetch()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['artikelnummer']; ?></td>
                <td><?php echo $row['name']; ?> - <?php echo $row['beschreibung']; ?></td>
                <td><?php echo $row['menge']; ?></td>
                <td><?php echo $row['lagerort']; ?></td>
                <td>
                    <a href="edit_material.php?id=<?php echo $row['id']; ?>" class="edit">bearbeiten</a> | 
                    <a href="delete_material.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Löschen?')">Löschen</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>

    <a href="main.php" class="back">← Zurück zum Login</a>
</div>

</body>
</html>