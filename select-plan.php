<?php
    session_start();

    $_SESSION['phase-select-plan'] = false;
    $jumpFrom = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
    
    if ($_SESSION['phase-index'] === false || strpos($jumpFrom, 'index.php') !== false) {
    // ====================ここからindexでの入力が正しいか====================
        $formError = false;
        $_SESSION['errors'] = [];

    // ----------------------------------------
        if(!empty($_POST['name'])) {
            $_SESSION['name'] = $_POST['name'];
        } else {
            $_SESSION['errors']['name'] = "名前が空です";
            $formError = true;
        }
    // ----------------------------------------
        if(!empty($_POST['furigana'])) {
            $_SESSION['furigana'] = mb_convert_kana($_POST['furigana'], "SCKV");
        } else {
            $_SESSION['errors']['furigana'] = "フリガナが空です";
            $formError = true;
        }
    // ----------------------------------------
        $genderValues = ["男", "女"];
        if(isset($_POST['gender']) && in_array($_POST['gender'], $genderValues, true)) {
            $_SESSION['gender'] = $_POST['gender'];
        } else {
            $_SESSION['errors']['gender'] = "性別の値が不正です";
            $formError = true;
        }
    // ----------------------------------------
        if(!empty($_POST['birthday'])) {
            if(birthdayFormat($_POST['birthday'])) {
                $birthday = new DateTime($_POST['birthday']);
                $today = new DateTime();
                $age = $today->diff($birthday)->y;
                if($age <= 12) {
                    $_SESSION['errors']['birthday'] = "12歳以下の方は登録できません";
                    $formError = true;
                } else {
                    $_SESSION['birthday'] = $_POST['birthday'];
                }
            } else {
                $_SESSION['errors']['birthday'] = "誕生日の値が不正です";
                $formError = true;
            }
        } else {
            $_SESSION['errors']['birthday'] = "誕生日が空です";
            $formError = true;
        }
    // ----------------------------------------
        if(!empty($_POST['mail'])) {
            if(mailFormat($_POST['mail'])) {
                $_SESSION['mail'] = $_POST['mail'];
            } else {
                $_SESSION['errors']['mail'] = "メールアドレスの値が不正です";
                $formError = true;
            }
        } else {
            $_SESSION['errors']['mail'] = "メールアドレスが空です";
            $formError = true;
        }
    // ----------------------------------------
        if(!empty($_POST['mailCheck'])) {
            if($_POST['mailCheck'] === $_POST['mail']) {
                $_SESSION['mailCheck'] = $_POST['mailCheck'];
            } else {
                $_SESSION['errors']['mailCheck'] = "メールアドレスとメールアドレス(確認)が一致しません";
                $formError = true;
            }
            if(mailFormat($_POST['mailCheck'])) {
                $_SESSION['mailCheck'] = $_POST['mailCheck'];
            } else {
                $_SESSION['errors']['mailCheck'] = "メールアドレス(確認)の値が不正です";
                $formError = true;
            }
        } else {
            $_SESSION['errors']['mailCheck'] = "メールアドレス(確認)が空です";
            $formError = true;
        }
    // ----------------------------------------
        if(isset($_POST['genre'])) {
            $genreValues = ["洋画", "邦画", "アニメ", "ドラマ", "ドキュメンタリー", "ホラー", "バラエティ"];
            $genreError = false;
            
            foreach($_POST['genre'] as $data) {
                if(!in_array($data, $genreValues, true)) {
                    $genreError = true;
                }
            }

            if(!$genreError) {
                $_SESSION['genre'] = $_POST['genre'];
            } else {
                $_SESSION['errors']['genre'] = "興味のあるジャンルの値が不正です";
                $formError = true;
            }
        } else {
            $_SESSION['errors']['genre'] = "一つ以上選択してください";
            $formError = true;
        }
    // ----------------------------------------
        if($formError) {
            header('Location: index.php');
        }
    // } elseif(strpos($jumpFrom, 'confirm.php') !== false) {
    // } else {
        // header('Location: index.php');
    }
    // ----------------------------------------

        function h($str) {
            return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        };

        function birthdayFormat($date){
            if(!preg_match('/^(19|20)[0-9]{2}\-\d{2}\-\d{2}$/', $date)){
                return false;
            }
            list($y, $m, $d) = explode('-', $date);
            if(!checkdate($m, $d, $y)){
                return false;
            }
            return true;
        }

        function mailFormat($text) {
            if(preg_match("/^[A-Za-z0-9.\-_+@]+$/", $text) === 1) {
                return true;
            } else {
                return false;
            }
        }

    // ====================ここからプラン関係====================
        $errors2 = [
            "plan" => "",
            "option" => "",
            "deviceNum" => "",
            "coupon" => "",
        ];

        if(isset($_SESSION['errors2'])) {
            $errors2['plan'] = isset($_SESSION['errors2']['plan']) ? $_SESSION['errors2']['plan'] : "";
            $errors2['option'] = isset($_SESSION['errors2']['option']) ? $_SESSION['errors2']['option'] : "";
            $errors2['deviceNum'] = isset($_SESSION['errors2']['deviceNum']) ? $_SESSION['errors2']['deviceNum'] : "";
            $errors2['coupon'] = isset($_SESSION['errors2']['coupon']) ? $_SESSION['errors2']['coupon'] : "";
        }
    // ----------------------------------------
        $plan = isset($_SESSION['plan']) ? $_SESSION['plan'] : "ブロンズ";
        $planCheck = [
            "ブロンズ" => "",
            "シルバー" => "",
            "ゴールド" => "",
        ];
        $planCheck[$plan] = "checked";
    // ----------------------------------------
        $option = isset($_SESSION['option']) ? $_SESSION['option'] : [];
        $optionCheck = [
            "4K画質対応" => "",
            "複数デバイス視聴" => "",
            "ペアレンタルコントロール" => "",
        ];
        foreach($option as $data) {
            $optionCheck[$data] = "checked";
        };
    // ----------------------------------------
        $deviceNum = isset($_SESSION['deviceNum']) ? $_SESSION['deviceNum'] : "";
        $deviceNumCheck = [
            "2" => "",
            "3" => "",
            "4" => "",
        ];
        $deviceNumCheck[$deviceNum] = "selected";
    // ----------------------------------------
        $coupon = isset($_SESSION['coupon']) ? $_SESSION['coupon'] : "";
    // ----------------------------------------
    if (isset($_SESSION['errors2']) && count($_SESSION['errors2']) === 0) {
        $_SESSION['phase-select-plan'] = true;
    }

    // if (isset($_SESSION['errors']) && count($_SESSION['errors']) !== 0) {
    //     header('Location: index.php');
    // }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プラン・オプション選択画面</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
