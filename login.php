<?php
require('dbconnect.php');
session_start();
  
  if (isset($_COOKIE['email']) != '') {
    $_POST['email'] = $_COOKIE['email'];
    $_POST['password'] = $_COOKIE['password'];
    $_POST['save'] = 'on';
  }


if(!empty($_POST)){
    if($_POST['email'] != '' && $_POST['password'] != ''){
        $sql = sprintf(
        'SELECT * FROM members WHERE email = "%s" AND password = "%s"',
        mysqli_real_escape_string($db, $_POST['email']),
        mysqli_real_escape_string($db, sha1($_POST['password']))
        );
      $record = mysqli_query($db, $sql) or die (mysqli_error($db));
      if ($table = mysqli_fetch_assoc($record)){
        $_SESSION['id'] = $table['id'];
        $_SESSION['time'] = time();


      if ($_POST['save'] == 'on') {
        setcookie('email', $_POST['email'], time()+60*60*24*14);
        setcookie('password', $_POST['password'], time()+60*60*24*14);
      }



        header('Location: mypage.php');
        exit();
      } else {
          $error['login'] = 'failed';
      } 
      } else {
         $error['login'] = 'blanck';
      }
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>こちらの画面からログインしてください</title>
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/login.css">
    <link rel="stylesheet" href="./assete/js/login.js"> 
    <link rel="stylesheet" href="./assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="./assete/js/bootstrap.js"> 
    <link rel="stylesheet" href="./assete/css/bootstrap.css"> 
</head>
<body>

  <div class="container">
    <div class="card card-container">
      <p>&raquo;<a href="join/index.php">create new account</a></p>
        <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
      <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
      <p id="profile-name" class="profile-name-card"></p>
      <div>
      </div>
      <form action="" method="post" class="form-signin">
        <dl>
          <dt>E-mail</dt>
          <dd>
            <span id="reauth-email" class="reauth-email"></span>
            <?php
                if (isset($_POST['email'])) {
                    echo sprintf('<input type="text" name="email" id="inputEmail" value="%s" class="form-control" placeholder="User Name" required autofocus>',
                    htmlspecialchars($_POST["email"], ENT_QUOTES,'UTF-8')
                   );
                } else {
                    echo '<input type="text" name="email">';
                }
            ?>
            <?php if (isset($error["login"])): ?>
                <?php if($error["login"] == 'blanck'): ?>
                    <p class="error">*メールアドレスとパスワードを正しく入力してください。</p>
                <?php  endif; ?>    
                <?php if($error['login'] == 'failed'): ?>
                    <p class="error">*ログインに失敗しました、正しくご記入ください</p>
                <?php endif; ?>
            <?php endif; ?>
          </dd>
          <dt>Password</dt>
          <dd>
           <?php
                if (isset($_POST['password'])) {
                    echo sprintf('<input type="text" name="password" value="%s" id="inputPassword" class="form-control" placeholder="Password" required>',
                    htmlspecialchars($_POST["password"], ENT_QUOTES,'UTF-8')
                   );
                } else {
                    echo '<input type="text" name="password">';
                }
            ?>
          </dd>    
          <div id="remember" class="checkbox">
            <label>
              <input id="save" type="checkbox" name="save" value="on" > Always login
            </label>
          </div>
          <button class="btn btn-lg btn-primary btn-block btn-signin"  type="submit">Let's Go!</button>
        </dl>
      </form>       
    </div><!-- /card-container -->
  </div><!-- /container -->

</body>
</html>
