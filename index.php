<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Aleksin LEMP-sivu</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 50px; background: #f4f4f9; }
        h1 { color: #2c3e50; }
        .clock { font-size: 28px; color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Tervetuloa Aleksin LEMP-sivulle!</h1>
    <p>Tämä sivu pyörii <strong>Nginx + PHP + MariaDB</strong> -stackillä cPoutassa.</p>

    <?php
    $conn = new mysqli("localhost", "clock_user", "vahva_salasana", "clock_db");
    if ($conn->connect_error) {
        die("Yhteys epäonnistui: " . $conn->connect_error);
    }
    $result = $conn->query("SELECT CURRENT_TIMESTAMP AS time");
    $row = $result->fetch_assoc();
    echo "<p class='clock'>Kellonaika tietokannasta: " . $row['time'] . "</p>";
    $conn->close();
    ?>
</body>
</html>
