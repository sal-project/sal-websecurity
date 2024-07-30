<?php

session_start();
include_once "../includes/db.php";
include_once "../includes/functions.php";

if (!is_admin_logged_in()) {
    user_not_login();
    exit;
}

// URLパラメータから問い合わせIDを取得
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["inquiry_id"]) && !empty(trim($_GET["inquiry_id"]))) {
        $inquiry_id = trim($_GET["inquiry_id"]);
        
        // 問い合わせ情報を取得するSQL文
        $stmt = $pdo->prepare("SELECT * FROM Inquiry WHERE id = :inquiry_id");
        $stmt->bindParam(':inquiry_id', $inquiry_id);
        $stmt->execute();
        $inquiry = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inquiry) {
            echo "指定された問い合わせが見つかりません。";
            exit;
        }

        // 過去の回答を取得するSQL文
        $stmt = $pdo->prepare("SELECT InquiryPost.*, User.username FROM InquiryPost INNER JOIN User ON InquiryPost.user_id = User.id WHERE inquiry_id = :inquiry_id AND InquiryPost.deleted = 0 ORDER BY InquiryPost.created_date ASC");
        $stmt->bindParam(':inquiry_id', $inquiry_id);
        $stmt->execute();
        $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        echo "問い合わせIDが指定されていません。";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームからの入力を受け取り、問い合わせへの返信を登録する処理を行う
    $message = $_POST["message"];
    $inquiry_id = $_POST["inquiry_id"];
    $user_id = $_SESSION["user_id"]; // 管理者のユーザIDを取得する

    // 問い合わせへの返信を登録するSQL文
    $stmt = $pdo->prepare("INSERT INTO InquiryPost (inquiry_id, user_id, message) VALUES (:inquiry_id, :user_id, :message)");
    $stmt->bindParam(':inquiry_id', $inquiry_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':message', $message);
    $result = $stmt->execute();
    
    if ($result) {
        // 登録成功時は現在のページにリダイレクト
        header("Location: ".$_SERVER['PHP_SELF']."?inquiry_id=".$inquiry_id);
        exit;
    } else {
        // 登録失敗時の処理（エラー処理など）
        echo "問い合わせへの返信に失敗しました。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>問い合わせへの返信</title>
    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5"><div class="row justify-content-center"><div class="col-10">
<p class="mb-2"><span class="bg-warning">管理者画面を表示中</span></p>

<h1 class="mb-4">問い合わせへの返信</h1>
<h2><?php echo $inquiry['title']; ?></h2>

<?php if (!empty($responses)): ?>
    <?php foreach ($responses as $response): ?>
        <div class="card mb-3">
            <div class="card-header fw-semibold">
                投稿者:
                <?php echo htmlspecialchars($response['username']); ?>
                <?php echo ($_SESSION['user_id'] === $response['user_id']) ? '（あなた）' : ''; ?>
            </div>
            <div class="card-body">
                <!-- メッセージ -->
                <?php echo htmlspecialchars($response['message']); ?>
            </div>
            <div class="card-footer fw-semibold">
                投稿日時:
                <?php echo htmlspecialchars($response['created_date']); ?>
                <form action="inquiry_reply_delete.php" method="post" onsubmit="return confirm('本当に削除しますか？');" class="d-inline-block">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($response['id']); ?>">
                    <button type="submit" class="btn btn-sm btn-danger">削除</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>まだ回答はありません。</p>
<?php endif; ?>
<div class="mb-3">
    <h3>返信フォーム</h3>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <input type="hidden" name="inquiry_id" value="<?php echo htmlspecialchars($inquiry_id); ?>">
        <div class="form-group mb-2">
            <textarea class="form-control" name="message" rows="4" placeholder="返信内容を入力してください" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">返信</button>
    </form>
</div>
<div>
    <a href="inquiry_management.php" class="btn btn-secondary mt-3">戻る</a>
</div>

</div></div></div>
<script src="../bootstrap/bootstrap.min.js"></script>
</body>
</html>
