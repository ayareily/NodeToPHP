<?php
    include 'includes/login.php';

    $content = $_POST['content'];
    $userid = $_SESSION['id'];
    $postedby = $_SESSION['name'];
    $trackingid = $_SESSION['trackingid'];
    
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
        INSERT INTO post (content, user_id, postedby, date, trackingid)
        VALUES (:content, :userid, :postedby, now(), :trackingid)"
    );
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindParam(':postedby', $postedby, PDO::PARAM_STR);
    $stmt->bindParam(':trackingid', $trackingid, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: index.php');
    exit();
    } catch (PDOException $e) {
        die ('エラー：' . $e->getMessage());
    }
?>