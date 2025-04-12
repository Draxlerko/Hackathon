<?php
session_start();
session_destroy();
header('Location: ../index.php'); // Presmerovanie na hlavnú stránku
exit;
?>