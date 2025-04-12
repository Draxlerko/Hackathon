<!-- filepath: c:\xampp\htdocs\hackathon\Hackathon\reservation.php -->
<?php
include 'cabinet/db.php'; // Pripojenie k databáze

$success = '';
$error = '';

// Načítanie obcí (úradov)
$query = "SELECT id, nazov FROM obec";
$result = $conn->query($query);
$obce = $result->fetch_all(MYSQLI_ASSOC);

// Načítanie dostupných časov pre vybraný úrad
$selected_obec = isset($_POST['obec']) ? intval($_POST['obec']) : null;
$date = isset($_POST['date']) ? $_POST['date'] : null;
$time = isset($_POST['time']) ? $_POST['time'] : null;
$dovod = isset($_POST['dovod']) ? trim($_POST['dovod']) : null;

// Spracovanie rezervácie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kontrola, či sú všetky potrebné údaje vyplnené
    if (!$selected_obec || !$date || !$time || !$dovod) {
        $error = "Vyplňte všetky polia formulára.";
    } else {
        // Skontrolujeme, či už existuje rezervácia pre daný čas
        $check_query = "SELECT dostupne FROM office_hours WHERE datum = ? AND cas = ? AND obec_id = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ssi", $date, $time, $selected_obec);
        $stmt->execute();
        $check_result = $stmt->get_result()->fetch_assoc();

        if ($check_result && !$check_result['dostupne']) {
            $error = "Vybraný čas už nie je dostupný.";
        } else {
            // Ak čas neexistuje, pridáme ho do tabuľky office_hours
            if (!$check_result) {
                $insert_hour_query = "INSERT INTO office_hours (datum, cas, dostupne, obec_id) VALUES (?, ?, TRUE, ?)";
                $stmt = $conn->prepare($insert_hour_query);
                $stmt->bind_param("ssi", $date, $time, $selected_obec);
                $stmt->execute();
                $office_hour_id = $stmt->insert_id;
            } else {
                // Ak čas existuje a je dostupný, získame jeho ID
                $office_hour_id_query = "SELECT id FROM office_hours WHERE datum = ? AND cas = ? AND obec_id = ?";
                $stmt = $conn->prepare($office_hour_id_query);
                $stmt->bind_param("ssi", $date, $time, $selected_obec);
                $stmt->execute();
                $office_hour_id = $stmt->get_result()->fetch_assoc()['id'];
            }

            // Vloženie rezervácie
            $insert_query = "INSERT INTO reservations (office_hour_id, dovod) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("is", $office_hour_id, $dovod);

            if ($stmt->execute()) {
                // Aktualizácia dostupnosti
                $update_query = "UPDATE office_hours SET dostupne = FALSE WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("i", $office_hour_id);
                $stmt->execute();

                $success = "Rezervácia bola úspešne vytvorená!";
            } else {
                $error = "Chyba pri vytváraní rezervácie.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervácia na úrad</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 90%;
            max-width: 500px;
        }

        h1 {
            font-size: 1.5rem;
            color: #3A59D1;
            text-align: center;
            margin-bottom: 20px;
        }

        .success {
            color: #2ecc71;
            text-align: center;
            margin-bottom: 10px;
        }

        .error {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
            color: #3A59D1;
            display: block;
            margin-top: 10px;
        }

        select, input[type="date"], input[type="time"], textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
            box-sizing: border-box;
        }

        button {
            background-color: #3A59D1;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2f47aa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rezervácia na úrad</h1>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php elseif ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="obec">Vyberte obec:</label>
            <select id="obec" name="obec" required>
                <option value="">-- Vyberte obec --</option>
                <?php foreach ($obce as $obec): ?>
                    <option value="<?= $obec['id'] ?>" <?= $selected_obec == $obec['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($obec['nazov']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="date">Dátum:</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($date) ?>" required>

            <label for="time">Čas:</label>
            <input type="time" id="time" name="time" required>

            <label for="dovod">Dôvod návštevy:</label>
            <textarea id="dovod" name="dovod" required></textarea>

            <button type="submit">Rezervovať</button>
        </form>
    </div>
</body>
</html>