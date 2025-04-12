<?php
require 'db.php'; // Pripojenie k databáze

$recenzie = [];
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'pozitivna'; // Predvolený filter je "pozitivna"

// Odstránenie recenzie, ak bolo kliknuté na "Prečítané"
if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $delete_query = "DELETE FROM feedback WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: feedback_admin.php?filter=$filter");
    exit;
}

// Načítanie recenzií podľa filtra
$sql = "SELECT id, typ_osoby, text_recenzie, datum FROM feedback WHERE typ_recenzie = ? ORDER BY datum DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $filter);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recenzie[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prehľad recenzií</title>
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
            flex-direction: column;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            color: #3A59D1;
            margin-bottom: 20px;
            text-align: center;
        }

        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .filter-button {
            background-color: #f5f5f5;
            color: #3A59D1;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .filter-button:hover {
            background-color: #3A59D1;
            color: white;
            transform: translateY(-3px);
        }

        .filter-button.active {
            background-color: #3A59D1;
            color: white;
        }

        .review-box {
            background-color: white;
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #3A59D1;
        }

        .review-header h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        .review-body {
            margin-top: 10px;
            color: #333;
        }

        .review-date {
            font-size: 0.9rem;
            color: #888;
            margin-top: 10px;
        }

        .delete-button {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color:rgb(0, 0, 0);
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 0.9rem;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .delete-button:hover {
            background-color:rgb(65, 255, 2);
            transform: translateY(-3px);
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
    <div class="container">
        <h2>Prehľad recenzií</h2>

        <!-- Filter tlačidlá -->
        <div class="filter-buttons">
            <a href="?filter=pozitivna" class="filter-button <?= $filter === 'pozitivna' ? 'active' : '' ?>">Pozitívne</a>
            <a href="?filter=negativna" class="filter-button <?= $filter === 'negativna' ? 'active' : '' ?>">Negatívne</a>
        </div>

        <!-- Zobrazenie recenzií -->
        <?php if (!empty($recenzie)): ?>
            <?php foreach ($recenzie as $recenzia): ?>
                <div class="review-box">
                    <div class="review-header">
                        <h3><?= htmlspecialchars(ucfirst($recenzia['typ_osoby'])) ?></h3>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="delete_id" value="<?= $recenzia['id'] ?>">
                            <button type="submit" class="delete-button">Oznacit ako prečítané</button>
                        </form>
                    </div>
                    <div class="review-body">
                        <?= nl2br(htmlspecialchars($recenzia['text_recenzie'])) ?>
                    </div>
                    <div class="review-date">
                        <?= date('d.m.Y H:i', strtotime($recenzia['datum'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: #888;">Žiadne recenzie na zobrazenie.</p>
        <?php endif; ?>

        <!-- Tlačidlo na návrat -->
        <a href="cabinet_menu.php" class="back-button">← Späť do menu</a>
    </div>
</body>
</html>