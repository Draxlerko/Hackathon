<!-- filepath: c:\xampp\htdocs\hackathon\Hackathon\cabinet\reservationcheck.php -->
<?php
include 'db.php'; // Pripojenie k databáze

// Ak je odoslaný formulár na odstránenie rezervácie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = intval($_POST['reservation_id']);

    // Odstránenie rezervácie z databázy
    $delete_query = "DELETE FROM reservations WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        $success = "Rezervácia bola úspešne vybavená a odstránená.";
    } else {
        $error = "Chyba pri odstraňovaní rezervácie.";
    }
}

// Načítanie rezervácií z databázy, vrátane mena a priezviska používateľa
$query = "
    SELECT r.id, r.dovod, r.meno, r.priezvisko, oh.datum, oh.cas, o.nazov AS obec
    FROM reservations r
    JOIN office_hours oh ON r.office_hour_id = oh.id
    JOIN obec o ON oh.obec_id = o.id
    ORDER BY oh.datum ASC, oh.cas ASC
";
$result = $conn->query($query);
$reservations = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrola rezervácií</title>
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
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 20px;
            position: relative;
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

        .reservation-list {
            margin-top: 20px;
        }

        .reservation-item {
            background-color: #f5f5f5;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reservation-item h3 {
            margin: 0;
            color: #3A59D1;
        }

        .reservation-item p {
            margin: 5px 0;
            color: #555;
        }

        .reservation-item .date-time {
            font-weight: bold;
            color: #333;
        }

        .reservation-item form {
            margin: 0;
        }

        .reservation-item button {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .reservation-item button:hover {
            background-color: #c9302c;
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

        <h1>Kontrola rezervácií</h1>

        <?php if (isset($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php elseif (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if (count($reservations) > 0): ?>
            <div class="reservation-list">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="reservation-item">
                        <div>
                            <h3>Obec: <?= htmlspecialchars($reservation['obec']) ?></h3>
                            <p class="date-time">Dátum: <?= htmlspecialchars($reservation['datum']) ?>, Čas: <?= htmlspecialchars($reservation['cas']) ?></p>
                            <p><strong>Dôvod:</strong> <?= htmlspecialchars($reservation['dovod']) ?></p>
                            <p><strong>Registroval:</strong> <?= htmlspecialchars($reservation['meno']) ?> <?= htmlspecialchars($reservation['priezvisko']) ?></p>
                        </div>
                        <form method="post">
                            <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                            <button type="submit">Vybavené</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #555;">Žiadne rezervácie neboli nájdené.</p>
        <?php endif; ?>
    </div>
</body>
</html>
