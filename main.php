<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Materiallagersystem</title>
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
    </style>
</head>
<body>
<?php
// =====================================================
// DATENBANKVERBINDUNG & LOGIN
// =====================================================
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
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Bitte Benutzername und Passwort eingeben.";
    } else {
        $sql = "SELECT id, benutzername, password FROM benutzer WHERE benutzername = :benutzername";
        $stmt = $pdo->prepare($sql);
        $_SESSION['benutzer_id'] = $user['id'];
        $_SESSION['benutzername'] = $user['benutzername'];
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
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

            <input type="submit" value="🔐 Anmelden">
        </form>

        <div style="text-align: center; margin-top: 20px;">
            Noch kein Konto? <a href="Regestrieren.php">Registrieren</a>
        </div>
    </div>

</body>
</html>
<?php $pdo = null; ?>