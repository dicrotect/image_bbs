<?php

    require('dbconnect.php'); 
    session_start();
    $_SESSION['like_id'] = $_REQUEST['id'];
    //いいねがきた写真
    echo $_SESSION['like_id'];
    //いいねをした人
    echo '<br>';
    echo $_SESSION['id'];
    echo '<br>';


    //いままでに$_SESSION[id]が$_SESSION['like_id']にいいねした数を数える
    $sql = sprintf('SELECT * FROM likes WHERE picture_id = %d AND member_id=%d',
        $_SESSION['like_id'],
        $_SESSION['id']
    );
    $sql = mysqli_query($db,$sql) or die(mysqli_error($db));
    $like = mysqli_fetch_assoc($sql); 
    //いいねした数
    echo $like['member_id'];


    //いいねした数が0ならデータベースへ
    if ($like['member_id'] != $_SESSION['id'] ){
        $sql = sprintf('INSERT INTO likes SET picture_id=%d ,member_id=%d',
            mysqli_real_escape_string($db,$_SESSION['like_id']), 
            mysqli_real_escape_string($db,$_SESSION['id'])
        );
        mysqli_query($db,$sql) or die(mysqli_error($db));
        header('Location:view.php');
        exit();

    } else {
        $_SESSION['like_error'] = "error";
        header('Location:view.php');
        exit();
}



?>
