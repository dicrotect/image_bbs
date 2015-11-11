<?php

  require('dbconnect.php');
  session_start();
  function h($value){
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
  }
  $genres = array('','旅行','料理','おもしろ','趣味');
  if(isset($_SESSION['genre_id'])){
    $sql = sprintf('SELECT * FROM genres WHERE id=%d',
      $_SESSION['genre_id']);
    $sql = mysqli_query($db,$sql) or die (mysqli_error($db));
    $genre = mysqli_fetch_assoc($sql);
  }

  if(!empty($_SESSION)){
      if (isset($_POST['action'])) {
          $sql = sprintf (
              'INSERT INTO pictures SET member_id=%d, picture="%s", title="%s",comments="%s", genre_id=%d, created=NOW()',
              mysqli_real_escape_string($db, $_SESSION['id']),
              mysqli_real_escape_string($db, $_SESSION['picture']),
              mysqli_real_escape_string($db, $_SESSION['title']),
              mysqli_real_escape_string($db, $_SESSION['comment']),
              mysqli_real_escape_string($db, $_SESSION['genre_id'])
          );
          mysqli_query($db, $sql) or die (mysqli_error($db));
      }
  }

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>投稿確認画面</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/js/index.js">
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/js/bootstrap.js">
  </head>
  <body>
    <div class="container auth">
      <div id="big-form" class="well auth-box" >
        <fieldset style="text-aline:center;">
          <?php if (isset($_POST['action'])): ?>
             <h1>画像を投稿しました。</h1>
          <?php else :?>
             <h1>この内容でOK?</h1>
          <?php endif; ?>
          <ul>
            <h2>タイトル <?php echo h($_SESSION['title'])?></h2>
            <h3>ジャンル <?php echo h($genres[$_SESSION['genre_id']])?><h3>         
            <p>
              <?php
                echo sprintf('<img src="./pictures/%s". width="300" height="300">',
                $_SESSION['picture']);
              ?>
            </p>
            <br>
            <h2>コメント</h2>
            <h3><?php echo h($_SESSION['comment'])?></h3> 
          </ul>

          <div class="form-group">
            <div class="">
              <form action="" method ="post">
                <ul>
                  <button type="submit hidden" name ="action" id="button1id" value='push' class="btn btn-success">PUSH</button>
                  <bottun>
                    <a href="mypage.php?action=rewrite" type="submit hidden" name ="action" id="button1id" value='push' class="btn btn-success">mypage</a>
                  </button>
                </ul>
              </form>
            </div>
          </div>
          <?php require('js_read.html'); ?>
        </fieldset>
      </div>
    </div>
  </body>
</html>
