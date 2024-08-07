<?php

session_start();
include_once "../includes/db.php";
include_once "../includes/functions.php";

if (!is_logged_in()) {
    user_not_login();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["user_id"]) && !empty($_POST["user_id"])) {
        $user_id = $_POST["user_id"];

        // ユーザを削除するSQL文
        // ユーザからの入力値を直接受け付ける実装
        $query = "DELETE FROM User WHERE id = $user_id";
        $result = $pdo->query($query);
        
        if ($result) {
            // 削除成功時はユーザ一覧画面にリダイレクト
            header("Location: user_management.php");
            exit;
        } else {
            // 削除失敗時の処理（エラー処理など）
            echo "ユーザの削除に失敗しました。";
        }
    } else {
        echo "削除するユーザが指定されていません。";
    }
} else {
    echo "不正なリクエストです。";
}
?>