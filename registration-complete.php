<?php 
    session_start();

    // セッションの内容を全て破棄する
    $_SESSION = [];
    if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 1800);
    }
    session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了画面</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
</head>
<body id="complete-page">
    <div id="confetti"></div>
    <h1 id="service-name">ViewTube Premium</h1>

    <main>
        <div>
            <h1>
                <span>登録ありがとうございます！</span>
                <span>ViewTube Premiumを</span>
                <span>お楽しみください！</span>
            </h1>
        </div>
    </main>

    <footer>
        <p>2024 - PHP夏季課題</p>
    </footer>

    <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="confetti.js"></script>
</body>
</html>