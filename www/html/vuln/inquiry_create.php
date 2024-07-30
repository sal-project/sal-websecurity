<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to create an inquiry.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['user_id'], $_POST['message'])) {
    $title = $_POST['title'];
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];

    $pdo->beginTransaction();
    try {
        $result = $pdo->query("INSERT INTO Inquiry (title, user_id) VALUES ('$title', '$user_id')");
        $success = false;
        if ($result) {
            $inquiry_id = $pdo->lastInsertId();
            $result = $pdo->query("INSERT INTO InquiryPost (inquiry_id, user_id, message) VALUES ($inquiry_id, $user_id, '$message')");
            if ($result) {
                $pdo->commit();
                $success = true;
                header("Location: inquiry_detail.php?inquiry_id=".$inquiry_id);
            }
        }
        if (!$success) {
            echo "Failed to create inquiry.";
        }
    } catch(Exception $e) {
        $pdo->rollBack();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap/bootstrap.min.css" rel="stylesheet">
    <title>新規問い合わせ</title>
</head>
<body>
<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">

<h2 class="mb-3">新規問い合わせ</h3>
<form method="post">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
    <div class="mb-3">
        <label for="title" class="form-label">題名</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="message" class="form-label">内容</label>
        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">新規作成</button>
</form>

</div></div></div>
<script src="./bootstrap/bootstrap.min.js"></script>
</body>
</html>