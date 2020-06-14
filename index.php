<?php
session_start();
?>

<?php
  $errorMsg = "";
  $usersXML = simplexml_load_file("xmlFiles/users.xml");
  if(isset($_SESSION['userid']) && isset($_SESSION['user'])){
    header('Location: main.php');
  }
//Check the login credentials with the credentials that are present in the users.xml file
  if (isset($_POST['login'])) {
    foreach ($usersXML as $user) {
      //echo md5($_POST['password']);
      if($_POST['username'] == $user->userName && md5($_POST['password']) == $user->authentication->encryptedPW) {
        $_SESSION['user'] = (string) $user->userName;
        $_SESSION['userid'] = (string) $user->userID;
        header('Location: main.php');
      }
      else{
         $errorMsg= "OH..OH..You are not the right person here...!!";
      }
    }    
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

  

  <link rel="stylesheet" href="style.css">
</head>

<body>
  
<h2>MS Customer Service Portal</h2>
<!-- Tabs Titles -->
<div class="wrapper fadeInDown">
  <div id="formContent">
    

    <!-- Icon -->
    <div class="fadeIn first">
     <!-- <img src="http://danielzawadzki.com/codepen/01/icon.svg" id="icon" alt="User Icon" />-->
    </div>
  <form action="" method="POST">
    <div>
      <input type="text" id="login" class="fadeIn second" name="username" placeholder="login">
      <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
      <input type="submit" name="login" class="fadeIn fourth" value="Login"><span><?=$errorMsg?></span>
    </div>
  </form>
</div>
</div>
</body>

</html>