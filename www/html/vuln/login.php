<?php

session_start();
require_once 'includes/db.php';

$mail_address = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mail_address'], $_POST['password'])) {
    $mail_address = $_POST['mail_address'];
    $password = $_POST['password'];
    $sql = "SELECT *
            FROM User
            WHERE mail_address = '$mail_address' AND password = '$password'";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['is_admin'] === 0) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['organization_id'] = $row['organization_id'];
        header("Location: top.php");
        exit;
    }
    $error = 'Invalid mail address or password';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">

<h3 class="mb-3">ログイン</h3>
<form method="post">
    <div class="mb-3">
        <label for="mail_address" class="form-label">メールアドレス</label>
        <input type="text" class="form-control" id="mail_address" name="mail_address" value="<?php echo $mail_address?>">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">パスワード</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
<?php if (isset($error)): ?>
    <div class="alert alert-danger mt-3" role="alert">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

</div></div></div>
<script src="./bootstrap/bootstrap.min.js"></script>
</body>
</html>