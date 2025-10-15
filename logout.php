<?php
session_start();
session_destroy();
header("Location: main.php?success=Erfolgreich abgemeldet");
exit();
?>