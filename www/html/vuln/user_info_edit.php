<?php

session_start();
include_once "includes/db.php";
include_once "includes/functions.php";

if (!is_logged_in()) {
    user_not_login();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームからの入力を受け取り、ユーザ情報を更新する処理を実行する
    $password = $_POST["password"];
    if (!empty($password)) {
        $password_update =  ", password = '$password' ";
    } else {
        $password_update =  "";
    }
    
    $user_id = $_POST["user_id"];
    $name = $_POST["name"];

    $mail_address = $_POST["mail_address"];
    $sql = "UPDATE User
            SET username = '$name' $password_update, mail_address = '$mail_address'
            WHERE id = $user_id";
    $result = $pdo->query($sql);

    if ($result) {
        header("location: user_info.php?user_id=$user_id");
        exit;
    } else {
        echo "ユーザ情報の更新中にエラーが発生しました。";
    }

}
elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id = $_SESSION['user_id'];
    if (!empty($user_id)) {
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
    <link href="./bootstrap/bootstrap.min.css" rel="stylesheet">
    <title>ユーザ情報編集</title>
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">

<h2>ユーザ情報編集</h2>
<form action="./user_info_edit.php" method="post">
    <input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
    <div class="mb-3">
        <label for="name" class="form-label">名前</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user["username"]; ?>" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">パスワード</label>
        <input type="password" class="form-control" id="password" name="password" value="" placeholder="変更する場合のみ入力してください">
    </div>
    <div class="mb-3">
        <label for="mail_address" class="form-label">メールアドレス</label>
        <input type="email" class="form-control" id="mail_address" name="mail_address" value="<?php echo $user["mail_address"]; ?>" required>
    </div>
    <div class="mb-3">
        <input type="submit" class="btn btn-primary" value="更新">
    </div>
</form>
<a href="user_info.php?user_id=<?php echo $user["id"]; ?>" class="btn btn-secondary mt-3">戻る</a>

</div></div>
<script src="./bootstrap/bootstrap.min.js"></script>
</body>
</html>