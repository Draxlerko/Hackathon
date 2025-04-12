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
    $typ = $_POST['typ'] ?? null;
    $id_obcan = $_SESSION['user']['id'] ?? null;
    $obrazok = null;

    // Check if all required fields are filled
    if (empty($nazov) || empty($text) || empty($typ)) {
        die("Chyba: Všetky polia musia byť vyplnené.");
    }

    // Handle the uploaded image
    if (isset($_FILES['obrazok']) && $_FILES['obrazok']['error'] === UPLOAD_ERR_OK) {
        $obrazok = file_get_contents($_FILES['obrazok']['tmp_name']);
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "prvy_proof";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the record into the database
    $sql = "INSERT INTO podnet (id_obcan, nazov, obrazok, text, typ, datum) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $id_obcan, $nazov, $obrazok, $text, $typ);

    if ($stmt->execute()) {
        $success = true; // Set success flag
    } else {
        echo "Chyba pri registrácii podnetu: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulár na zadanie podnetu</title>
    <link rel="stylesheet" href="podnet_form.css">
</head>
<body>
    <div class="form-container">
        <h2>Zadaj podnet</h2>
        <form method="post" action="podnet_form.php" enctype="multipart/form-data">
            <label for="nazov">Názov podnetu:</label>
            <input type="text" id="nazov" name="nazov" required>
            
            <label for="obrazok">Pridať obrázok:</label>
            <input type="file" id="obrazok" name="obrazok" accept="image/*">
            
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
            <!-- Tlačidlo na návrat do usermenu -->
            <a href="user_menu.php" class="back-button">Späť do menu</a>
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
