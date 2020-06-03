<?php
    session_start();//session開始

    if (isset($_SESSION['id']) && isset($_SESSION['name'])){
        header ('Location: index.php');
    } else if (isset($_POST['name']) && isset($_POST['password'])){
        
        $dsn = 'mysql:host=localhost;dbname=secret_board;charset=utf8';
        $user = 'bbsuser';
        $password = 'password';

        try{
            $db = new PDO($dsn, $user, $password);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt = $db->prepare("
            SELECT * FROM users WHERE name=:name AND password=:pass
            ");

            $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            $stmt->bindParam(':pass', sha1($_POST['password']), PDO::PARAM_STR);
            $stmt->execute();

            if ($row = $stmt->fetch()){
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['trackingid'] = bin2hex(random_bytes(16));
                session_regenerate_id(true);
                header('Location: index.php');
                exit();
            } else {
                header('Location: login.php');
                exit();
            }
        } catch(PDOException $e) {
            die ('エラー：' . $e->getMessage());
        }
    } else {    
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</head>
<body class="container m-5">
    <h1 class="text-center m-3">秘密の匿名掲示板ログイン</h1>
    <div class="card text-center p-3">
        <h2>ログインフォーム</h2>
            <form action="login.php" method="post">
                <p><input type="text" name="name" placeholder="ユーザー名"></p>
                <p><input type="password" name="password" placeholder="パスワード"></p>
                <input class="btn btn-primary" type="submit" value="ログイン">
            </form>
    </div>
</body>
</html>
<?php  } ?>