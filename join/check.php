<?php
    session_start();
    require('../dbconnect.php');
    function h($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    
    //データのインサートDB membersへ
    if (!empty($_POST)) {
        $sql = sprintf(
            'INSERT INTO members SET name="%s", email="%s", password="%s", profile="%s", image="%s", created=NOW()', 
            mysqli_real_escape_string($db, $_SESSION['member']['name']),
            mysqli_real_escape_string($db, $_SESSION['member']['email']),
            //sha1() ()内を暗号化してパスワードの安全性を保つ
            mysqli_real_escape_string($db, sha1($_SESSION['member']['password'])),
            mysqli_real_escape_string($db, $_SESSION['member']['profile']),
            mysqli_real_escape_string($db, $_SESSION['image'])
        );

        mysqli_query($db,$sql) or die(mysqli_error($db));
        var_dump($sql);
        unset($_SESSION['member']);

        header('Location: ../login.php');
        exit();
    }

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>please check your infomations</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/js/index.js">
  </head>
  <body>
    <div class="container auth">
      <div id="big-form" class="well auth-box">
        <fieldset>
          <legend>Please join our service</legend>
            <form action="" method ="post" >   
              <input type="hidden" name="action" value="submit /">
              <ui>
                <ul><?php echo h($_SESSION['member']['name']);?></ul>
                <ul><?php echo h($_SESSION['member']['email']);?></ul>
                <ul><?php echo h($_SESSION['member']['password']);?></ul>
                <ul><?php echo h($_SESSION['member']['profile']);?></ul>
              </ui>
              <div style="text-align : center ;">
                <?php
                  //頭にスラッシュがあると相対パスにならない
                  //同じ階層を指定したい場合は/の前に.をつける
                  //ファイル名の後ろには/なし⇨階層だと認識される。
                    echo sprintf('<img src="./pro_image/%s". width="100" height="100">',
                    $_SESSION['image']);
                ?>
                <br>
                <a href="index.php?action=rewrite">&laquo;&nbsp; 書き直す</a> 
                <input type="submit" value="登録する">
              </div>
            </form>
          <?php require('../js_read.html'); ?>
        </fieldset>
      </div>
    </div>
  </body>
</html>
