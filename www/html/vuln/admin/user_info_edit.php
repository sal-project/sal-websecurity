<?php

session_start();
include_once "../includes/db.php";
include_once "../includes/functions.php";

if (!is_logged_in()) {
    user_not_login();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームからの入力を受け取り、ユーザ情報を更新する処理を実行する
    $password = $_POST["password"];
    $password_update = !empty($password) ? ", password = '$password'" : "";
    $query = "UPDATE User SET username = '{$_POST["name"]}'$password_update, organization_id = {$_POST["organization_id"]}, mail_address = '{$_POST["mail_address"]}', is_admin = " . (isset($_POST["is_admin"]) ? 1 : 0) . " WHERE id = {$_POST["user_id"]}";
    $result = $pdo->query($query);
    if ($result) {
        header("location: user_management.php");
        exit;
    } else {
        echo "ユーザ情報の更新中にエラーが発生しました。";
    }
}
elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    // URLパラメータからユーザIDを取得して、そのユーザの情報を取得する
    if (isset($_GET["user_id"]) && !empty($_GET["user_id"])) {
        $user_id = $_GET["user_id"];
        $sql = "SELECT * FROM User WHERE id = $user_id";
        $stmt = $pdo->query($sql);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            echo "ユーザが見つかりません。";
            exit;
        }
    } else {
        echo "ユーザIDが指定されていません。";
        exit;
    }
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
    <title>ユーザ情報編集</title>
    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">
<p class="mb-2"><span class="bg-warning">管理者画面を表示中</span></p>

<h2 class="mb-4">ユーザ情報編集</h2>
<form action="./user_info_edit.php" method="post">
    <input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
    <div class="mb-3">
        <label for="name" class="form-label">名前</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user["username"]; ?>" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">パスワード</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="変更する場合のみ入力してください">
    </div>
    <div class="mb-3">
        <label for="organization_id" class="form-label">組織ID</label>
        <select class="form-select" id="organization_id" name="organization_id" required>
            <?php
            foreach ($organizations as $org) {
                $selected = ($org["id"] == $user["organization_id"]) ? "selected" : "";
                echo "<option value='" . $org["id"] . "' $selected>" . $org["orgname"] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="mail_address" class="form-label">メールアドレス</label>
        <input type="email" class="form-control" id="mail_address" name="mail_address" value="<?php echo $user["mail_address"]; ?>" required>
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" <?php echo $user["is_admin"] ? "checked" : ""; ?>>
        <label class="form-check-label" for="is_admin">管理者権限</label>
    </div>
    <button type="submit" class="btn btn-primary">更新</button>
</form>
<a href="user_management.php" class="btn btn-secondary mt-3">ユーザ一覧に戻る</a>

</div></div></div>
<script src="../bootstrap/bootstrap.min.js"></script>
</body>
</html>