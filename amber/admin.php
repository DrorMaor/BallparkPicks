<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script>
			function showDiv(div) {
				$('.bigDiv').hide();
				$("#" + div).show();
			}
			
			function generateDelete(div) {
				var del = "delete from emails where id in (";
				del += $("#" + div).val().slice(0, -2) + ");";
				$("#" + div).val(del);
			}
		</script>
		<style>
			.bigDiv {
				display:none;
				border: solid blue 1px;
				border-radius: 5px;
			}
			.spanClick {
				cursor:pointer;
				color:blue;
			}
			.addToDelete {
				color:gray;
				cursor:pointer;
			}
		</style>
	</head>
	<body>
		<?php
			ini_set('display_errors',1);
			ini_set('display_startup_errors',1);
			error_reporting(E_ALL);
			$conn = mysqli_connect('localhost', 'root', 'ladd2carew', 'AmberSoil');
		?>
		
		<span class="spanClick" onclick="showDiv('divExtract');">Extract</span> &nbsp;
		<span class="spanClick" onclick="showDiv('divInitials');">Initials-(RO)</span> &nbsp;
		<span class="spanClick" onclick="showDiv('divLike');">Like</span> &nbsp;
		<span class="spanClick" onclick="showDiv('divJoinTest');">Send-Join</span> &nbsp;
		<br>
		<div id="divExtract" class="bigDiv">
			<form action="admin.php" method="post" name="frmExtract">
				<textarea style="width:600px; height:250px;" name="emails"></textarea> <br>
				<input type="submit" value="Extract" name="submitExtract">
			</form>
			<br>
			<?php
				if (!empty($_POST["submitExtract"]))
				{
					$emails = $_POST["emails"];
					$spaces = str_split('<>,"');
					foreach ($spaces as $char)
						$emails = str_replace($char, ' '.$char.' ', $emails);
					$splitEmails = explode(' ', $emails);
					foreach ($splitEmails as $email)
					{
						$email = strtolower($email);
						if (filter_var($email, FILTER_VALIDATE_EMAIL))
							echo ("INSERT INTO emails (email) SELECT '$email' FROM DUAL WHERE NOT EXISTS (SELECT email FROM emails WHERE email = '$email') LIMIT 1; <br>");
					}
				}
			?>
		</div>
		<br>
		
		<div id="divInitials" class="bigDiv">
			<input type="text" style="width:600px;" id="divInitialsIDs" >
			<br>
			<?php
				for ($a = 65; $a <= 90; $a++)
					echo "<a href='admin.php?initialRO=".chr($a)."'>".chr($a)."</a> &nbsp;";
				echo "<br>";
				if (isset($_GET["initialRO"]))
				{
					$result = $conn->query("select id, email from emails where left(email, 1) = '" . $_GET["initialRO"] . "'");
					while($row = $result->fetch_assoc()) 
					{
						$a = "<a class='addToDelete' onclick=\"$('#divInitialsIDs').val($('#divInitialsIDs').val() + '".$row["id"].", '); "."\">".$row["id"]."</a>";
						echo $row["email"]. "&nbsp;" . $a . " <br>";
					}
				}
			?>
			<br>
			<button onclick="generateDelete('divInitialsIDs');">Generate Delete</button>
		</div>
		<br>
		
		<div id="divLike" class="bigDiv">
			<form action="admin.php" method="post" name="frmLike">
				<input type="text" name="txtLike"> &nbsp;
				<input type="submit" value="Like %%" name="submitLike">
			</form>
			<input type="text" style="width:600px;" id="divLikeIDs" >
			<br>
			<?php
				if (!empty($_POST["submitLike"]))
				{
					$result = $conn->query("select id, email from emails where email like '%" . $_POST["txtLike"] . "%'");
					while($row = $result->fetch_assoc()) 
					{
						$a = "<a class='addToDelete' onclick=\"$('#divLikeIDs').val($('#divLikeIDs').val() + '".$row["id"].", '); "."\">".$row["id"]."</a>";
						echo $row["email"]. "&nbsp;" . $a . " <br>";
					}
				}
			?>
			<br>
			<button onclick="generateDelete('divLikeIDs');">Generate Delete</button>
		</div>

		<div id="divJoinTest" class="bigDiv">
			<?php
				for ($a = 65; $a <= 90; $a++)
					echo "<a href='admin.php?sendJoinInitial=".chr($a)."'>".chr($a)."</a> &nbsp;";
				echo "<br>";
				if (isset($_GET["joinEmail"]))
					echo "good ".$_GET["joinEmail"];
				
				if (isset($_GET["sendJoinInitial"]))
				{
					$sender = "info@ambersoil.com";
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
					$result = $conn->query("select email from emails where left(email, 1) = '".strtolower($_GET['sendJoinInitial'])."';");
					//while($row = $result->fetch_assoc()) 
					
					$offerEmails = array('dror.m.maor@gmail.com', 'binyomineadler@gmail.com');
					foreach ($offerEmails as $offerEmail)
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
										Your privacy is of utmost importance to us, and this is an <span class="bold">opt-in only</span> service.
										<br>
										Just click the link below to join. Otherwise, you will not be hearing from us again.
										<br>
										And of course, you may unsubscribe at any time, and your email will be <span class="bold">permanently deleted</span> from the list.
										<br><br>
										Thank you very much,<br>
										The Amber Soil team
									</div>
									<br>
									<a id="submit" target="_blank" href="http://www.ambersoil.com/opt-in.php?email='.$offerEmail.'">Yes, I\'m interested!</a>
								</body>
							</html>
						';
						mail ($offerEmail, $subject, $message, $headers, "-r$sender") ;   // $row["email"]
						echo $offerEmail."<br>";
						$counter ++;
					}
					echo $counter;
				}
			?>
		</div>
		<br>
		
		<?php 
			$conn->close();
		?>
	</body>
</html>




