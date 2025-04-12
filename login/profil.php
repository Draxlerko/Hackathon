<!-- filepath: c:\xampp\htdocs\hackathon\Hackathon\login\profil.php -->
<?php
session_start();
require_once '../cabinet/db.php'; // Súbor na pripojenie k databáze

// Skontroluj, či je používateľ prihlásený
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Získanie údajov používateľa zo session
$id_pouzivatela = $_SESSION['user']['id'];

// Načítanie údajov používateľa z databázy
$query = "SELECT meno, priezvisko, email, notifikacie, body FROM obcan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pouzivatela);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Používateľ neexistuje.");
}

// Spracovanie formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $notifikacie = isset($_POST['notifikacie']) ? 1 : 0;

    // Aktualizácia údajov v databáze
    $update_query = "UPDATE obcan SET email = ?, notifikacie = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sii", $email, $notifikacie, $id_pouzivatela);

    if ($update_stmt->execute()) {
        $success = "Údaje boli úspešne uložené!";
        // Aktualizácia údajov v $user
        $user['email'] = $email;
        $user['notifikacie'] = $notifikacie;
    } else {
        $error = "Chyba pri ukladaní údajov.";
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil používateľa</title>
    <link rel="stylesheet" href="reservation.css">
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="email"] {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            font-size: 1rem;
            border: 2px solid #3A59D1;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="email"]:focus {
            border-color: #3A59D1;
            box-shadow: 0 5px 15px rgba(58, 89, 209, 0.3);
            outline: none;
        }

        .buttons-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 10px;
        }

        .reserve-button {
            width: 150px;
            padding: 10px;
            background-color: #3A59D1;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .reserve-button:hover {
            background-color: #2C47A0;
            transform: translateY(-3px);
        }

        .back-button {
            display: inline-block;
            text-decoration: none;
            color: #3A59D1;
            font-weight: bold;
            border: 2px solid #3A59D1;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .back-button:hover {
            background-color: #3A59D1;
            color: white;
        }
    </style>
</head>
<body>
    <div class="reservation-container">
        <h1>Profil používateľa</h1>
        <p><strong>Meno:</strong> <?= htmlspecialchars($user['meno']) ?></p>
        <p><strong>Priezvisko:</strong> <?= htmlspecialchars($user['priezvisko']) ?></p>
        <p><strong>ID používateľa:</strong> <?= htmlspecialchars($id_pouzivatela) ?></p>
        <p><strong>Počet bodov za aktivitu:</strong> <?= htmlspecialchars($user['body']) ?></p>

        <?php if (!empty($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php elseif (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Zadajte váš email" required>

            <label for="notifikacie">
                <input type="checkbox" id="notifikacie" name="notifikacie" <?= $user['notifikacie'] ? 'checked' : '' ?>>
                Chcem dostávať notifikácie
            </label>

            <div class="buttons-container">
                <button type="submit" class="reserve-button">Uložiť</button>
                <a href="user_menu.php" class="back-button">Späť do menu</a>
            </div>
        </form>
    </div>
</body>
</html>