<?php

session_start();
require 'includes/db.php';
require 'includes/functions.php';

if (!is_logged_in()) {
    user_not_login();
    exit;
}

// ユーザーIDをセッション情報から取得する
$user_id = $_SESSION['user_id'];

if (isset($_GET["user_id"]) && !empty($_GET["user_id"])) {
    if ($user_id != intval($_GET["user_id"])) {
        echo "ユーザIDが一致していません。";
        exit;
    }
} else {
    echo "ユーザIDが指定されていません。";
    exit;
}

$stmt = $pdo->prepare("SELECT User.username, User.mail_address, Organization.orgname
                    FROM User
                    INNER JOIN Organization
                    ON User.organization_id = Organization.id WHERE User.id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $user_name = $row['username'];
    $mail_address = $row['mail_address'];
    $orgnization_name = $row['orgname'];
    break;
}
if (!isset($user_name)) {
    echo "ユーザーが見つかりませんでした";
    exit;
}
 
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap/bootstrap.min.css" rel="stylesheet">
    <title>ユーザー情報</title>
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">

<h1>ユーザー情報</h1>
<a href="./user_info_edit.php" class="btn btn-sm btn-primary">編集</a>
<table class="table">
    <thead>
        <tr><th>名前</th><td><?php echo htmlspecialchars($user_name) ?></td></tr>
    </thead>
    <tbody>
        <tr><th>メールアドレス</th><td><?php echo htmlspecialchars($mail_address) ?></td></tr>
        <tr><th>組織</th><td><?php echo htmlspecialchars($orgnization_name) ?></td></tr>
    </tbody>
</table>
<a href="top.php" class="btn btn-secondary">戻る</a>

</div></div></div>
<script src="./bootstrap/bootstrap.min.js"></script>
</body>
</html>