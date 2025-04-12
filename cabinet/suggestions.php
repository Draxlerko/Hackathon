<?php
include 'db.php'; // Include the database connection file

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch data from the 'podnet' table
$sql = "SELECT p.id, p.nazov AS title, p.text AS description, p.datum AS created_at, p.stav_id, s.nazov_stavu 
        FROM podnet p
        JOIN stav s ON p.stav_id = s.id";
$result = $conn->query($sql);

// Debugging: Check if the query failed
if (!$result) {
    die("Error in SQL query: " . $conn->error);
}

// Fetch statuses from the 'stav' table
$status_sql = "SELECT id, nazov_stavu FROM stav";
$status_result = $conn->query($status_sql);

// Debugging: Check if the status query failed
if (!$status_result) {
    die("Error in SQL query for statuses: " . $conn->error);
}

// Store statuses in an array
$statuses = [];
while ($status_row = $status_result->fetch_assoc()) {
    $statuses[] = $status_row;
}

// Handle form submission to update the status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['podnet_id'], $_POST['stav_id'])) {
        $podnet_id = intval($_POST['podnet_id']);
        $stav_id = intval($_POST['stav_id']);

        // Update the status of the suggestion
        $update_sql = "UPDATE podnet SET stav_id = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $stav_id, $podnet_id);

        if ($stmt->execute()) {
            // Refresh the page to reflect the changes
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p>Chyba pri aktualizácii statusu: " . $conn->error . "</p>";
        }

        $stmt->close();
    }

    // Handle deletion of a suggestion
    if (isset($_POST['delete_podnet_id'])) {
        $delete_podnet_id = intval($_POST['delete_podnet_id']);

        // Delete the suggestion from the database
        $delete_sql = "DELETE FROM podnet WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $delete_podnet_id);

        if ($delete_stmt->execute()) {
            // Refresh the page to reflect the changes
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p>Chyba pri odstraňovaní podnetu: " . $conn->error . "</p>";
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
            background-color: #f9f9f9;
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
            background-color: #fff;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 8px 15px;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
            position: absolute;
            top: 10px; /* Adjusted to align with the white container */
            left: 10px; /* Adjusted to align with the white container */
            padding: 5px 10px; /* Made the button smaller */
            background-color: #3A59D1;
            color: white;
            text-decoration: none;
            font-size: 0.9rem; /* Reduced font size */
            font-weight: bold;
            border-radius: 4px; /* Slightly smaller border radius */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Reduced shadow size */
            transition: background-color 0.3s ease, transform 0.2s ease;
            z-index: 10; /* Ensure it stays above other elements */
        }

        .back-button:hover {
            background-color: #2f47aa;
            transform: translateY(-1px); /* Slightly reduced hover effect */
        }
    </style>
</head>
<body>
    <div class="container" style="position: relative;">
        <a href="cabinet_menu.php" class="back-button">← Späť</a>
        <div class="title">Podnety</div>
        <?php if ($result->num_rows > 0): ?>
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
                        <button type="submit" class="delete-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="16px" height="16px" style="vertical-align: middle; margin-right: 5px;">
                                <path d="M3 6h18v2H3V6zm2 3h14v12c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2V9zm3 2v8h2v-8H8zm4 0v8h2v-8h-2zm4 0v8h2v-8h-2zM9 4h6v2H9V4z"/>
                            </svg>
                            Odstrániť
                        </button>
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