</head>
<body>
    <h1 id="service-name">ViewTube Premium</h1>

    <main>
        <h3>ステップ 2/3</h3>
        <h1>プラン・オプションを選択</h1>

        <form action="confirm.php" method="POST">
            <div class="input-item">
                <p class="input-label">基本プランの選択</p>
                <div class="radio-group-plan">
                    <div class="radio-option-plan">
                        <input type="radio" name="plan" id="bronze" value="ブロンズ" <?php echo $planCheck['ブロンズ']; ?>>
                        <label for="bronze" id="label-bronze">ブロンズ<br><span class="plan-about">(基本プラン 500円/月)</span></label>
                    </div>
                    <div class="radio-option-plan">
                        <input type="radio" name="plan" id="silver" value="シルバー" <?php echo $planCheck['シルバー']; ?>>
                        <label for="silver" id="label-silver">シルバー<br><span class="plan-about">(基本に加えさらに高画質 800円/月)</span></label>
                    </div>
                    <div class="radio-option-plan">
                        <input type="radio" name="plan" id="gold" value="ゴールド" <?php echo $planCheck['ゴールド']; ?>>
                        <label for="gold" id="label-gold">ゴールド<br><span class="plan-about">(高画質でさらに最新作をお届け 1000円/月)</span></label>
                    </div>
                </div>
                <p class="error-message"><?php echo $errors2['plan'] ?></p>
            </div>

            <div class="input-item">
                <p class="input-label">オプションの選択</p>
                <div class="checkbox-group-option">
                    <div class="checkbox-option-option">
                        <label>
                            <input type="checkbox" name="option[]" value="4K画質対応" <?php echo $optionCheck["4K画質対応"]; ?>>4K画質対応<span class="note">(+600円)</span>
                        </label>
                    </div>
                    <div class="checkbox-option-option">
                        <label>
                            <input type="checkbox" name="option[]" value="複数デバイス視聴" <?php echo $optionCheck["複数デバイス視聴"]; ?> id="multiDevice">複数デバイス視聴<span class="note">(一台追加につき+200円)</span>
                            <select name="deviceNum" id="deviceNum">
                                <option value="2" <?php echo $deviceNumCheck["2"]; ?>>2台(+200円)</option>
                                <option value="3" <?php echo $deviceNumCheck["3"]; ?>>3台(+400円)</option>
                                <option value="4" <?php echo $deviceNumCheck["4"]; ?>>4台(+600円)</option>
                            </select>
                        </label>
                        <p><?php echo $errors2['deviceNum'] ?></p>
                    </div>
                    <div class="checkbox-option-option">
                        <label>
                            <input type="checkbox" name="option[]" value="ペアレンタルコントロール" <?php echo $optionCheck["ペアレンタルコントロール"]; ?>>ペアレンタルコントロール<span class="note">(無料)</span>
                        </label>
                    </div>
                </div>
                <p class="error-message"><?php echo $errors2['option'] ?></p>
            </div>

            <div class="input-item">
                <label>
                    <p class="input-label">クーポンコードの利用<br><span class="note">クーポンコードをお持ちの場合は入力してください</span></p>
                    <input type="text" name="coupon" value="<?php echo $coupon; ?>" placeholder="例 : A1B2-3C4D-EF56-78GH">
                    <p class="error-message"><?php echo $errors2['coupon'] ?></p>
                </label>
            </div>

            <div class="button-container">
                <input type="submit" value="登録内容の確認" class="button"></input>
                <a href="index.php" class="button gray-button">個人情報の修正
                    <?php 
                        if($_SESSION['phase-select-plan'] === false) {
                            echo "<br><span class='note'>※このページで入力した情報はリセットされます</span>";
                        }
                    ?>    
                </a>
            </div>
        </form>
    </main>
    <footer>
        <p>2024 - PHP夏季課題</p>
    </footer>
    
    <script>
        const multiDevice = document.getElementById('multiDevice');
        const deviceNum = document.getElementById('deviceNum');

        function isMultiDeviceChecked() {
            if (multiDevice.checked) {
                deviceNum.style.display = 'block';
            } else {
                deviceNum.style.display = 'none';
            }
        }

        window.onload = isMultiDeviceChecked;
        multiDevice.addEventListener('change', isMultiDeviceChecked);
    </script>
</body>
</html>