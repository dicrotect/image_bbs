<?php
    //データベースの接続のみ
    $db = mysqli_connect('localhost','root','mysql','image_bbs') or
        die(mysqli_connect_error());
    mysqli_set_charset($db,'utf-8');
?>
