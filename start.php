<?php

// Datenbankverbindung herstellen 
$pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');

session_start(); // Session starten für Login-Status
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // VALIDIERUNG: Felder prüfen
    if (empty($username) || empty($password)) {
        $error = "Bitte Benutzername und Passwort eingeben.";
    } else {
        // BENUTZER AUS DB HOLEN
        $sql = "SELECT id, username, password FROM benutzer WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // PRÜFEN: Benutzer existiert UND Passwort korrekt
        if ($user && password_verify($password, $user['password'])) {
            // LOGIN ERFOLGREICH!
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: material_table.php?login=Willkommen, " . $username . "!");
            exit();
        } else {
            $error = "Falscher Benutzername oder Passwort!";
        }
    }
}
?>
<body>
    <div class="login-container">
        <!-- ===================================================== -->
        <!-- LOGO & TITEL -->
        <!-- ===================================================== -->
        <div class="logo">🏭 Materiallagersystem</div>
        <h1>Anmeldung</h1>

        <!-- ===================================================== -->
        <!-- FEHLERMELDUNG -->
        <!-- ===================================================== -->
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>


        <form method="POST" action="main.php">
            <label for="username">Benutzername:</label>
            <input type="text" id="username" name="username" required 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">

            <label for="password">Passwort:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="🔐 Anmelden">
        </form>

        <!-- ===================================================== -->
        <!-- REGISTRIERUNGSLINK -->
        <!-- ===================================================== -->
        <div class="register-link">
            Noch kein Konto? <a href="Regestrieren.php">Registrieren</a>
        </div>
    </div>
</body>
</html>
<?php $pdo = null; ?>
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