<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEMP-palvelin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        h1 {
            font-size: 2.8rem;
            margin-bottom: 15px;
            background: linear-gradient(to right, #ffd700, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }
        p { font-size: 1.1rem; margin: 12px 0; opacity: 0.9; }
        .clock {
            font-size: 2rem;
            font-weight: 500;
            margin: 25px 0;
            padding: 15px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: inline-block;
            min-width: 280px;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9rem;
            opacity: 0.7;
        }
        .badge {
            display: inline-block;
            background: #ff6b6b;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 5px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>LEMP-palvelin</h1>
        <p><strong>Tervetuloa CSC cPouta -pilveen!</strong></p>
        <p>Tämä sivu pyörii <span class="badge">Nginx</span> <span class="badge">PHP</span> <span class="badge">MariaDB</span> -stackillä.</p>

        <div class="clock">
            <?php
                date_default_timezone_set('Europe/Helsinki');
                $conn = new mysqli("localhost", "clock_user", "helppo123", "clock_db");
                if ($conn->connect_error) {
                    echo "Yhteys epäonnistui";
                } else {
                    $result = $conn->query("SELECT NOW() AS nyt");
                    if ($result) {
                        $row = $result->fetch_assoc();
                        echo " " . $row['nyt'];
                    } else {
                        echo "Kellon haku epäonnistui";
                    }
                    $conn->close();
                }
            ?>
        </div>

        <p>Kellonaika haetaan reaaliajassa tietokannasta.</p>

        <!-- ISO LIVE DASHBOARD -NAPPI -->
        <div style="text-align: center; margin: 60px 0;">
            <a href="/data-analysis/"
               style="background: linear-gradient(45deg, #00BFFF, #1E90FF);
                      color: white;
                      padding: 22px 60px;
                      border-radius: 60px;
                      text-decoration: none;
                      font-weight: bold;
                      font-size: 1.6rem;
                      display: inline-block;
                      box-shadow: 0 12px 40px rgba(0,0,0,0.4);
                      transition: all 0.4s;
                      border: 3px solid #00BFFF;">
                Live Dashboard<br>
                <small style="font-size: 0.9rem; opacity: 0.9;">Helsingin lämpötila + XRP/USD • Päivittyy 15 min välein</small>
            </a>
        </div>

        <div class="footer">
            <p>Tehty GitHubilla • <a href="https://github.com/aleksijohan/lemp_website" style="color:#ffd700;">Näytä koodi</a></p>
            <p>©DoxeIt</p>
        </div>
    </div>
</body>
</html>
