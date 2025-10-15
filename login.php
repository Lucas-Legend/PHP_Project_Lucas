$_SESSION['user_id'] = $user['id'];  // ← Benutzer-ID wird AUTOMATISCH gespeichert!
header("Location: material_table.php?login=Willkommen, " . $username . "!");