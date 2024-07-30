<?php

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function user_not_login() {
    echo "ログインしていません。<br>";
    echo '<a href="./login.php">ログイン画面へ</a>';
}
