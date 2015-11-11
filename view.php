<?php
    require('dbconnect.php');
    session_start();
    function h($value){
      return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    
    if(isset($_POST['sort'])){
        if ($_POST['sort'] == 'likes') {
            $sql = 'SELECT m.name, p.* FROM members m, pictures p
                WHERE m.id=p.member_id ORDER BY p.like_sum DESC';  
            $posts = mysqli_query($db,$sql) or die (mysqli_error($db));

        }  elseif ($_POST['sort'] == 'date'){
            $sql = 'SELECT m.name, p.* FROM members m, pictures p
                WHERE m.id=p.member_id ORDER BY p.created DESC';  
            $posts = mysqli_query($db,$sql) or die (mysqli_error($db));
        }
    } else {
        $sql = 'SELECT m.name, p.* FROM members m, pictures p
            WHERE m.id=p.member_id ORDER BY p.created DESC ';  
        $posts = mysqli_query($db,$sql) or die (mysqli_error($db));
    }

    if(isset($_POST['sort_genre'])){
        if($_POST['sort_genre'] == 0){
            $sql ='SELECT m.name, p.* FROM members m, pictures p
                WHERE m.id=p.member_id ORDER BY p.created DESC';  
            $posts = mysqli_query($db,$sql) or die (mysqli_error($db));   
        } else {
            $sql = sprintf(
                    'SELECT m.name, p.* FROM members m, pictures p
                    WHERE m.id=p.member_id AND p.genre_id=%d ORDER BY p.created DESC',
                    $_POST['sort_genre']
                );  
            $posts = mysqli_query($db,$sql) or die (mysqli_error($db));     
        }
    } 


    
?> 

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>みんなの写真がみれるよ</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/js/index.js">
    <link rel="stylesheet" href="./assets/css/profile.css">
    <link rel="stylesheet" href="./assets/js/profile.js">
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/js/bootstrap.js">
  </head>
  <body>
    <div class="container auth"  style="margin-right: 30px;">
    <div id="big-form" class="well auth-box" style="text-aline:center;">
        <fieldset>
            <a href="mypage.php">自分のページにもどる</a>
            <form action="" method="post">
              <h2>並び順の変更</h2>
              <select name="sort">
                <option value="date">投稿が早い順</option>
                <option value="likes">いいねが多い順</option>
              </select>
              <button type="submit">change</button>
            </form>
            <form action="" method="post">
              <h2>ジャンルを選択します。</h2>
              <select name="sort_genre">
                <option value="0">全て</option>
                <option value="1">旅行</option>
                <option value="2">料理</option>
                <option value="3">おもしろ</option>
                <option value="4">趣味</option>
              </select>
              <button type="submit">このジャンルで絞り込む</button>
            </form>
        </feildset>
    </div>
    </div>
  

  <ul>
     <?php while ($post = mysqli_fetch_assoc($posts)): ?> 
        <div class="container auth" >
          <div  class="well auth-box">
            <fieldset>
            <!-- 投稿の表示画面 -->
                <div class="msg" style="float:left;">
                    <li>
                        <p><?php echo h($post['title']);?></p>
                        <image src="pictures/<?php echo h($post['picture']);?>"
                          width="100" height ="100">
                        </image>
                        <p><?php echo h($post['comments']);?></p>
                        <!-- いいねボタン -->
                        [<a href="show.php?id=<?php echo h($post['id'])?>">show</a>]
                        [<a href="likes.php?id=<?php echo h($post['id']);?>">like</a>]
                        <!-- いいね数の表示 -->
                        <p><?php 
                        $sql = sprintf('SELECT COUNT(*) AS cnt from likes WHERE picture_id=%d',
                            $post['id']
                        );
                        $sql = mysqli_query($db,$sql) or die (mysqli_error($db));
                        $cnt = mysqli_fetch_assoc($sql);
                        $update = sprintf('UPDATE pictures SET like_sum = %d WHERE id =%d',
                            $cnt['cnt'],
                            $post['id']
                        );
                        mysqli_query($db,$update) or die (mysqli_error($db));
                        echo $cnt['cnt'];
                        ?></p>
                      <!-- ジャンルの表示 -->
                        <p>ジャンルは</p>
                        <?php
                            if(isset($_SESSION['genre_id'])){
                                $sql = sprintf('SELECT * FROM genres WHERE id=%d',
                                $post['genre_id']);
                                $sql = mysqli_query($db,$sql) or die (mysqli_error($db));
                                $genre = mysqli_fetch_assoc($sql);
                                echo $genre['genre'];
                            }
                        ?>
                        <p><?php echo h($post['name']);?></p>
                        <p class="day">
                          <?php echo $post['created'];?></a>
                        </p>  
                    </li>
                </div>
              </fieldset>
            </div>
          </div>
        <?php endwhile; ?>
    </ul> 
    <?php require('js_read.html'); ?>
  </body>
</html>
