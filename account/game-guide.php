<?php

session_start();
$error = NULL;
$output = NULL;

if (isset($_SESSION['user_id'])) {
	$title = "All FantasyF1 Picks";
	include('includes/header.php');
	include('../../private/connection.php'); ?>
  
  
	<div class=row>
	   <div class="small-12 columns">
	   <h1>Game guide</h1>
     <p>I've made this brief guide about how FantasyF1 works, what you need to do and a couple of quick tips to help you in the right direction.</p>
     <h2>The Game</h2>
     <p>FantasyF1 is a simple game. If you can count to 45, you can play. If you can't count to 45, luckily most web browsers can, so you can still play! Equal opportunities are all the rage here.</p>
     <p>In FantasyF1, every F1 race driver has a value. The better they are, the more they're worth. At the start of the season, I decide how much they're worth based on estimated performance. As the season goes on, their value fluctuates according to their results. Drivers who score more points cost more, and drivers who score fewer points are cheaper. Driver values follow form – only the previous five Grands Prix are taken into account.</p>
     <p>At each race, you have 45 points to spend. With those 45 points, you can pick as many (or few) drivers as you wish. You may as well use all the points up, because you can't carry them forward and they're not useful for anything other than picking your drivers.</p>
     <h2>Scoring</h2>
     <p>In FantasyF1, drivers score just like they do in Formula 1 races. Thus, if Lewis Hamilton wins a race, anyone who picked him scores 25 points. If Jenson Button finishes eighth, anyone who picked him scores 4 points.</p>
     <p>Your total score for a race is the total score of all the drivers you picked. So, if you picked the drivers who finished first, second and third, you would score 25 + 18 + 15 = 58 points. Everyone's scores are then put in a big list and reordered, with whoever scored the most points at the top. If two entrants have the same score, whoever submitted their picks first takes priority. This is the finishing order for the race.</p>
     <p>Points are then awarded according to your finishing position, using the same points system as in Formula 1. So, if you scored the most points, you score 25 points. If you finished eleventh, too bad… These are the points that count towards the FantasyF1 Championship. At the end of the season, whoever has the most points wins the championship!</p>
     <h2>Teams</h2>
     <p>To make the game more fun, people are paired into teams, just like in Formula 1. This means you have two chances to win! You could win the individual Drivers' Championship by yourself, or with your teammate, you could launch an assault on the Constructors' Championship. It's up to you how much data you want to share with your teammate. Where possible, I place people in pairs who know each other. Don't know anyone else who's playing? There's still space for a few more to sign up!</p>
     <h2>Tactics</h2>
     <p>I am the reigning champion, so I'm not going to give away all my best secrets, but I will give you a few pointers:</p>
     <ul>
      <li>Get your picks in early. If you're level on points with someone else, but they picked before you, you lose out!</li>
      <li>If you change your mind, you can resubmit your picks up until the deadline – but you go to the back of the tie-breaker queue! So, before you do it, just ask, is it worth it?</li>
      <li>You want to pick drivers whose values are under-rated. You start with 45 points, but the average number of points required to win is nearly 70. You need to find a big return on that investment!</li>
      <li>It's better to go for glory than consistency. Much better to win once than to keep finishing mid-pack. If you score more than 100 points over the course of the season, you'll probably be in the top ten. If you score 150, you'll probably be in the top three. To win, you need to look closer to 175, or even more.</li>
      <li>Want to set yourself out from the crowd, or on a rival? Spy on their picks. You can't see who anyone's picking for a race until picks are closed, but you can search through everyone's picks for the whole season when you're logged in. Good for spotting people's favourite drivers, but also who they don't rate…</li>
      </ul>
      <h2>(In)Frequently Asked Questions</h2>
      <h3>How do I report a bug, or suggest a feature?</h3>
      <p>Contact me via Twitter (<a href="https://twitter.com/Noelinho">@Noelinho</a>), or by email, or use the <a href="/account/feedback.php">feedback page</a>.</p>
      <h3>Can you spy on my picks / fix it so you win?</h3>
      <p>Well, yes, I could. But I don't. I want to win, but I'm not Nelson Piquet Jr.</p>
      <h3>When is the deadline for picks?</h3>
      <p>The deadline for picks is always midnight GMT on the Thursday night before the race. Yes, even for Monaco.</p>


     
     <?php
  echo "</div>\n</div>\n";   
  include('includes/footer.php');
}
else {
	header ("Location:  http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php");
	exit(); // Quit the script.
}

?>