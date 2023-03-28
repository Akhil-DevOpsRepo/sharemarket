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
		background-color: lightblue
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
			$sql = "SELECT * FROM volumedata WHERE Symbol = '$input_value' ORDER BY Date DESC";
			
			$sql_avg = "WITH cte_name AS (SELECT * FROM volumedata WHERE Symbol = '$input_value' ORDER BY Date DESC LIMIT 30)
SELECT Symbol, ROUND(AVG(OPEN)) AS OPEN, ROUND(AVG(HIGH)) AS HIGH, ROUND(AVG(LOW)) AS LOW, ROUND(AVG(CLOSE)) AS CLOSE, ROUND(AVG(Volume)) AS Volume,ROUND(AVG(Delivery)) AS Delivery, ROUND(AVG(`Delivery-percentage`)) AS `Delivery-percentage` FROM cte_name";

			// Execute SQL query
			$result = $conn->query($sql);
			$result_avg = $conn->query($sql_avg);
			// Display query results
			if ($result->num_rows > 0) {
			// Output table headers
				
				echo "<table>";
				echo "<thead><tr><th colspan='9' style='text-align:center;'>$input_value</th></tr></thead>";
				echo "<tr><th>Date</th><th>OPEN</th><th>HIGH</th><th>LOW</th><th>CLOSE</th><th>Volume</th><th>Delivery</th><th>Delivery-percentage</th></tr>";
				while($row_avg = $result_avg->fetch_assoc()){
				echo "<tr>";
				echo "<td>" . "Average(30days)" . "</td>";
				echo "<td>" . $row_avg["OPEN"] . "</td>";
				echo "<td>" . $row_avg["HIGH"] . "</td>";
				echo "<td>" . $row_avg["LOW"] . "</td>";
				echo "<td>" . $row_avg["CLOSE"] . "</td>";
				echo "<td>" . $row_avg["Volume"] . "</td>";
				echo "<td>" . $row_avg["Delivery"] . "</td>";
				echo "<td>" . $row_avg["Delivery-percentage"] . "</td>";
				echo "</tr>";}
				// Output table rows
				while($row = $result->fetch_assoc()) {
					echo "<tr>";
					echo "<td>" . $row["Date"] . "</td>";
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
	<form style="position: absolute; top: 100px; left: 70px;" method="post">
		<label for="symbol">Enter Symbol:</label>
		<select id="symbol" name="symbol">
		<option value="TCS"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'TCS') echo ' selected'; ?>>TCS</option>
		<option value="ACC"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'ACC') echo ' selected'; ?>>ACC</option>
		<option value="ADANIENT"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'ADANIENT') echo ' selected'; ?>>ADANIENT</option>
		<option value="ADANIGREEN"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'ADANIGREEN') echo ' selected'; ?>>ADANIGREEN</option>
		<option value="ADANIPORTS"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'ADANIPORTS') echo ' selected'; ?>>ADANIPORTS</option>
		<option value="ADANITRANS"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'ADANITRANS') echo ' selected'; ?>>ADANITRANS</option>
		<option value="AMBUJACEM"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'AMBUJACEM') echo ' selected'; ?>>AMBUJACEM</option>
		<option value="APOLLOHOSP"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'APOLLOHOSP') echo ' selected'; ?>>APOLLOHOSP</option>
		<option value="ASIANPAINT"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'ASIANPAINT') echo ' selected'; ?>>ASIANPAINT</option>
		<option value="ATGL"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'ATGL') echo ' selected'; ?>>ATGL</option>
		<option value="AXISBANK"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'AXISBANK') echo ' selected'; ?>>AXISBANK</option>
		<option value="BAJAJ-AUTO"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BAJAJ-AUTO') echo ' selected'; ?>>BAJAJ-AUTO</option>
		<option value="BAJAJFINSV"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BAJAJFINSV') echo ' selected'; ?>>BAJAJFINSV</option>
		<option value="BAJAJHLDNG"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BAJAJHLDNG') echo ' selected'; ?>>BAJAJHLDNG</option>
		<option value="BAJFINANCE"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BAJFINANCE') echo ' selected'; ?>>BAJFINANCE</option>
		<option value="BANDHANBNK"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BANDHANBNK') echo ' selected'; ?>>BANDHANBNK</option>
		<option value="BANKBARODA"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BANKBARODA') echo ' selected'; ?>>BANKBARODA</option>
		<option value="BEL"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BEL') echo ' selected'; ?>>BEL</option>
		<option value="BERGEPAINT"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BERGEPAINT') echo ' selected'; ?>>BERGEPAINT</option>
		<option value="BHARTIARTL"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BHARTIARTL') echo ' selected'; ?>>BHARTIARTL</option>
		<option value="BIOCON"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BIOCON') echo ' selected'; ?>>BIOCON</option>
		<option value="BOSCHLTD"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BOSCHLTD') echo ' selected'; ?>>BOSCHLTD</option>
		<option value="BPCL"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BPCL') echo ' selected'; ?>>BPCL</option>
		<option value="BRITANNIA"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'BRITANNIA') echo ' selected'; ?>>BRITANNIA</option>
		<option value="CHOLAFIN"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'CHOLAFIN') echo ' selected'; ?>>CHOLAFIN</option>
		<option value="CIPLA"<?php if(isset($_POST['symbol']) && $_POST['symbol'] == 'CIPLA') echo ' selected'; ?>>CIPLA</option>
		</select>
		<br><br>
		<input type="submit" value="Submit">
	</form>
</body>
</html>
