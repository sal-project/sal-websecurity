<!DOCTYPE html>
<html>
<head>
    <title>SQLフォーム</title>
</head>
<body>
    <h2>SQLフォーム</h2>
    <form action="" method="post">
        <label for="sql_query">SQLクエリを入力してください:</label><br>
        <textarea name="sql_query" rows="8" cols="80"><?php echo htmlspecialchars($_POST["sql_query"]) ?></textarea><br>
        <input type="submit" value="実行">
    </form>

    <?php
    // フォームからのSQLクエリを受け取る
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $sql_query = $_POST["sql_query"];

        // セミコロンが末尾にない場合はエラーとする
        if (substr(trim($sql_query), -1) !== ';') {
            echo "<p style='color: red;'>SQLクエリの末尾にはセミコロンを付けてください。</p>";
            exit;
        }

        // データベースに接続
        $host = 'localhost';
        $dbname = 'your_database';
        $username = 'root';
        $password = 'Passw0rd!';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Could not connect to the database $dbname :" . $e->getMessage());
        }

        // 入力されたSQLクエリを実行
        $stmt = $pdo->query($sql_query);

        // 実行されたSQLクエリを表示
        echo "<h2>実行されたSQLクエリ</h2>";
        echo "<p>" . htmlspecialchars($sql_query) . "</p>";

        // 結果を表形式で表示
        echo "<h2>実行結果</h2>";
        echo "<table border='1'>";
        echo "<tr>";
        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $columnMeta = $stmt->getColumnMeta($i);
            echo "<th>" . htmlspecialchars($columnMeta["name"]) . "</th>";
        }
        echo "</tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
</body>
</html>