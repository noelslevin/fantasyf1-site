<?php

session_start();
$error = NULL;
$output = NULL;
$nextyear = NULL;
$bugs = NULL;
$features = NULL;
$user = $_SESSION['user_id'];

if (isset($_SESSION['user_id'])) {
  $title = "All FantasyF1 Picks";
  include('includes/header.php');
  include('../../private/connection.php');

  if (isset($_POST['feedback-submit'])) {
    $bugs = htmlspecialchars(strip_tags($_POST['bugs']));
    $features = htmlspecialchars(strip_tags($_POST['features']));
    $nextyear = htmlspecialchars(strip_tags($_POST['playnextyear']));
    $sql = $dbh->prepare("UPDATE fantasyusers SET bugs=?, features=?, nextseason=? WHERE id=?");
    $sql->execute(array($bugs, $features, $nextyear, $user));
    if ($sql->rowCount() == 1) {
      $output .= "<div data-alert class=\"alert-box success radius\">\n";
      $output .= "<p>Thank you for submitting your feedback.</p>\n";
      $output .= "</div>\n";
    }
    else {
      $output .= "<div data-alert class=\"alert-box alert radius\">\n";
      $output .= "<p>Something went wrong. Unable to save data. This may be because the information you submitted is the same as the data already stored.</p>\n";
      $output .= "</div>\n";
    }
  }
  else {
    // Check database for values
    $sql = $dbh->prepare("SELECT bugs, features, nextseason FROM fantasyusers WHERE id = :userid");
    $sql->execute(array(':userid' => $user));
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    
    if ($sql->rowCount() == 1) {
      $bugs = $row['bugs'];
      $features = $row['features'];
      $nextyear = $row['nextseason'];
    }
    else {
      $output .= "<div data-alert class=\"alert-box alert radius\">\n";
      $output .= "<p>Something went wrong. Unable to retrieve data.</p>\n";
      $output .= "</div>\n";
    }
  }

?>

<style>
  input[type="radio"] {
    margin-right: 5px;
  }
  .feedback-text {
    margin-bottom: 20px;
    min-height: 100px;
    resize: vertical;
  }
  .no-gap {
    margin-bottom: 0;
  }
  .alert-box p {
    margin-bottom: 0;
  }
</style>

	<div class=row>
	   <div class="small-12 columns">
	   <h1>User feedback</h1>
         
         <?php if ($output != NULL) { echo $output; } ?>
         
         <p>As it's the end of the season, I'd like your feedback about the site, what works well, what doesn't work so well, and any extra features you'd like to see.</p>
         <div data-alert class="alert-box info radius">
         <p>When you have submitted data, it will display in the fields below whenever you access this page, so you can add to, edit or remove information from your submission by editing the content of the boxes.</p>
         </div>
         <p>Please fill in the form below, to help me improve FantasyF1 for next season!</p>
         <form name="feedback-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
           <p class="no-gap">Is there anything on the site that doesn't work properly?</p>
           <textarea name="bugs" class="feedback-text"><?php if (isset($bugs)) { echo htmlspecialchars_decode($bugs); } ?></textarea>
           <p class="no-gap">Are there any new features you would like to see added?</p>
           <textarea name="features" class="feedback-text"><?php if (isset($features)) { echo htmlspecialchars_decode($features); } ?></textarea>
           <p>Are you interested in playing again next year?</p>
           <input type="radio" name="playnextyear" value="Yes" required <?php if (htmlspecialchars_decode($nextyear) == "Yes") { echo "checked"; } ?>>Yes<br/>
           <input type="radio" name="playnextyear" value="No" <?php if (htmlspecialchars_decode($nextyear) == "No") { echo "checked"; } ?>>No<br/>
           <input type="radio" name="playnextyear" value="Undecided" <?php if (htmlspecialchars_decode($nextyear) == "Undecided") { echo "checked"; } ?>/>I'm not sure<br/>
           <input type="submit" name="feedback-submit" value="Submit">
         </form>
      </div>
    </div>
    
	<?php include('includes/footer.php');
}
else {
	header ("Location:  http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php");
	exit(); // Quit the script.
}

?>