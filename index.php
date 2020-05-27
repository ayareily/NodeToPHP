<?php
    include 'includes/login.php';

    $num = 10;

    $dsn ='mysql:host=localhost;dbname=tennis;charset=utf8';
    $user = 'tennisuser';
    $password = 'password';

    $page = 0;
    if (isset($_GET['page']) && $_GET['page'] > 0){
        $page = intval($_GET['page']) -1;
    }

    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare(
            'SELECT * FROM bbs ORDER BY date DESC LIMIT :page, :num'
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
    <p><a href="index.php">トップページに戻る</a></p>
    <a href="logout.php">ログアウト</a>
    <form action="write.php" method="post">
        <p>名前：<input type="text" name="name" value="<?php echo $_COOKIE['name'] ?>"></p>
        <p>タイトル：<input type="text" name="title"></p>
        <textarea name="body"></textarea>
        <p>削除パスワード（数字4桁）：<input type="text" name="pass"></p>
        <p><input type="submit" value="書き込む">
        <input type="hidden" name="token" value="<?php echo sha1(session_id()); ?>"></p>
    </form>
    <hr />
</body>
</html>