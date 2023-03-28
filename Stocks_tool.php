<!DOCTYPE html>
<html>
<head>
	<title>STocks Volume DATA</title>
	
	<style>
	.container {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		height: 40vh;
	}
	table, th, td {
		border: 2px solid black;
		border-collapse: collapse;
		padding: 3px;
	}
	th{
		background-color: red
	}
	body{background-color: lightyellow}
	</style>
</head>

<body>
	<div class="container">
	<?php
		// Check if form is submitted
		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			// Get the input value from the form
			$input_value = $_POST["symbol"];

			// Connect to MySQL database
			$servername = "localhost";
			$username = "sharemarket_user";
			$password = "Akagak12@";
			$dbname = "sharemarket";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			// Prepare SQL query
			$sql = "SELECT * FROM volumedata WHERE Symbol = '$input_value'";

			// Execute SQL query
			$result = $conn->query($sql);

			// Display query results
			if ($result->num_rows > 0) {
			// Output table headers
				echo "<table><tr><th>Date</th><th>Symbol</th><th>OPEN</th><th>HIGH</th><th>LOW</th><th>CLOSE</th><th>Volume</th><th>Delivery</th><th>Delivery-percentage</th></tr>";
			
				// Output table rows
				while($row = $result->fetch_assoc()) {
					echo "<tr>";
					echo "<td>" . $row["Date"] . "</td>";
					echo "<td>" . $row["Symbol"] . "</td>";
					echo "<td>" . $row["OPEN"] . "</td>";
					echo "<td>" . $row["HIGH"] . "</td>";
					echo "<td>" . $row["LOW"] . "</td>";
					echo "<td>" . $row["CLOSE"] . "</td>";
					echo "<td>" . $row["Volume"] . "</td>";
					echo "<td>" . $row["Delivery"] . "</td>";
					echo "<td>" . $row["Delivery-percentage"] . "</td>";
					echo "</tr>";
				}
				// Close table tag
				echo "</table>";
		} else {
			echo "0 results";
		}

			// Close database connection
			$conn->close();
		}
	?>
</div>
	<form method="post">
		<label for="symbol">Enter Symbol:</label>
		<input type="text" name="symbol" id="symbol"><br>
		<input type="submit" value="Submit">
	</form>
</body>
</html>
