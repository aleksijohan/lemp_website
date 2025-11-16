
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
        p {
            font-size: 1.1rem;
            margin: 12px 0;
            opacity: 0.9;
        }
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
            $conn = new mysqli("localhost", "clock_user", "vahva_salasana", "clock_db");
            if ($conn->connect_error) {
                echo "Yhteys epäonnistui";
            } else {
                $result = $conn->query("SELECT NOW() AS nyt");
                $row = $result->fetch_assoc();
                echo "🕐 " . $row['nyt'];
                $conn->close();
            }
            ?>
        </div>

        <p>Kellonaika haetaan reaaliajassa tietokannasta.</p>
        <p style="margin: 25px 0; text-align: center;">
                <a href="/data-analysis/" style="background: #4a90e2; color: white; padding: 14px 28px; border-radius: 50px; text-decoration: none; font-weight: bold; font-size 1.1rem; display: inline-block;">
                        Avaa data analyysi sovellus </a>
        </p>
        <div class="footer">
            <p>Tehty GitHubilla • <a href="https://github.com/aleksijohan/lemp_website" style="color:#ffd700;">Näytä koodi</a></p>
            <p>©DoxeIt</p>
        </div>
    </div>
</body>
</html>
