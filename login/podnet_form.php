<?php
session_start();

// Zapnutie zobrazovania chýb
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ak nie je používateľ prihlásený, presmeruj ho na prihlasovací formulár
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$success = false; // Flag to track success

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nazov = $_POST['nazov'] ?? null;
    $text = $_POST['text'] ?? null;
    $typ = $_POST['typ'] ?? null; // Pridanie typu
    $id_obcan = $_SESSION['user']['id'] ?? null;

    if (empty($nazov) || empty($text) || empty($typ)) {
        die("Chyba: Všetky polia musia byť vyplnené.");
    }

    $servername = "localhost";
    $username = "root"; // nastav meno svojho používateľa databázy
    $password = ""; // nastav heslo pre svoju databázu
    $dbname = "prvy_proof"; // názov tvojej databázy

    // Vytvorenie pripojenia
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Skontroluj pripojenie
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Vloženie podnetu do databázy
    $sql = "INSERT INTO podnet (id_obcan, nazov, text, typ, datum) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_obcan, $nazov, $text, $typ);

    if ($stmt->execute()) {
        $success = true; // Set success flag
    } else {
        echo "Chyba pri registrácii podnetu: " . $stmt->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulár na zadanie podnetu</title>
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
        .form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            margin-bottom: 1rem;
            color: #3A59D1;
        }
        .form-container label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
            color: #555;
        }
        .form-container input[type="text"],
        .form-container textarea,
        .form-container select {
            width: calc(100% - 20px);
            padding: 0.8rem 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .form-container textarea {
            resize: none;
            height: 100px;
        }
        .form-container button {
            background: #3A59D1;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            width: 100%;
        }
        .form-container button:hover {
            background: #2f47aa;
            transform: translateY(-3px);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: #fff;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .modal-content h3 {
            color: #3A59D1;
            margin-bottom: 1rem;
        }
        .modal-content button {
            background: #3A59D1;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .modal-content button:hover {
            background: #2f47aa;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Zadaj podnet</h2>
        <form method="post" action="podnet_form.php">
            <label for="nazov">Názov podnetu:</label>
            <input type="text" id="nazov" name="nazov" required>
            
            <label for="text">Text podnetu:</label>
            <textarea id="text" name="text" required></textarea>
            
            <label for="typ">Typ podnetu:</label>
            <select id="typ" name="typ" required>
                <option value="" disabled selected>Vyberte typ podnetu</option>
                <option value="verejné osvetlenie">Verejné osvetlenie</option>
                <option value="infraštruktúra">Infraštruktúra</option>
                <option value="čistota">Čistota</option>
                <option value="sťažnosť">Sťažnosť</option>
                <option value="doprava">Doprava</option>
                <option value="bezpečnosť">Bezpečnosť</option>
                <option value="verejný priestor">Verejný priestor</option>
                <option value="dopravné značenie">Dopravné značenie</option>
                <option value="príroda">Príroda</option>
            </select>
            
            <button type="submit">Odoslať podnet</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal" id="successModal">
        <div class="modal-content">
            <h3>Podnet bol úspešne zaregistrovaný!</h3>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        // Show the modal if the form was successfully submitted
        <?php if ($success): ?>
        document.getElementById('successModal').style.display = 'flex';
        <?php endif; ?>

        // Close the modal
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }
    </script>
</body>
</html>
