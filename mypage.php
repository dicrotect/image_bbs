<?php

    //個人ページ
    //画像の投稿
    //自分の投稿した画像の表示
    require('dbconnect.php');
    session_start();
    function h($value){
      return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    //ログインidかどうかを確認
    if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
        $_SESSION['time'] = time();
        //プロフィール画面の読み込み
        $sql = sprintf('SELECT * from members WHERE id=%d',
              mysqli_real_escape_string($db,$_SESSION['id']));
        $record = mysqli_query($db,$sql) or die (mysqli_error($db));
        $pro_info = mysqli_fetch_assoc($record);
        
        // ***** 変数化 ***** //
        $_SESSION['member_id'] = $pro_info['id'];
        $_SESSION['name'] = $pro_info['name'];
        $_SESSION['profile'] = $pro_info['profile'];
        $_SESSION['image'] = $pro_info['image'];

    } else {
        header('Location:login.php');
        exit();
    }

    //ユーザーの画像投稿があるときにPOSTでデータを受け取ったものをSESSIONに代入
    if(!empty($_POST)) {
        $_SESSION['title'] = $_POST['title'];
        $_SESSION['comment'] = $_POST['comment'];
        if (isset($_POST['genre'])) {
            $_SESSION['genre_id'] = $_POST['genre'];
        }
        $fileName = $_FILES['picture']['name'];
        if (!empty($_FILES['picture'])){
            $ext = substr($fileName, -3);
            if ($ext != 'jpg' && $ext != 'gif'&& $ext != 'png'){
                $error['picture'] = 'type';
            }
        }
        //画像のアップロード
        if (empty($error)) {
            if($fileName != "" ) {
                $picture = $fileName;  
                move_uploaded_file($_FILES['picture']['tmp_name'],'pictures/'.$picture);  
             }
             $_SESSION['picture'] = $picture;
        }
        header('Location:post.php');
        exit();
    }

    //ページング
    if (empty($_REQUEST['page'])) {
        $page = 1 ;
    } else {
        $page = $_REQUEST['page']; 
    }
    //いま何ページにいるか
    $page = max($page , 1);
  
    $sql = 'SELECT COUNT(*) AS cnt FROM pictures';
    $recordSet = mysqli_query($db, $sql);
    $table = mysqli_fetch_assoc($recordSet);
    //テーブルに入っているデータの個数を1ページあたりに表示する個数(5)
    //で割ることでテーブルのデータをすべて表示するためには何ページ必要か割だす
    $maxPage = ceil($table['cnt']/5);
    $page = min($page,$maxPage);

    //何番目の投稿から5個表示するのか決める
    $start = ($page -1)*5;
    $start = max(0, $start);
    
    //画像の読み込み
    //自分(SESSION[id])の投稿した投稿のみデータベースからインサート
    $sql = sprintf('SELECT * from pictures WHERE member_id=%d ORDER BY modified DESC LIMIT %d, 5',
      mysqli_real_escape_string($db,$_SESSION['id']),
      $start
    );
    $record = mysqli_query($db,$sql) or die (mysqli_error($db));
      //$posts = mysqli_fetch_assoc($record);
      //var_dump($posts);

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?php echo h($_SESSION['name']);?>さんようこそ</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/js/index.js">
    <link rel="stylesheet" href="./assets/css/profile.css">
    <link rel="stylesheet" href="./assets/js/profile.js">
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/js/bootstrap.js">
  </head>
  <body>        
    <div>
      <!-- リンクの作成 -->
      <a href="">プロフィールを編集する</a>
      <a href="view.php">みんなの写真をみてみよう！</a>
      <a href="logout.php">ログアウトする</a>
    </div>
    <!-- プロフィールとPOSTページの作成 -->
    <div clss="container">
      <div class="row" style="margin-top:30px" >
        <div class="col-xs-offset-4 col-xs-4">
          <div class="card">
              <canvas class="header-bg" width="250" height="70" id="header-blur"></canvas>
              <div class="avatar">
                <?php
                    echo sprintf('<img src="./join/pro_image/%s". width="100" height="100" >',
                    h($_SESSION['image']));
                ?>
              </div>
              <div class="content">
                <p class="control-label" for="selectbasic"><?php echo h($_SESSION['profile']);?></p>    
                <p><button type="button" class="btn btn-default"><?php echo h($_SESSION['name']);?></button></p>
              </div>
          </div>
        </div>
      </div>
    <!-- 写真の投稿 -->
      <div class="container auth" style="margin-right: 30px;">
        <div id="big-form" class="well auth-box">
          <fieldset>
            <h2><label class=" control-label" for="selectbasic">Push Your Picture</label></h2>
            <form action="" method="post" enctype="multipart/form-data">
              <label class=" control-label" for="selectbasic">Picture</label><br>
              <input type="file" name="picture" ><br>
              <label class=" control-label" for="selectbasic">Title</label><br>
              <input type="text" name="title"  id="textinput" class="form-control input-md"><br>
              <label class=" control-label" for="selectbasic">Coment</label><br>
              <input type="text" name="comment" id="textinput" class="form-control input-md"><br>         
              <div class="form-group">
                <label class=" control-label" for="selectbasic">Choose Jenre</label>
                <div class="">
                  <select name="genre" class="form-control">
                    <option value="1">旅行</option>
                    <option value="2">料理</option>
                    <option value="3">おもしろ</option>
                    <option value="4">趣味</option>
                  </select>
                </div>
              </div>
              <input type="submit" >
            </form>
          </fieldset>
        </div>
      </div>
    </div>  
    <!-- 自分の投稿した画像の表示 --> 
    
    <ul>
      <?php while ($post = mysqli_fetch_assoc($record)): ?> 
        <div class="container"  style="margin-left: 15px;">
          <div class="container auth"  "col-xs-3" >
            <div class="well auth-box">
              <li>
                <div class="msg">
                  <p><?php echo h($post['title']);?></p>
                  <image src="pictures/<?php echo h($post['picture']);?>"
                    width="100" height ="100">
                  </image>
                  <p><?php echo h($post['comments']);?></p>
                  <p class="day">
                    <?php echo $post['created'];?></a>
                  </p>
                  <!-- デリートボタン -->
                  [<a href="delete_check.php?id=<?php echo h($post['id']); ?>" style="color: #F33;">削除</a>]
                </div>
              </li>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </ul>
      
    <ul class="paging" >
      <!-- ページング -->
      <?php
        if($page > 1){
      ?>
      <li><a href="mypage.php?page=<?php print($page -1);?>">前のページへ</a></li>
      <?php } else { ?> 
      <li>前のページへ</li>
      <?php } ?> 
      <?php if($page < $maxPage) { ?>
      <li><a href="mypage.php?page=<?php print($page +1);?>">次のページへ</a></li>
      <?php } else { ?>
      <li>次のページへ</li>
      <?php } ?> 
    </ul>
    <?php require('js_read.html'); ?>
  </body>
</html>
