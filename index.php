<?php
include 'header.php';
include 'navbar.php';

// Pripojenie k databáze
$conn = new mysqli("localhost", "root", "", "prvy_proof");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Načítanie udalostí z databázy
$sql = "SELECT * FROM news";
$result = $conn->query($sql);
?>

<style>

</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2>Nadchádzajúce udalosti v našej obci</h2>
            <div class="filter-buttons">
                <button class="filter-btn" data-category="vsetky">Všetky</button>
                <button class="filter-btn" data-category="oznam" style="background-color:#00bcd4;color:white;font-weight:600">Oznam</button>
                <button class="filter-btn" data-category="kultura" style="background-color:#e91e63;color:white;font-weight:600">Kultúra</button>
                <button class="filter-btn" data-category="sport" style="background-color:#FF7852;color:white;font-weight:600">Šport</button>
                <button class="filter-btn" data-category="zmena" style="background-color:#673ab7;color:white;font-weight:600">Zmena</button>
            </div>
            <div class="carousel-container">
                <button class="carousel-btn left-btn">&lt;</button>
                <div class="events-carousel">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $categoryClass = strtolower($row['typ']);
                            echo "
                            <div class='event-card $categoryClass'>
                                <h3>{$row['nazov']}</h3>
                                <p>{$row['text']}</p>
                                <p>{$row['datum_od']} - {$row['datum_do']}</p>
                                <span class='badge $categoryClass'>{$row['typ']}</span>
                            </div>
                            ";
                        }
                    } else {
                        echo "<p>Žiadne udalosti na zobrazenie.</p>";
                    }
                    ?>
                </div>
                <button class="carousel-btn right-btn">&gt;</button>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
?>