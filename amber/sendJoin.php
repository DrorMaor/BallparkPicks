<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL);

	$test = 1;
	
	for ($a = 75; $a <= 90; $a++)  // we start with K, since we already did thru J
		echo "<a href='sendJoin.php?sendJoinInitial=".chr($a)."'>".chr($a)."</a> &nbsp;";
	echo "<br>";
		
	if (isset($_GET["sendJoinInitial"]))
	{
		include "crypto.php";
		
		$sender = "opt-in@ambersoil.com";
		$headers = "From: Amber Soil <$sender>\n";
		//$headers .= "Cc: testsite <mail@testsite.com>\n"; 
		$headers .= "X-Sender: Amber Soil <$sender>\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		//$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: $sender\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		$subject = "Offers and Deals From the Orthodox Community";
			
		$counter = 0;
		$conn = mysqli_connect('localhost', 'root', 'ladd2carew', 'AmberSoil');
		if ($test == 0)
			$result = $conn->query("select email from emails where left(email, 1) = '".strtolower($_GET['sendJoinInitial'])."';");
		else
			$result = $conn->query("select 'dror.m.maor@gmail.com' email;");
		while($row = $result->fetch_assoc()) 
		{
			$message = '
				<html>
					<head>
						<style>
							.bold {
								font-weight:bold;
								background-color: #E0E1E1;
							}
							#submit {
								color:#06035e;
								background-color: #e0eeff;
								border: 1px solid blue;
								border-radius:5px;
								cursor:pointer;
								font-size:18px;
								padding:8px;
								font-family:arial;
								text-decoration: none !important;
							}
							#submit:hover {
								opacity:0.75;
							}
							#div {
								font-family:arial; 
								color:#181B5E; 
								font-size:16px;
								line-height: 1.33;
							}
						</style>
					</head>
					<body>
						<div id="div">
							Hello,<br>
							We wanted to know if you were interested in receiving periodic offers and deals from the Orthodox Jewish Community.
							<br><br>
							Your privacy is of utmost importance to us, so this is an <span class="bold">opt-in only</span> service.
							<br>
							Just click the link below to join. Otherwise, you will never hear from us again.
							<br>
							And of course, you may unsubscribe at any time, and your email will be <span class="bold">permanently deleted</span> from the list.
							<br><br>
							Thank you very much,<br>
							The Amber Soil team
						</div>
						<br>
						<a id="submit" target="_blank" href="http://www.ambersoil.com/opt-in.php?email='. encrypt($row["email"]) .'">Yes, I\'m interested!</a>
					</body>
				</html>
			';
			mail ($row["email"], $subject, $message, $headers);
			$counter ++;
		}
		$conn->close();
		echo $counter;
	}
?>



