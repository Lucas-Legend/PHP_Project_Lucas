<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren - Materiallagersystem</title>
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
        .login-container { 
            max-width: 400px; 
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
        input[type="text"], input[type="password"] { 
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
            width: 100%;
        } 
        input[type="submit"]:hover { 
            background-color: #45a049; 
        } 
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .logo {
            text-align: center;
            font-size: 24px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Verbindung fehlgeschlagen: " . $e->getMessage();
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $benutzername = $_POST['benutzername'];
    $passwort = $_POST['passwort'];
    $passwort_wiederholen = $_POST['passwort_wiederholen'];

    // VALIDIERUNG
    if (empty($benutzername) || empty($passwort) || empty($passwort_wiederholen)) {
        $error = "Bitte alle Felder ausfüllen.";
    } elseif ($passwort !== $passwort_wiederholen) {
        $error = "Passwörter stimmen nicht überein!";
    } elseif (strlen($passwort) < 6) {
        $error = "Passwort muss mindestens 6 Zeichen lang sein.";
    } else {
        // BENUTZERNAME PRÜFEN (EXISTIERT SCHON?)
        $check_sql = "SELECT id FROM benutzer WHERE benutzername = :benutzername";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute(['benutzername' => $benutzername]);
        
        if ($check_stmt->rowCount() > 0) {
            $error = "Benutzername bereits vergeben!";
        } else {
            // NEUER BENUTZER ERSTELLEN (AUTO-HASH!)
            $hash_passwort = password_hash($passwort, PASSWORD_DEFAULT);
            $sql = "INSERT INTO benutzer (benutzername, passwort) VALUES (:benutzername, :passwort)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'benutzername' => $benutzername,
                'passwort' => $hash_passwort
            ]);
            
            $success = "Registrierung erfolgreich! Du kannst dich jetzt <a href='main.php'>anmelden</a>.";
        }
    }
}
?>

<div class="login-container">
    <div class="logo">🏭 Materiallagersystem</div>
    <h1>Registrieren</h1>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php else: ?>

    <form method="POST" action="Regestrieren.php">
        <label for="benutzername">Benutzername:</label>
        <input type="text" id="benutzername" name="benutzername" required 
               value="<?php echo isset($_POST['benutzername']) ? htmlspecialchars($_POST['benutzername']) : ''; ?>">

        <label for="passwort">Passwort:</label>
        <input type="password" id="passwort" name="passwort" required minlength="6">
        <input type="checkbox" onclick="togglePassword('passwort')"> Passwort anzeigen <br><br>

        <label for="passwort_wiederholen">Passwort wiederholen:</label>
        <input type="password" id="passwort_wiederholen" name="passwort_wiederholen" required minlength="6">
        <input type="checkbox" onclick="togglePassword('passwort_wiederholen')"> Passwort anzeigen <br><br>

        <input type="submit" value="👤 Registrieren">
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="main.php">← Zurück zum Login</a>
    </div>

    <?php endif; ?>
</div>

</body>
</html>
<?php $pdo = null; ?>

<script>
function togglePassword(fieldId) {
    var passwordField = document.getElementById(fieldId);
    if (passwordField.type === "password") {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}
</script>