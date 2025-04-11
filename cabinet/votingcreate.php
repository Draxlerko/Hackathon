<!-- filepath: c:\xampp\htdocs\hackathon\Hackathon\cabinet\votingcreate.php -->
<?php
include 'db.php';

$success = '';
$error = '';

// Spracovanie formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $nazov = trim($_POST['nazov']);
    $info = trim($_POST['info']);
    $popis = trim($_POST['popis']);
    $moznost1 = trim($_POST['moznost1']);
    $moznost2 = trim($_POST['moznost2']);
    $moznost3 = trim($_POST['moznost3']);
    $moznost4 = trim($_POST['moznost4']);
    $datum_od = $_POST['datum_od'];
    $datum_do = $_POST['datum_do'];

    if (!empty($nazov) && !empty($moznost1) && !empty($moznost2) && !empty($datum_od) && !empty($datum_do)) {
        $stmt = $conn->prepare("INSERT INTO hlasovanie (nazov, info, popis, moznost1, moznost2, moznost3, moznost4, datum_od, datum_do) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nazov, $info, $popis, $moznost1, $moznost2, $moznost3, $moznost4, $datum_od, $datum_do);

        if ($stmt->execute()) {
            $success = "✅ Ankéta bola úspešne vytvorená!";
        } else {
            $error = "❌ Chyba pri vytváraní ankety: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "❗ Vyplňte všetky povinné polia.";
    }
}

// Spracovanie mazania ankety
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM hlasovanie WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = "✅ Ankéta bola úspešne vymazaná!";
    } else {
        $error = "❌ Chyba pri mazaní ankety: " . $stmt->error;
    }
    $stmt->close();
}

// Načítanie existujúcich ankiet
$ankety = [];
$result = $conn->query("SELECT * FROM hlasovanie ORDER BY datum_od DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ankety[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vytvoriť anketu</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #3A59D1, #3D90D7);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        .form-section {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .form-left,
        .form-right {
            width: 48%;
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

        input[type="text"],
        input[type="date"],
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

        .ankety-list {
            margin-top: 30px;
        }

        .anketa {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .anketa h3 {
            margin: 0;
            color: #3A59D1;
        }

        .anketa p {
            margin: 5px 0;
        }

        .delete-button {
            background-color: #D9534F;
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .delete-button:hover {
            background-color: #C9302C;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Vytvoriť anketu</h2>
        <form method="post">
            <div class="form-section">
                <div class="form-left">
                    <label for="nazov">Názov ankety:</label>
                    <input type="text" id="nazov" name="nazov" required>

                    <label for="info">Informácie:</label>
                    <textarea id="info" name="info" rows="3"></textarea>

                    <label for="popis">Popis:</label>
                    <textarea id="popis" name="popis" rows="5"></textarea>
                </div>
                <div class="form-right">
                    <label for="moznost1">Možnosť A:</label>
                    <input type="text" id="moznost1" name="moznost1" required>

                    <label for="moznost2">Možnosť B:</label>
                    <input type="text" id="moznost2" name="moznost2" required>

                    <label for="moznost3">Možnosť C:</label>
                    <input type="text" id="moznost3" name="moznost3">

                    <label for="moznost4">Možnosť D:</label>
                    <input type="text" id="moznost4" name="moznost4">

                    <label for="datum_od">Dátum od:</label>
                    <input type="date" id="datum_od" name="datum_od" required>

                    <label for="datum_do">Dátum do:</label>
                    <input type="date" id="datum_do" name="datum_do" required>
                </div>
            </div>
            <input type="submit" name="create" value="Vytvoriť anketu">
        </form>

        <?php if ($success): ?>
            <div class="msg success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="msg error"><?= $error ?></div>
        <?php endif; ?>

        <div class="ankety-list">
            <h2>Existujúce ankety</h2>
            <?php foreach ($ankety as $anketa): ?>
                <div class="anketa">
                    <h3><?= htmlspecialchars($anketa['nazov']) ?></h3>
                    <p><strong>Popis:</strong> <?= htmlspecialchars($anketa['popis']) ?></p>
                    <p><strong>Dátum:</strong> <?= htmlspecialchars($anketa['datum_od']) ?> - <?= htmlspecialchars($anketa['datum_do']) ?></p>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $anketa['id'] ?>">
                        <button type="submit" name="delete" class="delete-button">Vymazať</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>