<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    user_not_login();
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap/bootstrap.min.css" rel="stylesheet">
    <title>TOP</title>
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">

<h1 class="text-center mb-4">メニュー</h1>
<ul class="list-group">
    <li class="list-group-item"><a href="inquiry_list.php" class="text-decoration-none">問い合わせ一覧表示</a></li>
    <li class="list-group-item"><a href="user_info.php?user_id=<?php echo $user_id ?>" class="text-decoration-none">ユーザー情報表示</a></li>
    <li class="list-group-item"><a href="login.php" class="text-decoration-none">ログアウト</a></li>
</ul>

</div></div></div>
<script src="./bootstrap/bootstrap.min.js"></script>
</body>
</html>