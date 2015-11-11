<?php
    session_start();
    require('dbconnect.php');
    //$_SESSION[id]は画像を投稿したユーザのid
    if (isset($_SESSION['id'])){
        //idは画像のid
        $id = $_SESSION['delete_id'];


        //picturesの中から選択した写真(id)のデータを拾う
        $sql = sprintf('SELECT * FROM pictures WHERE id = %d',
            $id
          );
        $record = mysqli_query($db, $sql) or die (mysqli_error($db));

        $table = mysqli_fetch_assoc($record);
        //picturesの投稿者idと$_SESSION[id]が一致ならDELETE発動
        //picturesの中から選択した写真のデータを消す
        if($table['member_id'] == $_SESSION['id']){
            $sql = sprintf('DELETE from pictures WHERE id = %d',
                mysqli_real_escape_string($db, $id)
            );
            mysqli_query($db, $sql) or die (mysqli_error($db));
        }
    }
    header('Location: mypage.php');
    exit();
?>
