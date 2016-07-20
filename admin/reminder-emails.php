<?php

include '../../private/connection.php';
$num = 0;

$sql = $dbh->prepare ("SELECT id AS user_id, username, email_address, (SELECT grand_prix_name FROM `view_ff1allraces` WHERE status = '3') as 'grand_prix' FROM fantasyusers WHERE registered = 1 AND username NOT IN (SELECT DISTINCT username FROM `view_ff1allfantasypicks` WHERE status = '3') ORDER BY username ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
  require '../includes/class.phpmailer.php';
  require '../includes/class.smtp.php';
    $mail = new PHPMailer;
  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;
  $mail->isHTML(true);
  // These variables are set in the external connection file
  $mail->Host = $ff1mailhost;
  $mail->Username = $ff1mailusername;
  $mail->Password = $ff1mailpassword;
  $mail->setFrom($ff1fromaddress, $ff1fromname);
  $mail->Subject = 'REMINDER: Make your FantasyF1 picks!';
  foreach ($row as $result) {
    $mail->clearAllRecipients();
    $mail->addAddress($result->email_address); 
    $body = file_get_contents('picks-reminder-email.html');
    $body = str_replace("[[Username]]", $result->username, $body);
    $body = str_replace("[[Grand Prix]]", $result->grand_prix, $body);
    $mail->Body = $body;
  if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
  }
  else {
    $now = time();
    $sql = $dbh->prepare("INSERT INTO messagelog (fantasyusers_id, messages_id, timestamp) VALUES (:fantasyuser, :messageid, :timestamp)");
    $sql->execute(array(':fantasyuser' => $result->user_id, ':messageid' => "4", ':timestamp' => $now));
    $num++;
    }
  }
}
echo "<p>$num reminders emailed.</p>";


?>