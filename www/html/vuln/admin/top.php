<?php
session_start();
include_once "../includes/functions.php";

if (!is_logged_in()) {
    user_not_login();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin TOP</title>
    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">
<p class="mb-2"><span class="bg-warning">管理者画面を表示中</span></p>

<h1 class="text-center mb-4">メニュー</h1>
<ul class="list-group">
    <li class="list-group-item"><a href="user_management.php" class="text-decoration-none">ユーザ管理</a></li>
    <li class="list-group-item"><a href="inquiry_management.php" class="text-decoration-none">問い合わせ管理</a></li>
    <li class="list-group-item"><a href="login.php" class="text-decoration-none">ログアウト</a></li>
</ul>

</div></div></div>
<script src="../bootstrap/bootstrap.min.js"></script>
</body>
</html>
