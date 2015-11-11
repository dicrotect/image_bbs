<?php
//会員登録画面の作成
    session_start();
    function h($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    
    //エラー処理 からの時を弾く
    if (!empty($_POST)) {
        if ($_POST['name'] == ""){
            $error['name'] = "blanck";
        }  
        if ($_POST['email'] == ""){
            $error['email'] = "blanck";
        }  
        if ($_POST['password'] == ""){
            $error['password'] = "blanck";
        }  
        
    
        //ファイルのエラー判定

        $fileName = $_FILES['image']['name'];
        if (!empty($_FILES['image'])){
            $ext = substr($fileName, -3);
            if ($ext != 'jpg' && $ext != 'gif'&& $ext != 'png'){
                $error['image'] = 'type';
            }
        }


        //プロフィール写真のアップロード
        if (empty($error)) {
            if($fileName != "" ) {
                $image = $fileName;  
                move_uploaded_file($_FILES['image']['tmp_name'],'pro_image/'.$image);  
            }
        }
        if(empty($error)){
            $_SESSION['member'] = $_POST ;
            $_SESSION['image'] = $image;
            header('Location:check.php');
            exit();

        }
    }
    
       


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>join our paradise</title>
  <link rel="stylesheet" href="../assets/css/index.css">
  <link rel="stylesheet" href="../assets/js/index.js">
</head>
<body>
  <div class="container auth">
    <div id="big-form" class="well auth-box">
        <fieldset>
          <!-- Form Name -->
          <legend>Please join our service</legend>
          <form action="" method="post" enctype="multipart/form-data">
            <div>
              <ui>        
                <!-- 名前の登録 -->
                <div class="form-group">
                  <label class=" control-label" for="textinput">Username</label>  
                  <div class="">
                    <ul>
                        <?php
                            if (isset($_POST["name"])) {
                                echo sprintf('<input type="text" name="name" value="%s" id="textinput" class="form-control input-md">',
                                h($_POST["name"]));
                            } else {
                                echo '<input id="textinput" name="name" placeholder="Username" class="form-control input-md" type="text">';
                            }
                        ?>
                    </ul>
                        <?php if (isset($error['name'])): ?>
                         <ul>名前が未入力です</ul>
                       <?php endif; ?>
                  </div>
                </div>
                <!-- メールアドレスの入力 -->
                <div class="form-group">
                  <label class=" control-label" for="textinput">E-mail</label>  
                  <div class="">      
                    <ul>
                        <?php
                          if (isset($_POST["email"])) {
                              echo sprintf('<input type="text" name="email" value="%s" id="textinput" class="form-control input-md">',
                              h($_POST["name"]));
                          } else {
                              echo '<input type="text" name="email" input id="textinput"  placeholder="E-mail" class="form-control input-md">';
                          }
                        ?>        
                    </ul>
                    <?php if (isset($error['password'])): ?>
                      <ul>メールアドレスが入力されていません</ul>
                    <?php endif; ?>
                  </div>
                </div>
        
                <!-- パスワードの入力 -->
                <div class="form-group">
                  <label class=" control-label" for="passwordinput">Password</label>
                  <div class="">        
                    <ul>
                        <?php
                          if (isset($_POST["password"])) {
                              echo sprintf('<input type="password" name="password" value="%s" id="passwordinput" class="form-control input-md">',
                              h($_POST["name"]));
                          } else {
                              echo '<input type="password" name="password" placeholder="Password" id="passwordinput" class="form-control input-md">';
                          }
                        ?>
                    </ul>
                    <?php if (isset($error['password'])): ?>
                        <ul>パスワードが入力されていません</ul>
                    <?php endif; ?>
                  </div>
                </div>
              
                <!-- プロフィールの登録 -->
                <div class="form-group">
                  <label class=" control-label" for="textarea">Comment</label>
                  <div class="">                     
                    <ul><textarea class="form-control" id="textarea" name="profile">comments</textarea></ul>
                    <?php if (isset($error['profile'])): ?>
                        <ul>プロフィール画像を登録しましょう</ul>
                    <?php endif; ?>     
                  </div>
                </div>
                
                <!-- 画像送信 -->
                <div class="form-group">
                  <label class=" control-label" for="filebutton">Avatar</label>
                  <div class="">
                    <ul>
                        <?php
                          if (isset($_POST["image"])) {
                              echo sprintf('<input type="file" name="image" value="%s" id="filebutton"  class="input-file">',
                              h($_POST["image"]));
                          } else {
                              echo '<input type="file" name="image" id="filebutton"  class="input-file">';
                          }
                        ?>            
                    </ul>
                    <?php if (isset($error['image'])): ?>
                        <ul>プロフィール画像を登録しましょう</ul>
                    <?php endif; ?>
                  </div>
              </div>
              
              <!-- 送信ボタン -->
              <div class="form-group">
                <label class=" control-label" for="button1id"> Push your infomation</label>
                <div class="">
                  <ul><button type="submit" id="button1id" value='push' class="btn btn-success">PUSH</button></ul>
                </div>
              </div>
            </ui>
          </div>
        </form>
      </fieldset>
    </div>
  </div>
  <?php require('../js_read.html'); ?>
  </body>
</html>
