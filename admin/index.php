<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	session_start();
	if (!isset($_SESSION["loggedIn"]))
		$_SESSION["loggedIn"] = "0";
?>

<html>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	</head>
	<script>
		$(function(){
			$(".datepicker").datepicker({
				dateFormat: "yy-mm-dd"
				});
			});
	</script>
	<body>
<?php
	echo "<b>Today is " . date("Y-m-d") . "</b> <br> </br>";
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
		include("MLB.php");
		include("NHL.php");
		include("NFL.php");
		include("AddActualScores.php");
	}

	$conn->close();
?>

</body>
</html>
