<?php

session_start();
include_once "../includes/db.php";
include_once "../includes/functions.php";

if (!is_logged_in()) {
    user_not_login();
    exit;
}

// 削除ボタンが押された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    
    // ユーザからの入力値を直接受け付ける実装
    $sql = "UPDATE InquiryPost SET deleted = TRUE WHERE id = $id";
    $result = $pdo->query($sql);

    if ($result) {
        // 削除成功時は返信画面にリダイレクト
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // 削除失敗時の処理（エラーメッセージ表示など）
        echo "返信の削除に失敗しました。";
    }
}

?>