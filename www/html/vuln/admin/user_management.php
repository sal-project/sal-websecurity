<?php
session_start();
include_once "../includes/db.php";
include_once "../includes/functions.php";

if (!is_logged_in()) {
    user_not_login();
    exit;
}

// ユーザ一覧を取得
$sql = "SELECT User.*, Organization.orgname FROM User LEFT JOIN Organization ON User.organization_id = Organization.id";
$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ一覧</title>
    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">
<p class="mb-2"><span class="bg-warning">管理者画面を表示中</span></p>

<h2 class="mb-4">ユーザ一覧</h2>
<a href="user_add.php" class="btn btn-primary mb-3">ユーザ追加</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>組織ID</th>
            <th>管理者権限</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user["id"]; ?></td>
                <td><?php echo $user["username"]; ?></td>
                <td><?php echo $user["mail_address"]; ?></td>
                <td><?php echo $user["orgname"]; ?></td>
                <td><?php echo $user["is_admin"] ? "はい" : "いいえ"; ?></td>
                <td>
                    <a href="user_info_edit.php?user_id=<?php echo $user["id"]; ?>" class="btn btn-sm btn-primary">編集</a>
                    <form action="user_delete.php" method="post" onsubmit="return confirm('本当に削除しますか？');" class="d-inline-block" class="d-inline-block">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="./top.php" class="btn btn-secondary">トップに戻る</a>

</div></div></div>
<script src="../bootstrap/bootstrap.min.js"></script>
</body>
</html>
