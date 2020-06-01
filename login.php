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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <h1>秘密の匿名掲示板ログイン</h1>
    <h2>ログインフォーム</h2>
    <form action="login.php" method="post">
        <p>ユーザ名：<input type="text" name="name"></p>
        <p>パスワード：<input type="password" name="password"></p>
        <p><input type="submit" value="ログイン"></p>
    </form>
</body>
</html>
<?php  } ?>