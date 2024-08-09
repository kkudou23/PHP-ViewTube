<!-- confirm.php -->
<?php
    session_start();

    if(!isset($_SESSION['errors2'])) {
        header('Location: select-plan.php');
    }

    $jumpFrom = $_SERVER['HTTP_REFERER'];
    
if (strpos($jumpFrom, 'select-plan.php') !== false) {
// ====================ここからselect-planでの入力が正しいか====================
    $formError2 = false;
    $_SESSION['errors2'] = [];

// ----------------------------------------
    $planValues = ["bronze", "silver", "gold"];
    if(isset($_POST['plan']) && in_array($_POST['plan'], $planValues, true)) {
        $_SESSION['plan'] = $_POST['plan'];
    } else {
        $_SESSION['errors2']['plan'] = "基本プランの値が不正です";
        $formError2 = true;
    }
// ----------------------------------------
    if(isset($_POST['option'])) {
        $optionValues = ["4k", "multiDevice", "parentalControl"];
        $optionError = false;
        
        foreach($_POST['option'] as $data) {
            if(!in_array($data, $optionValues, true)) {
                $optionError = true;
            }
        }

        if(!$optionError) {
            $_SESSION['option'] = $_POST['option'];
        } else {
            $_SESSION['errors2']['option'] = "オプションの値が不正です";
            $formError2 = true;
        }
    } else {
        $_SESSION['option'] = [];
    }
// ----------------------------------------
    $deviceNumValues = ["2", "3", "4"];
    if(isset($_POST['deviceNum']) && in_array($_POST['deviceNum'], $deviceNumValues, true)) {
        if(isset($_POST['option']) && in_array("multiDevice", $_POST['option'], true)) {
            $_SESSION['deviceNum'] = $_POST['deviceNum'];
        } else {
            $_SESSION['deviceNum'] = "1";
        }
    } else {
        $_SESSION['errors2']['deviceNum'] = "デバイス数の値が不正です";
        $formError2 = true;
    }
// ----------------------------------------
    if(!empty($_POST['coupon'])) {
        $couponFormatResult = couponFormat($_POST['coupon']);
        if($couponFormatResult !== false) {
            $_SESSION['coupon'] = $couponFormatResult;
        } else {
            $_SESSION['errors2']['coupon'] = "クーポンの値が不正です";
            $formError2 = true;
        }
    } else {
        $_SESSION['coupon'] = "";
    }
// ----------------------------------------
}

function couponFormat($text) {
    // 半角英数、全角英数、ハイフンが一回以上繰り返されているか
    if(preg_match("/^[a-zA-Z0-9ａ-ｚＡ-Ｚ０-９\-－]+$/u", $text) === 1) {
        // 全角英数を半角英数に変換
        $text = mb_convert_kana($text, "a");
        // 小文字を大文字に変換
        $text = strtoupper($text);
        // パターンにマッチするかチェック
        if(preg_match("/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/", $text) === 1) {
            return $text;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

if($formError2) {
    header('Location: select-plan.php');
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
};
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認画面</title>
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
    <table border=1>
        <tr>
            <th>基本プラン</th>
            <td>
                <?php echo h($_SESSION["plan"]); ?>
            </td>
        </tr>
        <tr>
            <th>オプション</th>
            <td>
                <?php echo implode(", ", $_SESSION['option']); ?>
            </td>
        </tr>
        <tr>
            <th>台数</th>
            <td>
                <?php echo h($_SESSION["deviceNum"]); ?>
            </td>
        </tr>
        <tr>
            <th>クーポンコード</th>
            <td>
                <?php echo h($_SESSION["coupon"]); ?>
            </td>
        </tr>
        <tr>
            <th><a href="index.php">indexに戻る</a></th>
            <td><a href="select-plan.php">プラン・オプションの選択に戻る</a></td>
        </tr>
    </table>
    <a href="registration-complete.php">セッションを削除する</a>
</body>
</html>