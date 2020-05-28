<?php
    include 'includes/login.php';

    $content = $_POST['content'];
    $userid = $_SESSION['userid'];

    /*
    if ($token !=sha1(session_id())){
        header('Location: index.php');
        exit();
    }
    setcookie('userid', $userid, time() + 60 * 60 * 24 * 30);
    */

    $dsn = 'mysql:host=localhost;dbname=secret_board;charset=utf8';
    $user = 'bbsuser';
    $password = 'password';
    
    try{
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare("
        INSERT INTO post (content, user_id, date)
        VALUES (:content, :userid, now())"
    );
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: index.php');
    exit();
    } catch (PDOException $e) {
        die ('エラー：' . $e->getMessage());
    }
?>