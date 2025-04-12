<?php
include 'db.php';

$success = '';
$error = '';

// Získame obce z databázy pre dropdown
$obce = [];
$result = $conn->query("SELECT id, nazov FROM obec");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $obce[] = $row;
    }
}

// Spracovanie formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_id'])) {
        $update_id = intval($_POST['update_id']);
        $nazov = trim($_POST['nazov']);
        $typ = $_POST['typ'];
        $text = trim($_POST['text']);
        $datum_od = $_POST['datum_od'];
        $datum_do = $_POST['datum_do'];
        $id_obec = intval($_POST['id_obec']);

        if (!empty($nazov) && !empty($typ) && !empty($text) && !empty($datum_od) && !empty($datum_do) && $id_obec > 0) {
            $update_query = "UPDATE news SET typ = ?, nazov = ?, text = ?, datum_od = ?, datum_do = ?, id_obec = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sssssii", $typ, $nazov, $text, $datum_od, $datum_do, $id_obec, $update_id);

            if ($stmt->execute()) {
                $success = "✅ Udalosť bola úspešne aktualizovaná!";
            } else {
                $error = "❌ Chyba pri aktualizácii: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "❗ Vyplň všetky polia.";
        }
    } else {
        $nazov = trim($_POST['nazov']);
        $typ = $_POST['typ'];
        $text = trim($_POST['text']);
        $datum_od = $_POST['datum_od'];
        $datum_do = $_POST['datum_do'];
        $id_obec = intval($_POST['id_obec']);

        if (!empty($nazov) && !empty($typ) && !empty($text) && !empty($datum_od) && !empty($datum_do) && $id_obec > 0) {
            $stmt = $conn->prepare("INSERT INTO news (typ, nazov, text, datum_od, datum_do, id_obec) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $typ, $nazov, $text, $datum_od, $datum_do, $id_obec);

            if ($stmt->execute()) {
                $success = "✅ Úspešne uložené!";
            } else {
                $error = "❌ Chyba pri ukladaní: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "❗ Vyplň všetky polia.";
        }
    }
}

// Načítanie existujúcich udalostí s filtrovaním podľa názvu a typu
$events = [];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$event_type = isset($_GET['event_type']) ? $_GET['event_type'] : '';

$events_query = "SELECT id, typ, nazov, text, datum_od, datum_do, id_obec FROM news WHERE nazov LIKE ? ";
if ($event_type != '') {
    $events_query .= "AND typ = ? ";
}
$events_query .= "ORDER BY datum_od DESC LIMIT 10";

$stmt = $conn->prepare($events_query);
$search_param = "%$search%";
if ($event_type != '') {
    $stmt->bind_param("ss", $search_param, $event_type);
} else {
    $stmt->bind_param("s", $search_param);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
$stmt->close();

// Odstránenie udalosti
if (isset($_GET['delete'])) {
    $event_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM news WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $event_id);
    if ($stmt->execute()) {
        $success = "✅ Udalosť bola úspešne odstránená!";
    } else {
        $error = "❌ Chyba pri odstraňovaní udalosti.";
    }
    $stmt->close();
}

// Editovanie udalosti
if (isset($_GET['edit'])) {
    $event_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM news WHERE id = ?";
    $stmt = $conn->prepare($edit_query);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $edit_event = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Pridať oznam / udalosť</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #3A59D1, #3D90D7);
            height: 100vh;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 20px;
            position: relative; /* Nastavenie relatívnej pozície pre umiestnenie tlačidla */
        }

        form {
            width: 48%;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .history {
            width: 48%;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #3A59D1;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #3A59D1;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        textarea {
            resize: vertical;
        }

        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #333;
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 8px;
        }

        input[type="submit"] {
            background-color: #3A59D1;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #2f47aa;
        }

        .msg {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
        }

        .success {
            color: #2ecc71;
        }

        .error {
            color: #e74c3c;
        }

        .event-box {
            background-color: white;
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #3A59D1;
        }

        .event-header h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        .event-actions {
            display: flex;
            gap: 10px;
        }

        .event-actions a {
            color: #3A59D1;
            text-decoration: none;
            font-size: 1rem;
        }

        .event-actions a:hover {
            color: #2f47aa;
        }

        .existing-events {
            margin-top: 30px;
        }

        .history h3 {
            color: #3A59D1;
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar {
            width: 150px;
            padding: 8px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .search-filter {
            width: 150px;
            padding: 8px;
            margin-left: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .scrollable-events {
            max-height: 650px; /* Zvýšenie výšky pre zobrazenie viac udalostí */
            overflow-y: auto; /* Povolenie vertikálneho skrolovania */
            padding-right: 10px; /* Priestor pre scrollbar */
        }

        .more-events {
            display: none; /* Skrytie tlačidla šípky */
        }

        .arrow-button {
            background-color: transparent;
            border: none;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .arrow-button::before,
        .arrow-button::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            border: solid #3A59D1;
            border-width: 0 0 4px 4px;
            transform: rotate(45deg);
        }

        .arrow-button::before {
            top: 5px;
            transform: rotate(315deg); /* Otočenie o 180 stupňov */
        }

        .arrow-button::after {
            top: 20px;
            transform: rotate(315deg); /* Otočenie o 180 stupňov */
        }

        .arrow-button:hover::before,
        .arrow-button:hover::after {
            border-color: #2f47aa;
        }

        .hidden-events {
            display: none;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 5px 10px;
            background-color: #3A59D1;
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: bold;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-button:hover {
            background-color: #2f47aa;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="container">

        <!-- Tlačidlo na návrat -->
        <a href="cabinet_menu.php" class="back-button">← Späť</a>

        <!-- Formulár na tvorbu udalosti -->
        <form method="post">
            <h2>📝 <?= isset($edit_event) ? 'Upraviť' : 'Pridať' ?> oznam / udalosť</h2>

            <input type="hidden" name="update_id" value="<?= isset($edit_event) ? $edit_event['id'] : '' ?>">

            <label for="nazov">Názov:</label>
            <input type="text" id="nazov" name="nazov" value="<?= isset($edit_event) ? htmlspecialchars($edit_event['nazov']) : '' ?>" required>

            <label>Typ:</label>
            <div class="radio-group">
            <label><input type="radio" name="typ" value="oznam" checked> Oznam</label>
                <label><input type="radio" name="typ" value="udalost"> Udalosť</label>
                <label><input type="radio" name="typ" value="sport"> Športová udalosť</label>
                <label><input type="radio" name="typ" value="zmena"> Zmena</label> 
                <label><input type="radio" name="typ" value="kultura"> Kultúrne podujatie</label>
            </div>

            <label for="datum_od">Dátum od:</label>
            <input type="date" id="datum_od" name="datum_od" value="<?= isset($edit_event) ? $edit_event['datum_od'] : '' ?>" required>

            <label for="datum_do">Dátum do:</label>
            <input type="date" id="datum_do" name="datum_do" value="<?= isset($edit_event) ? $edit_event['datum_do'] : '' ?>" required>

            <label for="id_obec">Obec:</label>
            <select id="id_obec" name="id_obec" required>
                <option value="">-- Vyber obec --</option>
                <?php foreach ($obce as $obec): ?>
                    <option value="<?= $obec['id'] ?>" <?= isset($edit_event) && $edit_event['id_obec'] == $obec['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($obec['nazov']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="text">Popis:</label>
            <textarea id="text" name="text" rows="5" required><?= isset($edit_event) ? htmlspecialchars($edit_event['text']) : '' ?></textarea>

            <input type="submit" value="<?= isset($edit_event) ? 'Upraviť' : 'Odoslať' ?>">

            <?php if ($success): ?>
                <div class="msg success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="msg error"><?= $error ?></div>
            <?php endif; ?>
        </form>

        <!-- História udalostí -->
        <div class="history">
            <h3>História Udalostí</h3>

            <!-- Filtrovanie -->
            <input type="text" id="search-bar" class="search-bar" placeholder="Hľadať názov..." onkeyup="searchEvents()">
            <select id="event-type" class="search-filter" onchange="searchEvents()">
                <option value="">Všetky typy</option>
                <option value="oznam">Oznam</option>
                <option value="udalost">Udalosť</option>
                <option value="sport">Športová udalosť</option>
                <option value="kultura">Kultúrne podujatie</option>
                <option value="zmena">Zmena</option>
            </select>

            <div class="scrollable-events">
                <div class="existing-events" id="existing-events">
                    <?php foreach ($events as $event): ?>
                        <div class="event-box">
                            <div class="event-header">
                                <h3><?= htmlspecialchars($event['nazov']) ?></h3>
                                <div class="event-actions">
                                    <a href="?edit=<?= $event['id'] ?>">Upraviť</a>
                                    <a href="?delete=<?= $event['id'] ?>" onclick="return confirm('Skutočne chcete vymazať?')">Zmazať</a>
                                </div>
                            </div>
                            <p><strong>Typ:</strong> <?= htmlspecialchars($event['typ']) ?></p>
                            <p><strong>Popis:</strong> <?= nl2br(htmlspecialchars($event['text'])) ?></p>
                            <p><strong>Dátum:</strong> <?= date('d.m.Y', strtotime($event['datum_od'])) ?> - <?= date('d.m.Y', strtotime($event['datum_do'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="more-events">
                <button class="arrow-button" onclick="loadMoreEvents()">↓</button>
            </div>
        </div>

    </div>

    <script>
        let eventsToShow = 3;

        function loadMoreEvents() {
            eventsToShow += 3;
            const eventBoxes = document.querySelectorAll('.event-box');
            eventBoxes.forEach((box, index) => {
                if (index < eventsToShow) {
                    box.classList.remove('hidden-events');
                }
            });
        }

        function searchEvents() {
            const search = document.getElementById('search-bar').value;
            const eventType = document.getElementById('event-type').value;
            window.location.href = `?search=${search}&event_type=${eventType}`;
        }
    </script>

</body>
</html>
