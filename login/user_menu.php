<?php
session_start();

// Skontroluj, či je používateľ prihlásený
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hlavné Menu</title>
    <!-- Napojenie na externý CSS súbor -->
    <link rel="stylesheet" href="user_menu.css">
</head>
<body>
    <div class="header">Hlavné Menu</div>
    <div class="notification">Notifikácia o blížiacej sa platbe</div>
    <div class="menu-container">
        <a href="podnet_form.php" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Podnety</span>
        </a>
        <a href="../login/reservation.php?meno=<?php echo urlencode($_SESSION['user']['meno']); ?>&priezvisko=<?php echo urlencode($_SESSION['user']['priezvisko']); ?>" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Rezervácie</span>
        </a>
        <a href="../voting/votingmenu.php?meno=<?php echo urlencode($_SESSION['user']['meno']); ?>&id=<?php echo $_SESSION['user']['id']; ?>" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Anketa</span>
        </a>
        <a href="../login/payments.php" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Platby</span>
        </a>
    </div>
    <a href="logout.php" class="logout-button">Odhlásiť sa</a>
</body>
</html>