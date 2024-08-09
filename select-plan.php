<!-- select-plan.php -->
<?php
    session_start();

    if(!isset($_SESSION['errors'])) {
        header('Location: index.php');
    }

    $_SESSION['phase-select-plan'] = false;
    $jumpFrom = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
    
if (strpos($jumpFrom, 'index.php') !== false) {
// ====================ここからindexでの入力が正しいか====================
    $formError = false;
    $_SESSION['errors'] = [];

// ----------------------------------------
    if(!empty($_POST['name'])) {
        $_SESSION['name'] = $_POST['name'];
    } else {
        $_SESSION['errors']['name'] = "名前が空でした";
        $formError = true;
    }
// ----------------------------------------
    if(!empty($_POST['furigana'])) {
        $_SESSION['furigana'] = mb_convert_kana($_POST['furigana'], "SCKV");
    } else {
        $_SESSION['errors']['furigana'] = "フリガナが空でした";
        $formError = true;
    }
// ----------------------------------------
    $genderValues = ["male", "female"];
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
        $_SESSION['errors']['birthday'] = "誕生日が空でした";
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
        $_SESSION['errors']['mail'] = "メールアドレスが空でした";
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
        $_SESSION['errors']['mailCheck'] = "メールアドレス(確認)が空でした";
        $formError = true;
    }
// ----------------------------------------
    if(isset($_POST['genre'])) {
        $genreValues = ["western", "japanese", "anime", "drama", "documentary", "horror", "variety"];
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
        $_SESSION['errors']['genre'] = "興味のあるジャンルは最低でも一つ以上選択してください";
        $formError = true;
    }

    if($formError) {
        header('Location: index.php');
    }
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
        $plan = isset($_SESSION['plan']) ? $_SESSION['plan'] : "bronze";
        $planCheck = [
            "bronze" => "",
            "silver" => "",
            "gold" => "",
        ];
        $planCheck[$plan] = "checked";
    // ----------------------------------------
        $option = isset($_SESSION['option']) ? $_SESSION['option'] : [];
        $optionCheck = [
            "4k" => "",
            "multiDevice" => "",
            "parentalControl" => "",
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

    if (isset($_SESSION['errors']) && count($_SESSION['errors']) !== 0) {
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プラン・オプション選択画面</title>
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

        echo "<pre><h1>deviceNumCheck</h1>";
        var_dump($deviceNumCheck["2"]);    
        var_dump($deviceNumCheck["3"]);    
        var_dump($deviceNumCheck["4"]);    
        echo "</pre>";
    ?>

    <table border=1>
        <tr>
            <th>氏名</th>
            <td><?php echo h($_SESSION["name"]); ?></td>
        </tr>
        <tr>
            <th>フリガナ</th>
            <td><?php echo h($_SESSION["furigana"]); ?></td>
        </tr>
        <tr>
            <th>性別</th>
            <td><?php echo $_SESSION["gender"]; ?></td>
        </tr>
        <tr>
            <th>生年月日</th>
            <td><?php echo h($_SESSION["birthday"]); ?></td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td><?php echo h($_SESSION["mail"]); ?></td>
        </tr>
        <tr>
            <th>メールアドレス(確認)</th>
            <td><?php echo h($_SESSION["mailCheck"]); ?></td>
        </tr>
        <tr>
            <th>興味のあるジャンル</th>
            <td><?php echo implode(", ", $_SESSION['genre']); ?></td>
        </tr>
    </table>
<br>
    <form action="confirm.php" method="POST">
        <table border=1>
            <tr>
                <th>基本プランの選択</th>
                <td>
                    <label><input type="radio" name="plan" value="bronze" <?php echo $planCheck['bronze']; ?>>ブロンズ(基本プラン 500円/月)</label><br>
                    <label><input type="radio" name="plan" value="silver" <?php echo $planCheck['silver']; ?>>シルバー(基本に加えさらに高画質 800円/月)</label><br>
                    <label><input type="radio" name="plan" value="gold" <?php echo $planCheck['gold']; ?>>ゴールド(高画質でさらに最新作をお届け 1000円/月)</label>
                    <p><?php echo $errors2['plan'] ?></p>
                </td>
            </tr>
            <tr>
                <th>オプションの選択</th>
                <td>
                    <label><input type="checkbox" name="option[]" value="4k" <?php echo $optionCheck["4k"]; ?>>4K画質対応(+600円)</label><br>
                    <label><input type="checkbox" name="option[]" value="multiDevice" <?php echo $optionCheck["multiDevice"]; ?> id="multiDevice">複数デバイス視聴</label><br>
                    <select name="deviceNum" id="deviceNum">
                        <option value="2" <?php echo $deviceNumCheck["2"]; ?>>2台</option>
                        <option value="3" <?php echo $deviceNumCheck["3"]; ?>>3台</option>
                        <option value="4" <?php echo $deviceNumCheck["4"]; ?>>4台</option>
                    </select>
                    <p><?php echo $errors2['deviceNum'] ?></p>
                    <label><input type="checkbox" name="option[]" value="parentalControl" <?php echo $optionCheck["parentalControl"]; ?>>ペアレンタルコントロール</label><br>
                    <p><?php echo $errors2['option'] ?></p>
                </td>
            </tr>
            <tr>
                <th>クーポンコードの利用</th>
                <td>
                    <input type="text" name="coupon" value="<?php echo $coupon; ?>" placeholder="クーポンがあれば入力">
                    <p><?php echo $errors2['coupon'] ?></p>
                </td>
            </tr>
            <tr>
                <th><a href="index.php">個人情報の修正</a></th>
                <td><input type="submit" value="登録内容の確認"></td>
            </tr>
        </table>
    </form>
    <a href="registration-complete.php">セッションを削除する</a>

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