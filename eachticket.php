<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
if(!isset($_SESSION['userid']) || !isset($_SESSION['user'])){
  header('Location: login.php');
}

$usersXML = simplexml_load_file("xmlFiles/users.xml");
$ticketDetails;
$userDetails;
$admin = false;
if (isset($_SESSION['userid'])) {
  foreach ($usersXML as $user) {
    if ($_SESSION['userid'] == $user->userID) {
      $userDetails = $user;
      foreach($userDetails->attributes() as $key => $value){
        if($value == 'admin' || $value == 'support') 
          $admin = true;
      }
      $ticketsXML = simplexml_load_file("xmlFiles/tickets.xml");
      foreach ($ticketsXML as $ticket) {
        if ($_GET['ticketID'] == $ticket->ticketID) {
          $ticketDetails = $ticket;
          if(isset($_POST['addMessage'])) {
            $newItem = $ticket->messages->addChild("message");
            $newItem->addChild("userID", $_SESSION['userid']);
            $newItem->addChild("messageDateTime", date("M,d,Y h:i:s A"));
            $newItem->addChild("message", $_POST['message']);
            $ticketsXML->saveXML("xmlFiles/tickets.xml");
          }
        }
      }
    }
  }
} else {
  header('Location: login.php');
}

function getUsername($userID)
{
  global $usersXML;
  $userName = "";
  foreach ($usersXML as $user) {
    if ((string) $userID == (string) $user->userID) {
      $userName = $user->userName;
    }
  }
  return $userName;
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Tickets | <?= $ticketDetails->tcktName; ?></title>
 <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container">
    <h1>Ticket Details</h1>
    <a href="logout.php" input="button" class="btn btn-primary">Logout</a>
    <a href="main.php" class="btn btn-primary">Back</a>
    <div class="tickets-container">
      <div class="ticket ticket-<?= $ticketDetails->tcktStatus; ?>">
        <h2><?= $ticketDetails->tcktName; ?></h2>
        <p>Date: <?= $ticketDetails->date; ?></p>
        <p class="badge"><?= $ticketDetails->priority; ?> Priority</p>
        <p class="badge"><?= $ticketDetails->tcktStatus; ?></p>
        <p><?= $ticketDetails->tcktDesc; ?></p>
      </div>
      <?php
      foreach ($ticketDetails->messages->message as $message) {
      ?>
        <div class="message">
          <?= getUsername($message->userID)." (".$message->messageDateTime."):"; ?>
          <?= "<p>".$message->message."</p>"; ?>
        </div>
      <?php
      }
      ?>
      <?php 
        if(((string) $ticketDetails->userID == (string) $userDetails->userID) || $admin) {
      ?>
      <div>
        <form action="" method="POST">
          <textarea class="message-box" name="message" required></textarea>
          <input type="submit" class="btn btn-primary" name="addMessage" value="Post">
        </form>
      </div>
      <?php
      }
      ?>
    </div>
  </div>

</body>

</html>