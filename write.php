<?php
    include 'includes/login.php';

    $content = $_POST['content'];
    $userid = $_SESSION['id'];

    setcookie('trackingid', $trackingid, time() + 60 * 60 * 24 * 30);

    $trackingid = $_COOKIE['trackingid'];
/*
    if ($token !=sha1(session_id())){
        header('Location: index.php');
        exit();
    }
    */

    $dsn = 'mysql:host=localhost;dbname=secret_board;charset=utf8';
    $user = 'bbsuser';
    $password = 'password';
    
    try{
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare("
        INSERT INTO post (content, user_id, date, cookie)
        VALUES (:content, :userid, now(), :cookie)"
    );
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindParam(':cookie', $trackingid, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: index.php');
    exit();
    } catch (PDOException $e) {
        die ('エラー：' . $e->getMessage());
    }
?>