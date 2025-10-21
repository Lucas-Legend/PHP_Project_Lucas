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
        }
    }
}
?>
<body>
        <link rel="stylesheet" href="style.css">
    <div class="login-container">

        <div class="logo">🏭 Materiallagersystem</div>
        <h1>Anmeldung</h1>

        <form method="POST" action="main.php">
            <label for="username">Benutzername:</label>
            <input type="text" id="username" name="username" required 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">

            <label for="password">Passwort:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="🔐 Anmelden">
        </form>

        <div class="register-link">
            Noch kein Konto? <a href="Regestrieren.php">Registrieren</a>
        </div>
    </div>
</body>
</html>
<?php $pdo = null; ?>