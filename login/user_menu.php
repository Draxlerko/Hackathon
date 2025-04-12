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
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .header {
            width: 100%;
            background-color: #3A59D1;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .notification {
            background-color: #FFA500;
            color: white;
            padding: 10px;
            width: 100%;
            text-align: center;
            font-size: 1rem;
            font-weight: bold;
        }

        .menu-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .menu-item {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 120px;
            height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #3A59D1;
            font-size: 1rem;
            font-weight: bold;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .menu-item-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
            background-color: #3A59D1;
            mask: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white"%3E%3Cpath stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8m-8 4h8m-8-8h8m-8-4h8"%3E%3C/path%3E%3C/svg%3E') no-repeat center;
            mask-size: contain;
        }

        .logout-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #D9534F;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .logout-button:hover {
            background-color: #C9302C;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="header">Hlavné Menu</div>
    <div class="notification">Notifikácia o blížiacej sa platbe</div>
    <div class="menu-container">
        <a href="podnet_form.php" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Podnety</span>
        </a>
        <!-- Odkaz na reservation.php s prenesením mena a priezviska -->
        <a href="../login/reservation.php?meno=<?php echo urlencode($_SESSION['user']['meno']); ?>&priezvisko=<?php echo urlencode($_SESSION['user']['priezvisko']); ?>" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Rezervácie</span>
        </a>
        <a href="../voting/votingmenu.php?meno=<?php echo urlencode($_SESSION['user']['meno']); ?>&id=<?php echo $_SESSION['user']['id']; ?>" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Anketa</span>
        </a>
    </div>
    <a href="logout.php" class="logout-button">Odhlásiť sa</a>
</body>
</html>