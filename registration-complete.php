<?php 
    session_start();
    echo "<p>セッションを削除しました</p>";
    
    echo "<pre>";
    var_dump($_SESSION);
    echo "</pre>";

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

    echo "<a href='index.php'>戻る</a>";