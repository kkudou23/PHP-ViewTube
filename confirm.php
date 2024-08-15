<?php
    session_start();

    $_SESSION['phase-confirm'] = false;
    $jumpFrom = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
    
    // ▼==================== select-planで入力された情報のチェック ====================▼
    if (strpos($jumpFrom, 'select-plan.php') !== false) { // select-planからこのページに飛んできた場合
        $formError2 = false; // どこかの値にエラーがあった場合trueになる変数
        $_SESSION['errors2'] = []; // セッションにerror配列を用意
        $fee = 0; // 月額利用料を入れる変数
        $planFee = 0; // プランの料金を入れる変数

        // ---------- プランチェック ----------
        $planValues = ["ブロンズ", "シルバー", "ゴールド"]; // 規定の値一覧の配列
        if(isset($_POST['plan']) && in_array($_POST['plan'], $planValues, true)) { // プランの値が送信されていて、かつ規定の値から選ばれている(配列の中にある)か
            $_SESSION['plan'] = $_POST['plan'];
            
            if($_POST['plan'] === "ブロンズ") {
                $planFee = 500;
            } elseif($_POST['plan'] === "シルバー") {
                $planFee = 800;
            } elseif($_POST['plan'] === "ゴールド") {
                $planFee = 1000;
            }
            $fee += $planFee;
        } else {
            $_SESSION['errors2']['plan'] = "基本プランの値が不正です";
            $formError2 = true;
        }

        // ---------- オプションチェック ----------
        if(isset($_POST['option'])) {
            $optionValues = ["4K画質対応", "複数デバイス視聴", "ペアレンタルコントロール"]; // 規定の値一覧の配列
            $optionError = false;
            
            foreach($_POST['option'] as $data) {
                if(!in_array($data, $optionValues, true)) { // 規定の値から選ばれていない(配列の中にない)場合
                    $optionError = true;
                } else {
                    if($data === "4K画質対応") { // 規定の値から選ばれていて、かつその値が"4K画質対応"の場合
                        $fee += 600;
                    }
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

        // ---------- デバイス数チェック ----------
        $deviceNumValues = ["2", "3", "4"]; // 規定の値一覧の配列
        if(isset($_POST['deviceNum']) && in_array($_POST['deviceNum'], $deviceNumValues, true)) { // デバイス数の値が送信されていて、かつ規定の値から選ばれている(配列の中にある)か
            if(isset($_POST['option']) && in_array("複数デバイス視聴", $_POST['option'], true)) { // オプションの値が送信されていて、かつその中に「複数デバイス視聴」があるか
                $_SESSION['deviceNum'] = $_POST['deviceNum'];
            } else {
                $_SESSION['deviceNum'] = "1"; // 「複数デバイス視聴」が選択されていない場合はデバイス数を1にする
            }

            if($_SESSION['deviceNum'] === "2") {
                $fee += 200;
            } elseif($_SESSION['deviceNum'] === "3") {
                $fee += 400;
            } elseif($_SESSION['deviceNum'] === "4") {
                $fee += 600;
            }
        } else {
            $_SESSION['errors2']['deviceNum'] = "デバイス数の値が不正です";
            $formError2 = true;
        }

        // ---------- クーポンチェック ----------
        if(!empty($_POST['coupon'])) {
            if(couponFormat($_POST['coupon']) !== false) {
                $_SESSION['coupon'] = $_POST['coupon'];
                $fee -= 100;
            } else {
                $_SESSION['errors2']['coupon'] = "クーポンの値が不正です";
                $formError2 = true;
            }
        } else {
            $_SESSION['coupon'] = "";
        }

        // ----------------------------------------
        if($formError2) { // どこかの値にエラーがあった場合select-planに戻る
            header('Location: select-plan.php');
        }
    } else {
        header('Location: index.php');
    }
    // ▲========================================▲

    // ▼==================== select-planで入力された情報のチェックと入力された情報をこのページ(confirm)で表示する際に使う関数 ====================▼
    function couponFormat($text) {
        if(preg_match("/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/", $text) === 1) {
            return $text;
        } else {
            return false;
        }
    }

    function h($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    };
    // ▲========================================▲
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認画面</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
</head>
<body>
    <h1 id="service-name">ViewTube Premium</h1>

    <main>
        <h3>ステップ 3/3</h3>
        <h1>入力した情報を確認</h1>

        <table>
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
                <td><?php echo $_SESSION["gender"] ?></td>
            </tr>
            <tr>
                <th>生年月日</th>
                <td>
                    <?php
                        echo date("Y年n月j日", strtotime($_SESSION["birthday"]));
                    ?>
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td><?php echo h($_SESSION["mail"]); ?></td>
            </tr>
            <tr>
                <th>興味のあるジャンル</th>
                <td><?php echo implode(", ", $_SESSION['genre']); ?></td>
            </tr>
        </table>

        <a href="index.php" class="button gray-button">個人情報の修正</a>

        <table class="plan-table">
            <tr>
                <th>基本プラン</th>
                <td>
                    <?php echo h($_SESSION["plan"]); ?>
                </td>
                <td class="show-price">
                    <?php echo $planFee ?>円
                </td>
            </tr>
                <?php
                    if(count($_SESSION['option']) === 0) {
                        echo "<tr><th>オプション</th><td>なし</td><td class='show-price'></td></tr>";
                    } else {
                        $count=1;
                        foreach($_SESSION['option'] as $option) {
                            $deviceNum = "";
                            if($option === "4K画質対応") {
                                $thisOptionPrice = 600;
                            } elseif ($option === "複数デバイス視聴") {
                                $thisOptionPrice = 200 * $_SESSION['deviceNum'] - 200;
                                $deviceNum = "(".$_SESSION['deviceNum']."台)";
                            } elseif ($option === "ペアレンタルコントロール") {
                                $thisOptionPrice = 0;
                            }

                            if(count($_SESSION['option']) === 1) {
                                echo "<tr><th>オプション</th><td>".$option.$deviceNum."</td><td class='show-price'>".$thisOptionPrice."円</td></tr>";
                            } else {
                                echo "<tr><th>オプション".$count."</th><td>".$option.$deviceNum."</td><td class='show-price'>".$thisOptionPrice."円</td></tr>";
                                $count+=1;
                            }
                        }
                    }
                ?>
            <tr>
                <th>クーポンコード</th>
                <td>
                    <?php
                    if($_SESSION["coupon"] === "") {
                        echo "なし";
                    } else {
                        echo "あり(".$_SESSION["coupon"].")<td class='show-price'>-100円</td>";
                    }
                    ?>
                </td>
            </tr>
        </table>

        <h1 class="monthly-fee">
            <span>月額利用料</span><?php echo $fee ?><span>円</span>
        </h1>

        <div class="button-container">
            <a href="select-plan.php" class="button gray-button">プラン・オプションの修正</a>
            <a href="registration-complete.php" class="button">登録完了</a>
        </div>
    </main>

    <footer>
        <p>2024 - PHP夏季課題</p>
    </footer>
</body>
</html>