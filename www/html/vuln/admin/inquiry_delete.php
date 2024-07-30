<?php

session_start();
include_once "../includes/db.php";
include_once "../includes/functions.php";

if (!is_logged_in()) {
    user_not_login();
    exit;
}

// 削除ボタンが押された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["inquiry_id"])) {
    $inquiry_id = $_POST["inquiry_id"];

    // ユーザからの入力値を直接受け付ける実装
    $sql = "UPDATE Inquiry SET deleted = TRUE WHERE id = $inquiry_id";
    $result = $pdo->query($sql);
    
    if ($result) {
        // 削除成功時は問い合わせ一覧画面にリダイレクト
        header("Location: inquiry_management.php");
        exit;
    } else {
        // 削除失敗時の処理（エラーメッセージ表示など）
        echo "トピックの削除に失敗しました。";
    }
}

?>