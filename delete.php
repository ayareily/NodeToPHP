<?php
include 'includes/login.php';

$id = intval($_POST['id']);
$userid = $_SESSION['id'];

if ($id == '' || $userid == ''){
    header ('Location: index.php');
    exit();
}

/*
if ($token != sha1(session_id())){
    header('Location: bbs.php');
    exit();
}
*/

$dsn = 'mysql:host=localhost; dbname=secret_board;charset=utf8';
$user = 'bbsuser';
$password = 'password';

try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $stmt = $db->prepare(
        "DELETE FROM post WHERE id=:id AND user_id=:userid"
    );
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();
} catch(PDOException $e){
    echo 'エラー：' . $e->getMessage();
}
header('Location: index.php');
exit();
?>