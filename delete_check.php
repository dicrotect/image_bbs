<?php
    require('dbconnect.php');
    session_start();
    function h($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    if (isset($_SESSION['id'])){
        //idは画像のid
        $id = $_REQUEST['id'];


        //picturesの中から選択した写真(id)のデータを拾う
        $sql = sprintf('SELECT * FROM pictures WHERE id = %d',
            $id
          );
        $record = mysqli_query($db, $sql) or die (mysqli_error($db));
        //picturesの投稿者idと$_SESSION[id]が一致ならDELETE発動
        //picturesの中から選択した写真のデータを消す
        
    }
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>DELETE?</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/js/index.js">
    <link rel="stylesheet" href="./assets/css/profile.css">
    <link rel="stylesheet" href="./assets/js/profile.js">
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/js/bootstrap.js">
  </head>
  <body>
    <div class="container auth"  >
        <div id="big-form" class="well auth-box" style="margin:0 auto;">
            <fieldset>
                <h1>この写真を削除してよろしいですか？</h1>
                <?php 
                    $table = mysqli_fetch_assoc($record);
                    $_SESSION['delete_id'] = $table['id'];
                ?>
                <div>
                  <p><?php echo h($table['title']);?></p>
                    <?php
                        echo sprintf('<img src="./pictures/%s". width="100" height="100">',
                        h($table['picture']));
                    ?>
                  <p><?php echo h($table['comments']);?></p>
                </div>
                <a href="delete.php">削除する</a>
                <a href="mypage.php">mypageにもどる</a>
            </fieldset>
        </div>
    </div>
  </body>
</html>







