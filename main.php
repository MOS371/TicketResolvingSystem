<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
  $usersXML = simplexml_load_file("xmlFiles/users.xml");
  $tickets = [];
  if(!isset($_SESSION['userid']) || !isset($_SESSION['user'])){
    header('Location: login.php');
  }

  if (isset($_SESSION['userid'])) {
    foreach ($usersXML as $user) {
      if($_SESSION['userid'] == $user->userID) {
        $admin = false;
        foreach($user->attributes() as $key => $value){
          if($value == 'admin') 
            $admin = true;
        }
        $ticketsXML = simplexml_load_file("xmlFiles/tickets.xml");
        foreach ($ticketsXML as $ticket) {
          if(($_SESSION['userid'] == $ticket->userID || $admin)) {
            array_push($tickets, $ticket);  
          }
        }
        if (isset($_POST['updateStatus'])) {
          foreach ($ticketsXML as $ticket) {
            if($_POST['ticketID'] == $ticket->ticketID) {
              $ticket->tcktStatus = $_POST['tcktStatus'];
              $ticketsXML->saveXML("xmlFiles/tickets.xml");  
              break;
            }
          }
        }
      }
    }
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
  <title>Tickets | <?= $_SESSION['user']; ?></title>
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container">
  <h1>All the available tickets</h1>
  <a href="logout.php" class="btn btn-primary">Logout</a>
  <div class="tickets-container">
    <?php
      foreach($tickets as $ticket) {
    ?>
    <a href="eachticket.php?ticketID=<?= $ticket->ticketID; ?>">
    <div class="ticket ticket-<?= $ticket->tcktStatus; ?>">
        <h2><?= $ticket->tcktName; ?></h2>
        <?php if($admin) { ?> <p>User: <?= getUsername($ticket->userID); ?></p> <?php } ?>
        <p>Date: <?= $ticket->date; ?></p>
        <p class="badge"><?= $ticket->priority; ?> Priority</p>
        
        <?php if($admin) { ?> 
            
          <form action="" method="POST">
            <input type="hidden" name="ticketID" value="<?= $ticket->ticketID; ?>">
            <select name="tcktStatus" onclick="return false;">
              <option value="Resolved" <?= $ticket->tcktStatus == "Resolved" ? "selected" :"" ?>>Resolved</option>
              <option value="Pending" <?= $ticket->tcktStatus == "Pending" ? "selected" :"" ?>>Pending</option>
              <option value="On-going" <?= $ticket->tcktStatus == "On-going" ? "selected" :"" ?>>On-going</option>
            </select>
            <input type="submit" value="Update Status" name="updateStatus" onclick="this.parent.submit();">
          </form>
        <?php } else { ?>
          <p class="badge"><?= $ticket->tcktStatus; ?></p>
        <?php } ?>
        <p><?= $ticket->tcktDesc; ?></p>
    </div>
    </a>
    <?php
      }
    ?>
  </div>
  </div>

</body>

</html>