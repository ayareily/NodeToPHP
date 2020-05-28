<?php
    include 'includes/login.php';

    $num = 10;

    $dsn ='mysql:host=localhost;dbname=secret_board;charset=utf8';
    $user = 'bbsuser';
    $password = 'password';

    $page = 0;
    if (isset($_GET['page']) && $_GET['page'] > 0){
        $page = intval($_GET['page']) -1;
    }

    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare(
            'SELECT * FROM post ORDER BY date DESC LIMIT :page, :num'
        );
        $page = $page * $num;
        $stmt->bindParam(':page', $page, PDO::PARAM_INT);
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e){
        echo 'エラー：' . $e->getMessage();
    }
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>掲示板</title>
</head>
<body>
    <h1>掲示板</h1>
    <a href="logout.php">ログアウト</a>
    <form action="write.php" method="post">
        <textarea name="content"></textarea>
        <p><input type="submit" value="書き込む">
    </form>
    <hr />

<?php
    while ($row = $stmt->fetch()):
?>
    <p>名前：<?php echo $row['user_id'] ?></p>
    <p><?php echo nl2br(htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8'), false) ?></p>
    <p><?php echo $row['date'] ?></p>
    <form action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="userid" value="<?php echo $row['user_id']; ?>">
        <button type="submit">削除</button>
    </form>
<?php
    endwhile;

    try{
        $stmt = $db->prepare('SELECT COUNT(*) FROM post');
        $stmt->execute();
    }   catch (PDOException $e) {
        echo 'エラー：' . $e->getMessage();
    }
?>
</body>
</html>