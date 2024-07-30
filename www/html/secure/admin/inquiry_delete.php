<?php

session_start();
include_once "../includes/db.php";
include_once "../includes/functions.php";

if (!is_admin_logged_in()) {
    user_not_login();
    exit;
}

// 削除ボタンが押された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["inquiry_id"])) {
    $inquiry_id = $_POST["inquiry_id"];
    
    // Inquiryテーブルの該当レコードのdeletedカラムを更新するSQL文
    $stmt = $pdo->prepare("UPDATE Inquiry SET deleted = TRUE WHERE id = :inquiry_id");
    $stmt->bindParam(':inquiry_id', $inquiry_id);
    $result = $stmt->execute();
    
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