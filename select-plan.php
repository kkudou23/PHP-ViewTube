<?php
    session_start();

    $_SESSION['phase-select-plan'] = false;
    $jumpFrom = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
    
    if(!isset($_SESSION['phase-index'])) {
        header('Location: index.php');
    }

    // select-planに進んだのでセッションにerror配列があるが、要素がない(エラーがない)時
    if (isset($_SESSION['errors']) && count($_SESSION['errors']) === 0) {
        $_SESSION['phase-index'] = true;
    }
    
    // ▼==================== indexで入力された情報のチェック ====================▼
    if ($_SESSION['phase-index'] === false || strpos($jumpFrom, 'index.php') !== false) { // indexで入力された情報のチェックが終了していない、またはindexからこのページに飛んできた場合
        $formError = false; // どこかの値にエラーがあった場合trueになる変数
        $_SESSION['errors'] = []; // セッションにerror配列を用意

        // ---------- 名前チェック ----------
        if(!empty($_POST['name'])) {
            $_SESSION['name'] = $_POST['name'];
        } else {
            $_SESSION['errors']['name'] = "名前が空です";
            $formError = true;
        }

        // ---------- フリガナチェック ----------
        if(!empty($_POST['furigana'])) {
            $_SESSION['furigana'] = mb_convert_kana($_POST['furigana'], "SCKV"); // 半角スペースを全角スペースに、全角ひらがな・半角カタカナを全角カタカナに変換
        } else {
            $_SESSION['errors']['furigana'] = "フリガナが空です";
            $formError = true;
        }

        // ---------- 性別チェック ----------
        $genderValues = ["男", "女"]; // 規定の値一覧の配列
        if(isset($_POST['gender']) && in_array($_POST['gender'], $genderValues, true)) { // 性別の値が送信されていて、かつ規定の値から選ばれている(配列の中にある)か
            $_SESSION['gender'] = $_POST['gender'];
        } else {
            $_SESSION['errors']['gender'] = "性別の値が不正です";
            $formError = true;
        }

        // ---------- 生年月日チェック ----------
        if(!empty($_POST['birthday'])) {
            if(birthdayFormat($_POST['birthday'])) {
                $birthday = new DateTime($_POST['birthday']);
                $today = new DateTime();
                $age = $today->diff($birthday)->y; // 入力された生年月日と今日の日付を比較して年(年齢)をageに代入
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

        // ---------- メールアドレスチェック ----------
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

        // ---------- メールアドレス(確認)チェック ----------
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

        // ---------- ジャンルチェック ----------
        if(isset($_POST['genre'])) {
            $genreValues = ["洋画", "邦画", "アニメ", "ドラマ", "ドキュメンタリー", "ホラー", "バラエティ"]; // 規定の値一覧の配列
            $genreError = false;
            
            foreach($_POST['genre'] as $data) {
                if(!in_array($data, $genreValues, true)) { // 規定の値から選ばれていない(配列の中にない)場合
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
        if($formError) { // どこかの値にエラーがあった場合indexに戻る
            header('Location: index.php');
        }
    }
    // ▲========================================▲

    // ▼==================== select-planで表示する情報・エラーの処理 ====================▼
        // confirmから帰ってきたエラーを格納する配列を用意
        $errors2 = [
            "plan" => "",
            "option" => "",
            "deviceNum" => "",
            "coupon" => "",
        ];

        if(isset($_SESSION['errors2'])) {
            // セッションに入っているエラーを配列にコピー
            $errors2['plan'] = isset($_SESSION['errors2']['plan']) ? $_SESSION['errors2']['plan'] : "";
            $errors2['option'] = isset($_SESSION['errors2']['option']) ? $_SESSION['errors2']['option'] : "";
            $errors2['deviceNum'] = isset($_SESSION['errors2']['deviceNum']) ? $_SESSION['errors2']['deviceNum'] : "";
            $errors2['coupon'] = isset($_SESSION['errors2']['coupon']) ? $_SESSION['errors2']['coupon'] : "";
        }

        // ▼---------- セッションに入っているデータを変数にコピー ----------▼
            $plan = isset($_SESSION['plan']) ? $_SESSION['plan'] : "ブロンズ";
            $planCheck = [
                "ブロンズ" => "",
                "シルバー" => "",
                "ゴールド" => "",
            ];
            $planCheck[$plan] = "checked";
            
            $option = isset($_SESSION['option']) ? $_SESSION['option'] : [];
            $optionCheck = [
                "4K画質対応" => "",
                "複数デバイス視聴" => "",
                "ペアレンタルコントロール" => "",
            ];
            foreach($option as $data) {
                $optionCheck[$data] = "checked";
            };
            
            $deviceNum = isset($_SESSION['deviceNum']) ? $_SESSION['deviceNum'] : "";
            $deviceNumCheck = [
                "2" => "",
                "3" => "",
                "4" => "",
            ];
            $deviceNumCheck[$deviceNum] = "selected";
            
            $coupon = isset($_SESSION['coupon']) ? $_SESSION['coupon'] : "";
        // ▲----------------------------------------▲

        // confirmに進んだのでセッションにerror2配列があるが、要素がない(エラーがない)時
            if (isset($_SESSION['errors2']) && count($_SESSION['errors2']) === 0) {
            $_SESSION['phase-select-plan'] = true;
        }
    // ▲========================================▲

    // ▼==================== indexで入力された情報のチェックに使う関数 ====================▼
    function birthdayFormat($date){
        if(!preg_match('/^(19|20)\d{2}\-\d{2}\-\d{2}$/', $date)){ // 「数字4桁(19XXか20XX)-数字2桁-数字2桁」の形式になってるか
            return false;
        }
        list($y, $m, $d) = explode('-', $date); // ハイフンで年月日に分割
        if(!checkdate($m, $d, $y)){ // 存在する日付かチェック
            return false;
        }
        return true;
    }
    
    function mailFormat($text) {
        if(preg_match("/^[A-Za-z0-9.\-_+@]+$/", $text) === 1) { // 半角英数(と一部の記号)のみで構成されているか
            return true;
        } else {
            return false;
        }
    }
    // ▲========================================▲
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プラン・オプション選択画面</title>
    <link rel="stylesheet" href="style.css">
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
                <div class="plan-radio-group">
                    <div class="plan-radio-option">
                        <input type="radio" name="plan" id="bronze" value="ブロンズ" <?php echo $planCheck['ブロンズ']; ?>>
                        <label for="bronze" id="label-bronze">ブロンズ<br><span class="plan-about">(基本プラン 500円/月)</span></label>
                    </div>
                    <div class="plan-radio-option">
                        <input type="radio" name="plan" id="silver" value="シルバー" <?php echo $planCheck['シルバー']; ?>>
                        <label for="silver" id="label-silver">シルバー<br><span class="plan-about">(基本に加えさらに高画質 800円/月)</span></label>
                    </div>
                    <div class="plan-radio-option">
                        <input type="radio" name="plan" id="gold" value="ゴールド" <?php echo $planCheck['ゴールド']; ?>>
                        <label for="gold" id="label-gold">ゴールド<br><span class="plan-about">(高画質でさらに最新作をお届け 1000円/月)</span></label>
                    </div>
                </div>
                <p class="error-message"><?php echo $errors2['plan'] ?></p>
            </div>

            <div class="input-item">
                <p class="input-label">オプションの選択</p>
                <div class="option-checkbox-group">
                    <div class="option-checkbox-option">
                        <label>
                            <input type="checkbox" name="option[]" value="4K画質対応" <?php echo $optionCheck["4K画質対応"]; ?>>4K画質対応<span class="note">(+600円)</span>
                        </label>
                    </div>
                    <div class="option-checkbox-option">
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
                    <div class="option-checkbox-option">
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
        // 「複数デバイス視聴」のオプションが選択されているときのみデバイス数を選択するプルダウンメニューを表示する
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