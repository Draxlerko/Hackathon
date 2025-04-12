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
    <link rel="stylesheet" href="user_menu.css">
    <style>
        .menu-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 150px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #3A59D1;
            font-size: 1.2rem;
            font-weight: bold;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .menu-item-icon {
            width: 50px;
            height: 50px;
            background-color: #3A59D1;
            border-radius: 50%;
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .icon-line {
            width: 30px;
            height: 4px;
            background-color: white;
            margin: 2px 0;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 60px;
        }

        .logout-button, .back-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 150px;
            height: 50px;
            background-color: white;
            color: #3A59D1;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-align: center;
            border: 2px solid #3A59D1;
        }

        .logout-button:hover, .back-button:hover {
            background-color: #3A59D1;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">Hlavné Menu</div>
    <div class="notification">Notifikácia o blížiacej sa platbe</div>
    <div class="menu-container">
        <a href="podnet_form.php" class="menu-item">
            <div class="menu-item-icon">
                <div class="icon-line"></div>
                <div class="icon-line"></div>
                <div class="icon-line"></div>
            </div>
            <span>Podnety</span>
        </a>
        <a href="../login/reservation.php?meno=<?php echo urlencode($_SESSION['user']['meno']); ?>&priezvisko=<?php echo urlencode($_SESSION['user']['priezvisko']); ?>" class="menu-item">
            <div class="menu-item-icon">
                <div class="icon-line"></div>
                <div class="icon-line"></div>
                <div class="icon-line"></div>
            </div>
            <span>Rezervácie</span>
        </a>
        <a href="../voting/votingmenu.php?meno=<?php echo urlencode($_SESSION['user']['meno']); ?>&id=<?php echo $_SESSION['user']['id']; ?>" class="menu-item">
            <div class="menu-item-icon">
                <div class="icon-line"></div>
                <div class="icon-line"></div>
                <div class="icon-line"></div>
            </div>
            <span>Anketa</span>
        </a>
        <a href="/hackathon/Hackathon/paygate.php" class="menu-item">
            <div class="menu-item-icon">
                <div class="icon-line"></div>
                <div class="icon-line"></div>
                <div class="icon-line"></div>
            </div>
            <span>Platby</span>
        </a>
        <a href="profil.php" class="menu-item">
            <div class="menu-item-icon">
                <div class="icon-line"></div>
                <div class="icon-line"></div>
                <div class="icon-line"></div>
            </div>
            <span>Profil</span>
        </a>
    </div>
    <div class="action-buttons">
        <a href="logout.php" class="logout-button">Odhlásiť sa</a>
        <a href="/hackathon/Hackathon/index.php" class="back-button">Vrátiť sa späť</a>
    </div>
</body>
</html>