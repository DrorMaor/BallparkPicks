<div>
	<table border="1">
		<tr>
			<th>id</th>
			<th>IP</th>
			<th>theTime</th>
			<th>referer</th>
			<th>URL</th>
		</tr>
<?php
	include ("../DbConn.php");
	$traffic = "select * from traffic order by id desc limit 50;";
	$results = $conn->query($traffic) or die($conn->error);
	while ($row = $results->fetch_assoc())
	{
		echo "<tr>";
		echo "<td>".$row["id"]."</td>";
		echo "<td>".$row["IP"]."</td>";
		echo "<td>".$row["theTime"]."</td>";
		echo "<td>".$row["referer"]."</td>";
		echo "<td>".$row["URL"]."</td>";
		echo "</tr>";
	}
	$conn->close();
?>
	</table>
</div>

<hr>
