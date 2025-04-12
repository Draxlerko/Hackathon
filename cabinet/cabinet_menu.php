<!-- filepath: c:\xampp\htdocs\hackathon\Hackathon\cabinet_menu.php -->
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sekretariát</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #3A59D1, #3D90D7);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .menu-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        .menu-title {
            font-size: 1.5rem;
            color: #3A59D1;
            margin-bottom: 20px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 15px;
            text-decoration: none;
            color: #3A59D1;
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .menu-item:hover {
            background-color: #3A59D1;
            color: white;
            transform: translateY(-5px);
        }

        .menu-item-icon {
            width: 24px;
            height: 24px;
            background-color: #3A59D1;
            mask: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white"%3E%3Cpath stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8m-8 4h8m-8-8h8m-8-4h8"%3E%3C/path%3E%3C/svg%3E') no-repeat center;
            mask-size: contain;
        }

        .logout-button {
            display: inline-block;
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
            text-align: center;
        }

        .logout-button:hover {
            background-color: #C9302C;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <div class="menu-title">Sekretariát</div>
        <a href="news.php" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Oznamy a udalosti</span>
        </a>
        <a href="suggestions.php" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Podnety</span>
        </a>
        <a href="votingcreate.php" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Hlasovanie</span>
        </a>
        <a href="feedback_admin.php" class="menu-item">
            <div class="menu-item-icon"></div>
            <span>Prehľad feedbacku</span>
        </a>
        <a href="admin_login.php" class="logout-button">Odhlásiť Admina</a>
    </div>
</body>
</html>