<?php 
    session_start();

    // ==========セッションを破棄する処理==========
    // (1) セッションの中身を空にする(空の配列で上書きする)
    $_SESSION = [];

    // (2) セッションのキー(クッキー)を消去する
    if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 1800); // 第三引数(セッションの有効期限)を過去にすると開いた瞬間に有効期限が切れて消える
    }

    // (3) セッションのファイル(サーバ上にあるファイル)を削除する
    session_destroy();

    //// (1)だけでもいいが、完全にセッションを消すためには(2)と(3)もやる必要がある
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了画面</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
</head>
<body id="complete">
    <div id="particles-js"></div>
    <h1 id="service-name">
        <a href="registration-complete.php">ViewTube Premium</a>
    </h1>

    <main>
        <div>
            <h1>ご登録ありがとうございます！<br>ViewTube Premiumを<br>お楽しみください！</h1>
        </div>
    </main>

    <footer>
        <p>2024 - PHP夏季課題</p>
    </footer>

    <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="script.js"></script>
</body>
</html>