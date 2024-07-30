<?php

session_start();
include_once "../includes/db.php";
include_once "../includes/functions.php";

if (!is_admin_logged_in()) {
    user_not_login();
    exit;
}

$stmt = $pdo->query("SELECT
                    Inquiry.id, Inquiry.title, User.username, Organization.orgname, Inquiry.created_date
                    FROM Inquiry
                    JOIN User ON Inquiry.user_id = User.id
                    JOIN Organization ON User.organization_id = Organization.id
                    WHERE Inquiry.deleted = 0
                    ORDER BY Inquiry.created_date DESC");
$inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>問い合わせ管理</title>
    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">
<p class="mb-2"><span class="bg-warning">管理者画面を表示中</span></p>

<h2 class="mb-4">問い合わせ管理</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>タイトル</th>
            <th>組織名</th>
            <th>ユーザ名</th>
            <th>作成日時</th>
            <th>アクション</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($inquiries as $inquiry): ?>
            <tr>
                <td><?php echo htmlspecialchars($inquiry['id']); ?></td>
                <td><?php echo htmlspecialchars($inquiry['title']); ?></td>
                <td><?php echo htmlspecialchars($inquiry['orgname']); ?></td>
                <td><?php echo htmlspecialchars($inquiry['username']); ?></td>
                <td><?php echo htmlspecialchars($inquiry['created_date']); ?></td>
                <td>
                    <a href="inquiry_reply.php?inquiry_id=<?php echo htmlspecialchars($inquiry['id']); ?>" class="btn btn-sm btn-primary">返信</a>
                    <form action="inquiry_delete.php" method="post" onsubmit="return confirm('本当に削除しますか？');" class="d-inline-block">
                        <input type="hidden" name="inquiry_id" value="<?php echo htmlspecialchars($inquiry['id']); ?>">
                        <button type="submit" class="btn btn-sm btn-danger">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="top.php" class="btn btn-secondary">トップに戻る</a>

</div></div></div>
<script src="../bootstrap/bootstrap.min.js"></script>
</body>
</html>
