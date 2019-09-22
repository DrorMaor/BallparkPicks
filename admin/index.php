<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	session_start();
	if (!isset($_SESSION["loggedIn"]))
		$_SESSION["loggedIn"] = "0";
?>

<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	</head>
	<body>

<?php
	include "../DbConn.php";
	if (isset($_POST["submitLogin"]))
	{
		$sql = "select count(*) as loggedIn from users where user = '" . $_POST["user"] . "' and pwd = md5('" . $_POST["pwd"] . "');";
		$results = $conn->query($sql);
		$row = $results->fetch_assoc();
		$_SESSION["loggedIn"] = $row["loggedIn"];
	}

	if ($_SESSION["loggedIn"] == "0")
	{
		?>
			<form action="" method="post" name="frmLogin">
				<input type="text" name="user" > <br>
				<input type="password" name="pwd" > <br>
				<input type="submit" value="Login" name="submitLogin">
			</form>	
		<?php
	}
	else
	{
		// GENERATE MLB PICKS -->
		include("MLB.php");
		include("NFL.php");
		include("AddActualScores.php");
		$conn->close();
	}
?>

</body>
</html>