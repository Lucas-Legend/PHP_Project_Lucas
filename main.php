<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Materiallagersystem (DEBUG)</title>
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
        .logo {
            text-align: center;
            font-size: 24px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .debug {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-family: monospace;
            font-size: 14px;
        }
        .debug strong { color: #856404; }
        .success { color: green; font-weight: bold; }
        .error-debug { color: red; font-weight: bold; }
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

session_start();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // =====================================================
    // DEBUG 1: EINGABE TESTEN
    // =====================================================
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    echo '<div class="debug">';
    echo '<strong>🔍 DEBUG SCHRITT 1: EINGABE</strong><br>';
    echo 'Benutzername: <span class="success">"'.htmlspecialchars($username).'"</span><br>';
    echo 'Passwort: <span class="success">"'.htmlspecialchars($password).'"</span><br>';
    echo '</div>';

    // =====================================================
    // DEBUG 2: LEERE FELDER TESTEN
    // =====================================================
    if (empty($username) || empty($password)) {
        $error = "Bitte Benutzername und Passwort eingeben.";
        echo '<div class="debug">';
        echo '<strong>🔍 DEBUG SCHRITT 2: LEERE FELDER → FEHLER!</strong><br>';
        echo 'Username leer: ' . (empty($username) ? '<span class="error-debug">JA</span>' : 'NEIN') . '<br>';
        echo 'Passwort leer: ' . (empty($password) ? '<span class="error-debug">JA</span>' : 'NEIN') . '<br>';
        echo '</div>';
    } else {
        echo '<div class="debug">';
        echo '<strong>✅ DEBUG SCHRITT 2: FELDER OK!</strong>';
        echo '</div>';

        // =====================================================
        // DEBUG 3: BENUTZER AUS DB HOLEN
        // =====================================================
        $sql = "SELECT id, benutzername, passwort FROM benutzer WHERE benutzername = :benutzername";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['benutzername' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo '<div class="debug">';
        echo '<strong>🔍 DEBUG SCHRITT 3: DB-SUCHE</strong><br>';
        echo 'Suche nach: <span class="success">"'.$username.'"</span><br>';
        echo 'Benutzer gefunden: ' . ($user ? '<span class="success">JA (ID: '.$user['id'].')</span>' : '<span class="error-debug">NEIN!</span>') . '<br>';
        if ($user) {
            echo 'DB-Passwort: <span class="success">'.substr($user['passwort'], 0, 20).'...</span><br>';
        }
        echo '</div>';

        // =====================================================
        // DEBUG 4: PASSWORT VERGLEICH
        // =====================================================
        if ($user) {
            $verify_ok = password_verify($password, $user['passwort']);
            echo '<div class="debug">';
            echo '<strong>🔍 DEBUG SCHRITT 4: PASSWORT-VERGLEICH</strong><br>';
            echo 'Eingegeben: <span class="success">"'.$password.'"</span><br>';
            echo 'DB-Hash: '.substr($user['passwort'], 0, 20).'...<br>';
            echo 'password_verify(): <span style="font-size: 18px;">' . ($verify_ok ? '<span class="success">✅ TRUE</span>' : '<span class="error-debug">❌ FALSE</span>') . '</span><br>';
            echo '</div>';

            if ($verify_ok) {
                // =====================================================
                // DEBUG 5: LOGIN ERFOLGREICH
                // =====================================================
                echo '<div class="debug">';
                echo '<strong>🎉 DEBUG SCHRITT 5: LOGIN ERFOLGREICH!</strong><br>';
                $_SESSION['benutzer_id'] = $user['id'];
                $_SESSION['benutzername'] = $user['benutzername'];
                echo 'Session gesetzt: ID='.$user['id'].', Name="'.$user['benutzername'].'"<br>';
                echo '→ Weiterleitung in 3 Sekunden...<br>';
                echo '</div>';
                
                // LOGIN ERFOLGREICH (Debug-Version - KEIN Redirect)
                echo '<div class="success" style="text-align:center; font-size:20px; margin:20px;">';
                echo '🎉 LOGIN ERFOLGREICH!<br>';
                echo '👤 '.$user['benutzername'].' (ID: '.$user['id'].')<br>';
                echo '<a href="material_table.php" style="background:#4CAF50;color:white;padding:10px;">→ Zur Tabelle</a>';
                echo '</div>';
                
                // TEMPORÄR: Redirect auskommentiert für Debug
                // header("Location: material_table.php?login=Willkommen, " . $username . "!");
                // exit();
                
            } else {
                echo '<div class="debug">';
                echo '<strong>❌ DEBUG SCHRITT 5: LOGIN FEHLGESCHLAGEN!</strong><br>';
                echo 'Passwort stimmt NICHT überein!<br>';
                echo '</div>';
                $error = "Falscher Benutzername oder Passwort!";
            }
        } else {
            echo '<div class="debug">';
            echo '<strong>❌ DEBUG SCHRITT 4: BENUTZER NICHT GEFUNDEN!</strong><br>';
            echo 'Benutzername "'.$username.'" existiert nicht!<br>';
            echo '</div>';
            $error = "Falscher Benutzername oder Passwort!";
        }
    }
} else {
    echo '<div class="debug">';
    echo '<strong>ℹ️ DEBUG: Formular noch nicht abgesendet</strong>';
    echo '</div>';
}
?>

    <div class="login-container">
        <div class="logo">🏭 Materiallagersystem (DEBUG MODE)</div>
        <h1>Anmeldung</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="main.php">
            <label for="username">Benutzername:</label>
            <input type="text" id="username" name="username" required 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">

            <label for="password">Passwort:</label>
            <input type="password" id="password" name="password" required>
            <input type="checkbox" onclick="togglePassword()"> Passwort anzeigen <br><br>

            <input type="submit" value="🔍 TESTEN (DEBUG)">
        </form>

        <div style="text-align: center; margin-top: 20px;">
            Noch kein Konto? <a href="Regestrieren.php">Registrieren</a>
        </div>
    </div>

</body>
</html>
<?php $pdo = null; ?>

<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    if (passwordField.type === "password") {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}
</script>