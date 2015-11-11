<?php
    require('dbconnect.php');
    session_start();
    function h($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    $sql = sprintf('SELECT m.name , p.* FROM members m, pictures p WHERE p.id=%d',
        $_REQUEST['id']
    );
    $sql = mysqli_query($db,$sql) or die (mysqli_error($db));
    $post = mysqli_fetch_assoc($sql);
    
    if (isset($_POST['re_comment'])){
        $sql = sprintf('INSERT INTO comments SET 
            picture_id=%d, comment="%s", created=NOW(), member_id=%d',
            //写真のid
            $post['id'],
            //コメントの内容
            mysqli_real_escape_string($db,$_POST['re_comment']),
            //コメントを投稿したユーザのid
            $_SESSION['id']
        );
        mysqli_query($db, $sql) or die (mysqli_error($db));
    }

    $sql = sprintf('SELECT m.name, c.* FROM members m, comments c 
        WHERE m.id=%d AND c.picture_id=%d',
        $_SESSION['id'],
        $_REQUEST['id']
    );
    $sql = mysqli_query($db, $sql) or die (mysqli_error($db));

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>写真確認ページ</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/js/index.js">
    <link rel="stylesheet" href="./assets/css/profile.css">
    <link rel="stylesheet" href="./assets/js/profile.js">
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/js/bootstrap.js">
  </head>
  <body>
    <div class="container auth"  >
        <div id="big-form" class="well auth-box" style="text-aline:center;">
            <fieldset>
                <h1><?php echo h($post['title']);?></h1>
                <h3><?php echo h($post['created']);?>に投稿された写真です</h3>
                <?php echo '<h2>'.$post["name"].'</h2>';?>
                <h3>likes <?php echo h($post['like_sum'])?></h3>
                <img src="pictures/<?php echo h($post['picture'])?>">
                <h2><?php echo h($post['comments']);?></h2>
                <p><?php 
                    while ($re_comment = mysqli_fetch_assoc($sql)) {
                        echo h($re_comment['name']);
                        echo'<br>';
                        echo h($re_comment['comment']);
                        echo'<br>'; 
                    }
                ?></p>
                <form action="" method="post">
                  <p>コメントを書く</p>
                  <input type="textarea" name="re_comment">
                  <input type="submit">
                </form>
                <a href="view.php">一覧にもどる</a>
            </fieldset>
        </div>
    </div>
    <?php require('js_read.html'); ?>
  </body>
</html>
