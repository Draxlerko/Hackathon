<?php
require 'cabinet/db.php'; // Pripojenie k databáze

$success = '';
$error = '';

// Spracovanie formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $typ_osoby = $_POST['typ_osoby'] ?? '';
    $typ_recenzie = $_POST['typ_recenzie'] ?? '';
    $text_recenzie = trim($_POST['text_recenzie']);

    // Kontrola, či sú všetky polia vyplnené
    if (!empty($typ_osoby) && !empty($typ_recenzie) && !empty($text_recenzie)) {
        $insert_query = "INSERT INTO feedback (typ_osoby, typ_recenzie, text_recenzie) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sss", $typ_osoby, $typ_recenzie, $text_recenzie);

        if ($stmt->execute()) {
            $success = "✅ Vaša recenzia bola úspešne odoslaná!";
        } else {
            $error = "❌ Chyba pri ukladaní recenzie: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "❗ Vyplňte všetky polia.";
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pridať recenziu</title>
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
            padding: 20px;
        }

        .container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
        }

        h2 {
            color: #3A59D1;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #3A59D1;
        }

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
    </style>
</head>
<body>
    <div class="container">
        <h2>📝 Pridať recenziu</h2>
        <form method="post">
            <label for="typ_osoby">Ste:</label>
            <select id="typ_osoby" name="typ_osoby" required>
                <option value="">-- Vyberte možnosť --</option>
                <option value="dedincan">obcan</option>
                <option value="cudzinec">turista</option>
            </select>

            <label for="typ_recenzie">Typ recenzie:</label>
            <select id="typ_recenzie" name="typ_recenzie" required>
                <option value="">-- Vyberte možnosť --</option>
                <option value="pozitivna">Pozitívna</option>
                <option value="negativna">Negatívna</option>
            </select>

            <label for="text_recenzie">Vaša recenzia:</label>
            <textarea id="text_recenzie" name="text_recenzie" rows="5" style="width: calc(100% - 20px); padding: 10px; margin-top: 5px; border-radius: 8px; border: 1px solid #ccc; font-size: 1rem; resize: vertical;" required></textarea>

            <input type="submit" value="Odoslať recenziu">

            <?php if ($success): ?>
                <div class="msg success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="msg error"><?= $error ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>