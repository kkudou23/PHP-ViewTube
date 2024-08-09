<?php
    session_start();

    $_SESSION['phase-index'] = false;

    $errors = [
        "name" => "",
        "furigana" => "",
        "gender" => "",
        "birthday" => "",
        "mail" => "",
        "mailCheck" => "",
        "genre" => "",
    ];

    if(isset($_SESSION['errors'])) {
        $errors['name'] = isset($_SESSION['errors']['name']) ? $_SESSION['errors']['name'] : "";
        $errors['furigana'] = isset($_SESSION['errors']['furigana']) ? $_SESSION['errors']['furigana'] : "";
        $errors['gender'] = isset($_SESSION['errors']['gender']) ? $_SESSION['errors']['gender'] : "";
        $errors['birthday'] = isset($_SESSION['errors']['birthday']) ? $_SESSION['errors']['birthday'] : "";
        $errors['mail'] = isset($_SESSION['errors']['mail']) ? $_SESSION['errors']['mail'] : "";
        $errors['mailCheck'] = isset($_SESSION['errors']['mailCheck']) ? $_SESSION['errors']['mailCheck'] : "";
        $errors['genre'] = isset($_SESSION['errors']['genre']) ? $_SESSION['errors']['genre'] : "";
    }

// ----------------------------------------
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : "";
// ----------------------------------------
    $furigana = isset($_SESSION['furigana']) ? $_SESSION['furigana'] : "";
// ----------------------------------------
    $gender = isset($_SESSION['gender']) ? $_SESSION['gender'] : "male";
    $genderCheck = [
        "male" => "",
        "female" => "",
    ];
    $genderCheck[$gender] = "checked";
// ----------------------------------------
    $birthday = isset($_SESSION['birthday']) ? $_SESSION['birthday'] : "";
// ----------------------------------------
    $mail = isset($_SESSION['mail']) ? $_SESSION['mail'] : "";
// ----------------------------------------
    $mailCheck = isset($_SESSION['mailCheck']) ? $_SESSION['mailCheck'] : "";
// ----------------------------------------
    $genre = isset($_SESSION['genre']) ? $_SESSION['genre'] : [];
    $genreCheck = [
        "western" => "",
        "japanese" => "",
        "anime" => "",
        "drama" => "",
        "documentary" => "",
        "horror" => "",
        "variety" => "",
    ];
    foreach($genre as $data) {
        $genreCheck[$data] = "checked";
    };
// ----------------------------------------

if (isset($_SESSION['errors']) && count($_SESSION['errors']) === 0) {
    $_SESSION['phase-index'] = true;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>利用者の個人情報入力画面</title>
    <style>
        p {
            color: red;
        }
    </style>
</head>
<body>
    <?php 
        echo "<pre><h1>セッション</h1>";
        var_dump($_SESSION);    
        echo "</pre>";

        echo "<pre><h1>ポスト</h1>";
        var_dump($_POST);    
        echo "</pre>";
    ?>

    <form action="select-plan.php" method="POST">
        <table border=1>
            <tr>
                <th>氏名</th>
                <td>
                    <input type="text" name="name" value="<?php echo $name; ?>" placeholder="氏名をここに入力" required>
                    <p><?php echo $errors['name'] ?></p>
                </td>
            </tr>
            <tr>
                <th>フリガナ</th>
                <td>
                    <input type="text" name="furigana" value="<?php echo $furigana; ?>" placeholder="フリガナをここに入力" required>
                    <p><?php echo $errors['furigana'] ?></p>
                </td>
            </tr>
            <tr>
                <th>性別</th>
                <td>
                    <label><input type="radio" name="gender" value="male" <?php echo $genderCheck['male']; ?>>男</label>
                    <label><input type="radio" name="gender" value="female" <?php echo $genderCheck['female']; ?>>女</label>
                    <p><?php echo $errors['gender'] ?></p>
                </td>
            </tr>
            <tr>
                <th>生年月日</th>
                <td>
                    <input type="date" name="birthday" value="<?php echo $birthday; ?>" required>
                    <p><?php echo $errors['birthday'] ?></p>
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td>
                    <input type="text" name="mail" value="<?php echo $mail; ?>" placeholder="メールアドレスをここに入力" required>
                    <p><?php echo $errors['mail'] ?></p>
                </td>
            </tr>
            <tr>
                <th>メールアドレス(確認)</th>
                <td>
                    <input type="text" name="mailCheck" value="<?php echo $mailCheck; ?>" placeholder="確認のためメールアドレスを再度入力" required>
                    <p><?php echo $errors['mailCheck'] ?></p>
                </td>
            </tr>
            <tr>
                <th>興味のあるジャンル</th>
                <td>
                    <label><input type="checkbox" name="genre[]" value="western" <?php echo $genreCheck["western"]; ?>>洋画</label>
                    <label><input type="checkbox" name="genre[]" value="japanese" <?php echo $genreCheck["japanese"]; ?>>邦画</label>
                    <label><input type="checkbox" name="genre[]" value="anime" <?php echo $genreCheck["anime"]; ?>>アニメ</label>
                    <label><input type="checkbox" name="genre[]" value="drama" <?php echo $genreCheck["drama"]; ?>>ドラマ</label>
                    <label><input type="checkbox" name="genre[]" value="documentary" <?php echo $genreCheck["documentary"]; ?>>ドキュメンタリー</label>
                    <label><input type="checkbox" name="genre[]" value="horror" <?php echo $genreCheck["horror"]; ?>>ホラー</label>
                    <label><input type="checkbox" name="genre[]" value="variety" <?php echo $genreCheck["variety"]; ?>>バラエティ</label>
                    <p><?php echo $errors['genre'] ?></p>
                </td>
            </tr>
        </table>
        <input type="submit">
    </form>
    <a href="registration-complete.php">セッションを削除する</a>
</body>
</html>