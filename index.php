<?php
    include 'includes/login.php';

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
        <textarea name="content"></textarea>
        <p><input type="submit" value="書き込む">
    </form>
    <hr />
</body>
</html>