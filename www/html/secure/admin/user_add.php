<?php

session_start();
include_once "../includes/db.php";
include_once "../includes/functions.php";

if (!is_admin_logged_in()) {
    user_not_login();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームからの入力を受け取り、新規ユーザを追加する処理を行う
    $name = $_POST["name"];
    $password = $_POST["password"];
    $organization_id = $_POST["organization_id"];
    $mail_address = $_POST["mail_address"];
    $is_admin = isset($_POST["is_admin"]) ? 1 : 0;

    // 新規ユーザを追加するSQL文
    $stmt = $pdo->prepare("INSERT INTO User (username, password, organization_id, mail_address, is_admin) VALUES (:name, :password, :organization_id, :mail_address, :is_admin)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':organization_id', $organization_id);
    $stmt->bindParam(':mail_address', $mail_address);
    $stmt->bindParam(':is_admin', $is_admin);
    $result = $stmt->execute();

    if ($result) {
        // 追加成功時はユーザ一覧画面にリダイレクト
        header("Location: user_management.php");
        exit;
    } else {
        // 追加失敗時の処理（エラー処理など）
        echo "新規ユーザの追加に失敗しました。";
    }

    // フォーム処理が終了したら終了する
    exit;
}

//組織一覧の取得
$stmt = $pdo->query("SELECT * FROM Organization");
$organizations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規ユーザ追加</title>
    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">
<p class="mb-2"><span class="bg-warning">管理者画面を表示中</span></p>

<h2 class="mb-4">新規ユーザ追加</h2>
<form action="user_add.php" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">名前</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">パスワード</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="organization_id" class="form-label">組織ID</label>
        <select class="form-select" id="organization_id" name="organization_id" required>
            <?php
                foreach ($organizations as $org) {
                    echo "<option value='" . htmlspecialchars($org["id"]) . "'>" . htmlspecialchars($org["orgname"]) . "</option>";
                }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="mail_address" class="form-label">メールアドレス</label>
        <input type="email" class="form-control" id="mail_address" name="mail_address" required>
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin">
        <label class="form-check-label" for="is_admin">管理者権限</label>
    </div>
    <button type="submit" class="btn btn-primary">追加</button>
</form>
<a href="user_management.php" class="btn btn-secondary mt-3">ユーザ一覧に戻る</a>

</div></div></div>
<script src="../bootstrap/bootstrap.min.js"></script>
</body>
</html>
