<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    user_not_login();
    exit;
}

$user_id = $_SESSION['user_id'];
$organization_id = $_SESSION['organization_id'];

// 問い合わせ一覧の取得
$sql = "SELECT Inquiry.id AS inquiry_id, Inquiry.title, User.username, Inquiry.created_date 
        FROM Inquiry 
        JOIN User ON Inquiry.user_id = User.id 
        WHERE Inquiry.deleted = FALSE AND User.organization_id = $organization_id
        ORDER BY Inquiry.created_date DESC";
$stmt = $pdo->query($sql);
$inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap/bootstrap.min.css" rel="stylesheet">
    <title>問い合わせ一覧</title>
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">

<h2 class="mb-4">問い合わせ一覧</h1>
<a href="./inquiry_create.php" class="btn btn-sm btn-primary">新規作成</a>
<table class="table">
    <thead>
        <tr>
            <th>タイトル</th>
            <th>ユーザ名</th>
            <th>作成日</th>
            <th>アクション</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($inquiries as $inquiry): ?>
            <tr>
                <td><?php echo $inquiry['title']; ?></td>
                <td><?php echo $inquiry['user_name']; ?></td>
                <td><?php echo $inquiry['created_date']; ?></td>
                <td>
                    <a href="inquiry_detail.php?inquiry_id=<?php echo $inquiry['inquiry_id']; ?>" class="btn btn-sm btn-primary">返信</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="top.php" class="btn btn-secondary">戻る</a>

</div></div></div>
<script src="./bootstrap/bootstrap.min.js"></script>
</body>
</html>