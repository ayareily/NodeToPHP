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
    <title>秘密の匿名掲示板</title>
</head>
<body>
    <h1>秘密の匿名掲示板</h1>
    <h2>新規投稿</h2>
    <form action="write.php" method="post">
        <textarea name="content" cols="40" rows="4"></textarea>
        <p><input type="submit" value="投稿">
        <input type="hidden" name="token" value="<?php echo sha1(session_id()); ?>"></p>
    </form>
    <hr />
<?php
    while ($row = $stmt->fetch()):
        $title = $row['content'] ? $row['content'] : '（無題）';
?>
    <p><?php echo nl2br(htmlspecialchars($body, ENT_QUOTES, 'UTF-8'), false) ?></p>
    <p><?php echo $row['date'] ?></p>
    <form action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row ['id']; ?>">
        <input type="submit" value="削除">
        <input type="hidden" name="token" value="<?php echo sha1(session_id()); ?>">
    </form>
<?php
    endwhile;

    try{
        $stmt = $db->prepare('SELECT COUNT(*) FROM post');
        $stmt->execute();
    }   catch (PDOException $e) {
        echo 'エラー：' . $e->getMessage();
    }
    $comments = $stmt->fetchColumn();
    $max_page = ceil($comments / $num);
    echo '<p>';
    for ($i =1; $i <= $max_page; $i++){
        echo '<a href="bbs.php?page=' . $i . '">' . $i . '</a>&nbsp;';
    }
    echo '</p>';
?>
</body>
</html>