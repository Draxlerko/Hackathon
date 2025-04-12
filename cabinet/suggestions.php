<?php
include 'db.php'; // Include the database connection file

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch suggestions from the 'podnet' table, including those with NULL 'stav_id'
$sql = "SELECT p.id, p.nazov AS title, p.text AS description, p.datum AS created_at, 
               p.stav_id, COALESCE(s.nazov_stavu, 'Nepriradený') AS nazov_stavu
        FROM podnet p
        LEFT JOIN stav s ON p.stav_id = s.id";
$result = $conn->query($sql);

// Debugging: Check if the query failed
if (!$result) {
    die("Error in SQL query: " . $conn->error);
}

// Fetch statuses from the 'stav' table
$status_sql = "SELECT id, nazov_stavu FROM stav";
$status_result = $conn->query($status_sql);

// Store statuses in an array
$statuses = [];
if ($status_result) {
    while ($status_row = $status_result->fetch_assoc()) {
        $statuses[] = $status_row;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update suggestion status
    if (isset($_POST['podnet_id'], $_POST['stav_id'])) {
        $podnet_id = intval($_POST['podnet_id']);
        $stav_id = intval($_POST['stav_id']);

        $update_sql = "UPDATE podnet SET stav_id = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $stav_id, $podnet_id);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p>Error updating status: " . $conn->error . "</p>";
        }
        $stmt->close();
    }

    // Delete suggestion
    if (isset($_POST['delete_podnet_id'])) {
        $delete_podnet_id = intval($_POST['delete_podnet_id']);

        $delete_sql = "DELETE FROM podnet WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $delete_podnet_id);

        if ($delete_stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p>Error deleting suggestion: " . $conn->error . "</p>";
        }
        $delete_stmt->close();
    }
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
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            color: #333;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .title {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #3A59D1;
            text-align: center;
        }

        .suggestion {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .suggestion-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #3A59D1;
            margin-bottom: 10px;
        }

        .suggestion-description {
            margin: 10px 0;
            font-size: 1rem;
            line-height: 1.5;
            color: #555;
        }

        .suggestion-date,
        .suggestion-status {
            font-size: 0.9rem;
            color: #777;
        }

        .status-form {
            margin-top: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-select {
            padding: 8px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .status-submit, .delete-submit {
            padding: 8px 15px;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .status-submit {
            background-color: #3A59D1;
            color: white;
        }

        .status-submit:hover {
            background-color: #2f47aa;
        }

        .delete-submit {
            background-color: #D13A3A;
            color: white;
        }

        .delete-submit:hover {
            background-color: #AA2F2F;
        }

        .no-suggestions {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            margin-top: 20px;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #3A59D1;
            color: white;
            text-decoration: none;
            font-size: 1rem;
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
        <a href="cabinet_menu.php" class="back-button">← Späť</a>
        <div class="title">Podnety</div>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="suggestion">
                    <div class="suggestion-title"><?php echo htmlspecialchars($row['title']); ?></div>
                    <div class="suggestion-description"><?php echo htmlspecialchars($row['description']); ?></div>
                    <div class="suggestion-date">Pridané: <?php echo htmlspecialchars($row['created_at']); ?></div>
                    <div class="suggestion-status">Status: <?php echo htmlspecialchars($row['nazov_stavu']); ?></div>
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
                    <form method="POST" class="status-form" style="display: inline;">
                        <input type="hidden" name="delete_podnet_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="delete-submit">Odstrániť</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-suggestions">Žiadne podnety na zobrazenie.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
