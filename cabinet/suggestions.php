<?php
include 'db.php'; // Include the database connection file

// Fetch data from the 'podnet' table
$sql = "SELECT id, nazov AS title, text AS description, datum AS created_at, stav_id FROM podnet";
$result = $conn->query($sql);

if (!$result) {
    die("Error in SQL query: " . $conn->error);
}

// Fetch statuses from the 'stav' table
$status_sql = "SELECT id, nazov_stavu FROM stav";
$status_result = $conn->query($status_sql);

if (!$status_result) {
    die("Error in SQL query: " . $conn->error);
}

// Store statuses in an array
$statuses = [];
while ($status_row = $status_result->fetch_assoc()) {
    $statuses[] = $status_row;
}

// Handle form submission to update the status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['podnet_id'], $_POST['stav_id'])) {
    $podnet_id = intval($_POST['podnet_id']);
    $stav_id = intval($_POST['stav_id']);

    $update_sql = "UPDATE podnet SET stav_id = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $stav_id, $podnet_id);

    if ($stmt->execute()) {
        echo "<p>Status bol úspešne aktualizovaný.</p>";
    } else {
        echo "<p>Chyba pri aktualizácii statusu: " . $conn->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podnety</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #3A59D1, #3D90D7);
            color: white;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            color: #333;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #3A59D1;
        }

        .suggestion {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .suggestion:last-child {
            border-bottom: none;
        }

        .suggestion-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #3A59D1;
        }

        .suggestion-description {
            margin: 5px 0;
        }

        .suggestion-date {
            font-size: 0.9rem;
            color: #666;
        }

        .status-form {
            margin-top: 10px;
        }

        .status-select {
            padding: 5px;
            font-size: 1rem;
        }

        .status-submit {
            padding: 5px 10px;
            font-size: 1rem;
            background-color: #3A59D1;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .status-submit:hover {
            background-color: #2f47aa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Podnety</div>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="suggestion">
                    <div class="suggestion-title"><?php echo htmlspecialchars($row['title']); ?></div>
                    <div class="suggestion-description"><?php echo htmlspecialchars($row['description']); ?></div>
                    <div class="suggestion-date">Pridané: <?php echo htmlspecialchars($row['created_at']); ?></div>
                    <form method="POST" class="status-form">
                        <input type="hidden" name="podnet_id" value="<?php echo $row['id']; ?>">
                        <select name="stav_id" class="status-select">
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?php echo $status['id']; ?>" <?php echo $status['id'] == $row['stav_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($status['nazov_stavu']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="status-submit">Uložiť</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Žiadne podnety na zobrazenie.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
