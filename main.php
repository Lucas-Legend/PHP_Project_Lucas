<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login - Materiallagersystem</title>
</head>
<body>

<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=materiallagerprojekt', 'root', '');
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = "Bitte Benutzername und Passwort eingeben.";
    } else {
        $sql = "SELECT id, benutzername, passwort FROM benutzer WHERE benutzername = :benutzername";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['benutzername' => $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['passwort'])) {
            $_SESSION['benutzer_id'] = $user['id'];
            $_SESSION['benutzername'] = $user['benutzername'];
            header("Location: material_table.php?login=Willkommen, " . $username . "!");
            exit();
        } else {
            $error = "Falscher Benutzername oder Passwort!";
        }
    }
}
?>

<div class="login-container">
    <div class="logo">🏭 Materiallagersystem</div>
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

        <input type="submit" value="🔐 ANmelden">
    </form>

    <div style="text-align: center; margin-top: 20px;">
        Noch kein Konto? <a href="Regestrieren.php">Registrieren</a>
    </div>
</div>

</body>
</html>

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