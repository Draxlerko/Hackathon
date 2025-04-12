<?php
session_start();
require '../cabinet/db.php'; // Pripojenie k databáze cez db.php

// Skontroluje, či je používateľ prihlásený
if (!isset($_SESSION['user'])) {
    header("Location: ../login/login.php");
    exit();
}

// Získa meno prihláseného používateľa zo session
$voter_name = $_SESSION['user']['meno'];
$voter_id = $_SESSION['user']['id'];

// Načíta hlasovania z DB
$sql = "SELECT id, nazov AS title, info FROM hlasovanie";
$result = $conn->query($sql);

$votings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Nastavenie obrázku podľa ID (prispôsobené vašej štruktúre)
        switch ($row['id']) {
            case 1:
                $row['image'] = "images/chodniky.jpg";
                break;
            case 2:
                $row['image'] = "images/stromy.jpg";
                break;
            case 3:
                $row['image'] = "images/ihrisko.jpg";
                break;
            case 4:
                $row['image'] = "images/osvetlenie.jpg";
                break;
            default:
                $row['image'] = "images/default.jpg"; // fallback obrázok
        }
        $votings[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Menu</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #3A59D1, #3D90D7);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            position: relative;
        }
        .voter-info {
            margin-top: 20px;
            font-size: 18px;
            color: white;
            text-align: center;
        }
        .menu-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 30px;
            padding: 20px;
        }
        .voting-box {
            width: 200px;
            height: 250px;
            border-radius: 15px;
            background-color: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .voting-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        .voting-box img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }
        .voting-box h3 {
            margin: 10px 0 0;
            font-size: 18px;
            color: #333;
        }
        .info-button {
            margin-top: 10px;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            color: #3A59D1;
            background-color: rgb(255, 255, 255);
            border: 2px solid black;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .info-button:hover {
            transform: translateY(-5px);
            color: black;
            background-color: rgb(125, 151, 254);
        }
        .info-section {
            display: none;
            margin-top: 20px;
            width: 80%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .info-section h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #1E3C72, #2A5298);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .back-button:hover {
            background: linear-gradient(135deg, #2A5298, #1E3C72);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <a href="../login/user_menu.php" class="back-button">Späť do hlavného menu</a>
    <div class="voter-info">
        Prihlásený používateľ: <strong><?php echo htmlspecialchars($voter_name); ?></strong>
    </div>
    <div class="menu-container">
        <?php foreach ($votings as $voting): ?>
            <div>
                <div class="voting-box" onclick="location.href='voting.php?id=<?php echo $voting['id']; ?>&title=<?php echo urlencode($voting['title']); ?>&voter_id=<?php echo $voter_id; ?>&voter_name=<?php echo urlencode($voter_name); ?>'">
                    <img src="<?php echo htmlspecialchars($voting['image']); ?>" alt="Obrázok">
                    <h3><?php echo htmlspecialchars($voting['title']); ?></h3>
                </div>
                <button class="info-button" onclick="scrollToInfo(<?php echo $voting['id']; ?>)">Viac</button>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="info-sections">
        <?php foreach ($votings as $voting): ?>
            <div id="info-section-<?php echo $voting['id']; ?>" class="info-section">
                <h2>Ďalšie informácie pre: <?php echo htmlspecialchars($voting['title']); ?></h2>
                <p><?php echo htmlspecialchars($voting['info']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function scrollToInfo(id) {
            // Skryť všetky sekcie
            const allSections = document.querySelectorAll('.info-section');
            allSections.forEach(section => {
                section.style.display = 'none';
            });

            // Zobraziť iba vybranú sekciu
            const infoSection = document.getElementById(`info-section-${id}`);
            infoSection.style.display = 'block';
            infoSection.scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>