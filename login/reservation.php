<!-- filepath: c:\xampp\htdocs\hackathon\Hackathon\reservation.php -->
<?php
session_start();
include '../cabinet/db.php'; // Opravená cesta k pripojeniu databázy

$success = '';
$error = '';

// Skontroluj, či je používateľ prihlásený
if (!isset($_SESSION['user'])) {
    header("Location: ../login/login.php");
    exit();
}

// Získanie mena a priezviska používateľa zo session
$meno = $_SESSION['user']['meno'];
$priezvisko = $_SESSION['user']['priezvisko'];
$id_pouzivatela = $_SESSION['user']['id'];

// Načítanie obcí (úradov)
$query = "SELECT id, nazov FROM obec";
$result = $conn->query($query);

if ($result) {
    $obce = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "Chyba pri načítaní obcí: " . $conn->error;
}

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

            // Vloženie rezervácie s menom, priezviskom a ID používateľa
            $insert_query = "INSERT INTO reservations (office_hour_id, dovod, meno, priezvisko, id_pouzivatela) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("isssi", $office_hour_id, $dovod, $meno, $priezvisko, $id_pouzivatela);

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
    <link rel="stylesheet" href="reservation.css">
</head>
<body>
    <div class="reservation-container">
        <h1>Rezervácia na úrad</h1>
        <p><strong>Prihlásený používateľ:</strong> <?= htmlspecialchars($meno) ?> <?= htmlspecialchars($priezvisko) ?></p>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php elseif ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="obec">Vyberte obec:</label>
            <select id="obec" name="obec" required>
                <option value="">-- Vyberte obec --</option>
                <?php if (!empty($obce)): ?>
                    <?php foreach ($obce as $obec): ?>
                        <option value="<?= $obec['id'] ?>" <?= $selected_obec == $obec['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($obec['nazov']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <label for="date">Dátum:</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($date) ?>" required>

            <label for="time">Čas:</label>
            <input type="time" id="time" name="time" required>

            <label for="dovod">Dôvod návštevy:</label>
            <textarea id="dovod" name="dovod" required></textarea>

            <button type="submit" class="reserve-button">Rezervovať</button>
        </form>
        <!-- Tlačidlo na návrat do usermenu -->
        <a href="user_menu.php" class="back-button">Späť do menu</a>
    </div>
</body>
</html>