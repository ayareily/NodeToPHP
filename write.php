<?php
    include 'includes/login.php';
    include 'includes/db.php';

    $content = $_POST['content'];
    $postedBy = $_SESSION['userid'];

    if ($content == '') {
        header('Location: index.php');
        exit();
    }

    if ($token !=sha1(session_id())){
        header('Location: index.php');
        exit();
    }
    setcookie('userid', $userid, time() + 60 * 60 * 24 * 30);

    try{
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare("
        INSERT INTO post (content, postedBy, date)
        VALUES (:content, :postedBy, now())"
    );
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':postedBy', $userid, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: index.php');
    exit();
    } catch (PDOException $e) {
        die ('エラー：' . $e->getMessage());
    }
?>