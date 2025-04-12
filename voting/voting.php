<?php

require '../cabinet/db.php'; // Pripojenie k databáze cez db.php


// Získanie parametrov z URL
$id = $_GET['id'] ?? null;
$title = $_GET['title'] ?? null;
$voter_id = $_GET['voter_id'] ?? null;

// Kontrola, či sú parametre nastavené
if (!$id || !$title || !$voter_id) {
    die("Chyba: Neplatné údaje o hlasovaní.");
}

// Načítanie mena prihláseného používateľa z databázy
$sql = "SELECT meno FROM obcan WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $voter_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $voter = $result->fetch_assoc();
    $voter_name = $voter['meno'];
} else {
    die("Chyba: Používateľ s daným ID neexistuje.");
}

// Načítanie možností hlasovania z databázy
$sql = "SELECT moznost1, moznost2, moznost3, moznost4 FROM hlasovanie WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$options = [];
if ($row = $result->fetch_assoc()) {
    foreach (['moznost1', 'moznost2', 'moznost3', 'moznost4'] as $key) {
        if (!empty($row[$key])) {
            $options[] = $row[$key];
        }
    }
} else {
    die("Hlasovanie s daným ID neexistuje.");
}

// Spracovanie hlasovania
$message = "";
$error = "";
$results = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedOption = $_POST['vote'] ?? null;

    if ($selectedOption) {
        // Uloženie hlasu do databázy
        $sql = "INSERT INTO hlasovanie_vysledky (id_hlasovanie, id_obcan, moznost) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $id, $voter_id, $selectedOption);
        $stmt->execute();

        $message = "Ďakujeme za váš hlas! Hlasovali ste za možnosť: $selectedOption.";
    } else {
        $error = "Musíte zvoliť jednu možnosť.";
    }
}

// Načítanie výsledkov hlasovania z databázy
$sql = "SELECT moznost, COUNT(*) AS pocet FROM hlasovanie_vysledky WHERE id_hlasovanie = ? GROUP BY moznost";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
$totalVotes = 0;
while ($row = $result->fetch_assoc()) {
    $results[$row['moznost']] = $row['pocet'];
    $totalVotes += $row['pocet'];
}

// Výpočet percent
if ($totalVotes > 0) {
    foreach ($results as $option => $count) {
        $results[$option] = round(($count / $totalVotes) * 100);
    }
}

?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
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
        .voting-container {
            text-align: center;
            border-radius: 16px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        .option-container {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .option-container input[type="radio"] {
            display: none;
        }
        .option-container label {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 10px 15px;
            border: 2px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s ease;
            width: 100%;
            text-align: left;
        }
        .option-container input[type="radio"]:checked + label {
            background-color: #3A59D1;
            color: white;
            border-color: #3A59D1;
        }
        .progress-bar-container {
            margin: 20px 0;
            position: relative;
        }
        .progress-bar-container:not(:last-child)::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #ccc;
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
        button {
            background-color: #3A59D1;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #2f47aa;
        }
        .message {
            margin-top: 20px;
            font-size: 18px;
            color: green;
        }
        .error {
            margin-top: 10px;
            font-size: 16px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="voting-container">
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php if ($results): ?>
                <?php foreach ($results as $option => $percent): ?>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?php echo $percent; ?>%;"><?php echo $percent; ?>%</div>
                        <p><strong><?php echo htmlspecialchars($option); ?></strong></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <button onclick="location.href='votingmenu.php'">Späť</button>
        <?php else: ?>
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <p><strong>Hlasujúci:</strong> <?php echo htmlspecialchars($voter_name); ?></p>
            <form action="" method="POST">
                <?php foreach ($options as $index => $option): ?>
                    <div class="option-container">
                        <input type="radio" id="option<?php echo $index; ?>" name="vote" value="<?php echo htmlspecialchars($option); ?>">
                        <label for="option<?php echo $index; ?>"><?php echo htmlspecialchars($option); ?></label>
                    </div>
                <?php endforeach; ?>
                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <button type="submit">Hlasovať</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>