<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to create an inquiry.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['message'])) {
    $title = $_POST['title'];
    $user_id = $_SESSION['user_id'];
    $message = $_POST['message'];

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO Inquiry (title, user_id) VALUES (:title, :user_id)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':user_id', $user_id);
        $result = $stmt->execute();
        $success = false;
        if ($result) {
            $inquiry_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO InquiryPost (inquiry_id, user_id, message) VALUES (:inquiry_id, :user_id, :message)");
            $stmt->bindParam(':inquiry_id', $inquiry_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':message', $message);
            $result = $stmt->execute();
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