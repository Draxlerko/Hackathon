<?php
require 'db.php'; // Pripojenie k databáze cez db.php

// Načíta hlasovania z DB
$sql = "SELECT id, nazov AS title FROM hlasovanie";
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

// Náhodný výber občana z tabuľky `obcan`
$sql = "SELECT id, meno FROM obcan ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $voter = $result->fetch_assoc();
    $voter_id = $voter['id'];
    $voter_name = $voter['meno'];
} else {
    die("Chyba: Neboli nájdení žiadni občania v databáze.");
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Voting Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .menu-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .voting-box {
            width: 200px;
            height: 250px;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .voting-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
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
        .voter-info {
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="voter-info">
        Prihlásený používateľ: <strong><?php echo htmlspecialchars($voter_name); ?></strong>
    </div>
    <div class="menu-container">
        <?php foreach ($votings as $voting): ?>
            <div class="voting-box" onclick="location.href='voting.php?id=<?php echo $voting['id']; ?>&title=<?php echo urlencode($voting['title']); ?>&voter_id=<?php echo $voter_id; ?>&voter_name=<?php echo urlencode($voter_name); ?>'">
                <img src="<?php echo htmlspecialchars($voting['image']); ?>" alt="Obrázok">
                <h3><?php echo htmlspecialchars($voting['title']); ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>