<?php
require '../cabinet/db.php'; // Pripojenie k databáze cez db.php

// Načíta hlasovania z DB
$sql = "SELECT id, nazov AS title FROM hlasovanie";
$result = $conn->query($sql);

$votings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $votings[] = $row;
    }
}

// Načítanie výsledkov hlasovania, ak bola vybraná anketa
$results = [];
$totalVotes = 0;
$selectedVoting = null;

if (isset($_GET['id'])) {
    $votingId = intval($_GET['id']);
    $sql = "SELECT moznost, COUNT(*) AS pocet FROM hlasovanie_vysledky WHERE id_hlasovanie = ? GROUP BY moznost";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $votingId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $results[$row['moznost']] = $row['pocet'];
        $totalVotes += $row['pocet'];
    }

    // Načítanie názvu ankety
    $sql = "SELECT nazov FROM hlasovanie WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $votingId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $selectedVoting = $row['nazov'];
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Výsledky hlasovania</title>
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
        }

        .menu-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            margin-top: 50px;
        }

        .menu-title {
            font-size: 1.5rem;
            color: #3A59D1;
            margin-bottom: 20px;
            text-align: center;
        }

        .menu-item {
            display: block;
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 15px;
            text-decoration: none;
            color: #3A59D1;
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .menu-item:hover {
            background-color: #3A59D1;
            color: white;
            transform: translateY(-5px);
        }

        .results-container {
            margin-top: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .progress-bar-container {
            margin: 10px 0;
            position: relative;
        }

        .progress-bar {
            height: 20px;
            background-color: #3A59D1;
            border-radius: 5px;
            text-align: center;
            color: white;
            line-height: 20px;
            font-size: 14px;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3A59D1;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-align: center;
        }

        .back-button:hover {
            background-color: #2f47aa;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <div class="menu-title">Výber ankety</div>
        <?php foreach ($votings as $voting): ?>
            <a href="?id=<?php echo $voting['id']; ?>" class="menu-item">
                <?php echo htmlspecialchars($voting['title']); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($selectedVoting): ?>
        <div class="results-container">
            <h2>Výsledky: <?php echo htmlspecialchars($selectedVoting); ?></h2>
            <?php if ($totalVotes > 0): ?>
                <?php foreach ($results as $option => $count): ?>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?php echo round(($count / $totalVotes) * 100); ?>%;">
                            <?php echo round(($count / $totalVotes) * 100); ?>%
                        </div>
                        <p><strong><?php echo htmlspecialchars($option); ?></strong> - <?php echo $count; ?> hlasov</p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Zatiaľ neboli odovzdané žiadne hlasy.</p>
            <?php endif; ?>
            <a href="votingvyledky.php" class="back-button">← Späť na výber</a>
        </div>
    <?php endif; ?>
</body>
</html>