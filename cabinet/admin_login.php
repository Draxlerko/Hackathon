<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $dbname = "prvy_proof"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $meno = $_POST['meno'];
    $heslo = $_POST['heslo'];

    $sql = "SELECT * FROM admin WHERE meno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $meno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        if (hash('sha256', $heslo) === $admin['heslo']) {
            $_SESSION['admin'] = $admin; 
            header("Location: cabinet_menu.php"); 
            exit;
        } else {
            $error = "Nesprávne meno alebo heslo!";
        }
    } else {
        $error = "Nesprávne meno alebo heslo!";
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
    <title>Admin Prihlásenie</title>
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
            position: relative;
        }
        .login-container {
            background: #fff;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin-bottom: 1rem;
            color: #3A59D1;
        }
        .login-container label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
            color: #555;
        }
        .login-container input {
            width: calc(100% - 20px); 
            padding: 0.8rem 10px; 
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .login-container button {
            background: #3A59D1;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .login-container button:hover {
            background: #2f47aa;
            transform: translateY(-3px);
        }
        .error {
            color: red;
            font-weight: bold;
            margin-top: 1rem;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #1E3C72, #2A5298);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .back-button:hover {
            background: linear-gradient(135deg, #2A5298, #1E3C72);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <a href="../login/login.php" class="back-button">Späť</a>
    <div class="login-container">
        <h2>Admin Prihlásenie</h2>
        <form method="post" action="admin_login.php">
            <label for="meno">Meno:</label>
            <input type="text" id="meno" name="meno" required>
            <label for="heslo">Heslo:</label>
            <input type="password" id="heslo" name="heslo" required>
            <button type="submit">Prihlásiť sa</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>