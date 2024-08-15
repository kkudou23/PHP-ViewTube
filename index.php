<?php
    session_start();

    $_SESSION['phase-index'] = false;

    // select-planから帰ってきたエラーを格納する配列を用意
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
        // セッションに入っているエラーを配列にコピー
        $errors['name'] = isset($_SESSION['errors']['name']) ? $_SESSION['errors']['name'] : "";
        $errors['furigana'] = isset($_SESSION['errors']['furigana']) ? $_SESSION['errors']['furigana'] : "";
        $errors['gender'] = isset($_SESSION['errors']['gender']) ? $_SESSION['errors']['gender'] : "";
        $errors['birthday'] = isset($_SESSION['errors']['birthday']) ? $_SESSION['errors']['birthday'] : "";
        $errors['mail'] = isset($_SESSION['errors']['mail']) ? $_SESSION['errors']['mail'] : "";
        $errors['mailCheck'] = isset($_SESSION['errors']['mailCheck']) ? $_SESSION['errors']['mailCheck'] : "";
        $errors['genre'] = isset($_SESSION['errors']['genre']) ? $_SESSION['errors']['genre'] : "";
    }

    // ▼---------- セッションに入っているデータを変数にコピー ----------▼
        $name = isset($_SESSION['name']) ? $_SESSION['name'] : "";

        $furigana = isset($_SESSION['furigana']) ? $_SESSION['furigana'] : "";
        
        $gender = isset($_SESSION['gender']) ? $_SESSION['gender'] : "男";
        $genderCheck = [
            "男" => "",
            "女" => "",
        ];
        $genderCheck[$gender] = "checked";
        
        $birthday = isset($_SESSION['birthday']) ? $_SESSION['birthday'] : "";
        
        $mail = isset($_SESSION['mail']) ? $_SESSION['mail'] : "";
        
        $mailCheck = isset($_SESSION['mailCheck']) ? $_SESSION['mailCheck'] : "";
        
        $genre = isset($_SESSION['genre']) ? $_SESSION['genre'] : [];
        $genreCheck = [
            "洋画" => "",
            "邦画" => "",
            "アニメ" => "",
            "ドラマ" => "",
            "ドキュメンタリー" => "",
            "ホラー" => "",
            "バラエティ" => "",
        ];
        foreach($genre as $data) {
            $genreCheck[$data] = "checked";
        };
    // ▲----------------------------------------▲

    // select-planに進んだのでセッションにerror配列があるが、要素がない(エラーがない)時
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
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
</head>
<body>
    <h1 id="service-name">ViewTube Premium</h1>
    
    <main>
        <h3>ステップ 1/3</h3>
        <h1>個人情報を入力</h1>

        <form action="select-plan.php" method="POST">
            <div class="input-item">
                <label>
                    <p class="input-label">氏名<span class="required">必須</span></p>
                    <p class="error-message"><?php echo $errors['name'] ?></p>
                    <input type="text" name="name" value="<?php echo $name; ?>" placeholder="例 : 山田太郎" required>
                </label>
            </div>
            

            <div class="input-item">
                <label>
                    <p class="input-label">フリガナ<span class="required">必須</span></p>
                    <p class="error-message"><?php echo $errors['furigana'] ?></p>
                    <input type="text" name="furigana" value="<?php echo $furigana; ?>" placeholder="例 : ヤマダタロウ" required>
                </label>
            </div>

            <div class="input-item">
                <p class="input-label">性別</p>
                <p class="error-message"><?php echo $errors['gender'] ?></p>
                <div class="gender-radio-group">
                    <div class="gender-radio-option">
                        <input type="radio" name="gender" id="male" value="男" <?php echo $genderCheck['男']; ?>>
                        <label for="male">男</label>
                    </div>
                    <div class="gender-radio-option">
                        <input type="radio" name="gender" id="female" value="女" <?php echo $genderCheck['女']; ?>>
                        <label for="female">女</label>
                    </div>
                </div>
            </div>

            <div class="input-item">
                <label>
                    <p class="input-label">生年月日<span class="required">必須</span></p>
                    <p class="error-message"><?php echo $errors['birthday'] ?></p>
                    <input type="date" name="birthday" value="<?php echo $birthday; ?>" required>
                </label>
            </div>

            <div class="input-item">
                <label>
                    <p class="input-label">メールアドレス<span class="required">必須</span></p>
                    <p class="error-message"><?php echo $errors['mail'] ?></p>
                    <input type="text" name="mail" value="<?php echo $mail; ?>" placeholder="例 : YamadaTaro@sample.com" required>
                </label>
            </div>

            <div class="input-item">
                <label>
                    <p class="input-label">メールアドレス(確認)<span class="required">必須</span></p>
                    <p class="error-message"><?php echo $errors['mailCheck'] ?></p>
                    <input type="text" name="mailCheck" value="<?php echo $mailCheck; ?>" placeholder="メールアドレスを再度入力してください" required>
                </label>
            </div>

            <div class="input-item">
                <p class="input-label">興味のあるジャンル<span class="note">(一つ以上選択してください)</span></p>
                <p class="error-message"><?php echo $errors['genre'] ?></p>
                <div class="genre-checkbox-group">
                    <div class="genre-checkbox-option">
                        <label>
                            <input type="checkbox" name="genre[]" id="western" value="洋画" <?php echo $genreCheck["洋画"]; ?>>洋画
                        </label>
                    </div>
                    <div class="genre-checkbox-option">
                        <label>
                            <input type="checkbox" name="genre[]" id="japanese" value="邦画" <?php echo $genreCheck["邦画"]; ?>>邦画
                        </label>
                    </div>
                    <div class="genre-checkbox-option">
                        <label>
                            <input type="checkbox" name="genre[]" id="anime" value="アニメ" <?php echo $genreCheck["アニメ"]; ?>>アニメ
                        </label>
                    </div>
                    <div class="genre-checkbox-option">
                        <label> 
                            <input type="checkbox" name="genre[]" id="drama" value="ドラマ" <?php echo $genreCheck["ドラマ"]; ?>>ドラマ
                        </label>
                    </div>
                    <div class="genre-checkbox-option">
                        <label>
                            <input type="checkbox" name="genre[]" id="documentary" value="ドキュメンタリー" <?php echo $genreCheck["ドキュメンタリー"]; ?>>ドキュメンタリー
                        </label>
                    </div>
                    <div class="genre-checkbox-option">
                        <label>
                            <input type="checkbox" name="genre[]" id="horror" value="ホラー" <?php echo $genreCheck["ホラー"]; ?>>ホラー
                        </label>
                    </div>
                    <div class="genre-checkbox-option">
                        <label>
                            <input type="checkbox" name="genre[]" id="variety" value="バラエティ" <?php echo $genreCheck["バラエティ"]; ?>>バラエティ
                        </label>
                    </div>
                </div>
            </div>

            <input type="submit" value="プランの選択" class="button">
        </form>
    </main>

    <footer>
        <p>2024 - PHP夏季課題</p>
    </footer>
</body>
</html>