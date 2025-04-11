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
    $nazov = trim($_POST['nazov']);
    $typ = $_POST['typ'];
    $text = trim($_POST['text']);
    $datum = $_POST['datum'];
    $id_obec = intval($_POST['id_obec']);

    if (!empty($nazov) && !empty($typ) && !empty($text) && !empty($datum) && $id_obec > 0) {
        $stmt = $conn->prepare("INSERT INTO news (typ, nazov, text, datum, id_obec) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $typ, $nazov, $text, $datum, $id_obec);

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
            justify-content: center;
            align-items: center;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
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
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .radio-group label {
            margin-right: 15px;
            color: #333;
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
    <form method="post">
        <h2>📝 Pridať oznam / udalosť</h2>

        <label for="nazov">Názov:</label>
        <input type="text" id="nazov" name="nazov" required>

        <label>Typ:</label>
        <div class="radio-group">
            <label><input type="radio" name="typ" value="oznam" checked> Oznam</label>
            <label><input type="radio" name="typ" value="udalosť"> Udalosť</label>
        </div>

        <label for="datum">Dátum udalosti:</label>
        <input type="date" id="datum" name="datum" required>

        <label for="id_obec">Obec:</label>
        <select id="id_obec" name="id_obec" required>
            <option value="">-- Vyber obec --</option>
            <?php foreach ($obce as $obec): ?>
                <option value="<?= $obec['id'] ?>"><?= htmlspecialchars($obec['nazov']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="text">Popis:</label>
        <textarea id="text" name="text" rows="5" required></textarea>

        <input type="submit" value="Odoslať">

        <?php if ($success): ?>
            <div class="msg success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="msg error"><?= $error ?></div>
        <?php endif; ?>
    </form>
</body>
</html>
