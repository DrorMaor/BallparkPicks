<html>
<body>
<div style="font-family:arial; color:#181B5E; font-size:16px; line-height: 1.33;">
	<?php
		if (isset($_GET["email"]))
		{
			include "crypto.php";
			$email = decrypt($_GET["email"]);
			$conn = new mysqli('localhost', 'root', 'ladd2carew', 'AmberSoil');
			if ($conn->connect_error)
				die("Connection failed: " . $conn->connect_error);

			$sql = "insert into OptIn (email) values ('".$email."');";
			if ($conn->query($sql) === TRUE)
				echo $email . " has successfully subscribed. <br> Thank you very much, <br>The Amber soil team";
			else
				echo "There was a problem. Please try again";
			$conn->close();
		}
	?>
</div>
</body>
</html>