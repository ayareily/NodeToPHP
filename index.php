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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body class="container">
    <a href="logout.php" class="btn btn-info float-right">ログアウト</a>
    <h1>秘密の匿名掲示板</h1>
    <h2>新規投稿</h2>
    <form action="write.php" method="post">
        <div class="form-group">
            <textarea name="content" rows="4"></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">投稿</button>
        </div>
    </form>
    <hr />

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<?php
    while ($row = $stmt->fetch()):
?>
    <div class="card my-3">
        <div class="card-header">
            <p><?php echo $row['id'] ?>：ID：<?php echo $row['cookie'] ?></p>
        </div>
        <div class="card-body">
            <p><?php echo nl2br(htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8'), false) ?></p>
        </div>
        <div class="card-footer">
            <div>
                <p>投稿日時：<?php echo $row['date'] ?></p>
                <p>投稿者：<?php echo $row['postedby'] ?></p>
            </div>
            <form action="delete.php" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="postedby" value="<?php echo $row['postedby'] ?>">
                <button type="submit" class="btn btn-danger float-right">削除</button>
        </div>
    </div>
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