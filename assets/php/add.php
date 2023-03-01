<?php
	include 'config.php';

	if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["problem"])) {
		
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if($conn->connect_error)
		{
			die("Error: " . $conn->connect_error);
		}
		
		$name = $conn->real_escape_string($_POST["name"]);
		$email = $conn->real_escape_string($_POST["email"]);
		$problem = $conn->real_escape_string($_POST["problem"]);
		$date = date("Y-m-d H:i:s", time() - 60*60);
		
		$sql_1 = "INSERT INTO request (date, name, email, problem) 
			VALUES ('$date', '$name', '$email', '$problem');";

		$conn->query($sql_1);
		
		$sql_2 = "SELECT * FROM emails WHERE email = '$email';";

		$check_result = $conn->query($sql_2);

		if($check_result->num_rows == 0)
		{
			$only_date = date("Y-m-d", time() - 60*60);

			$sql_3 = "INSERT INTO emails (email, name, date, N_orders) 
			VALUES ('$email', '$name', '$only_date', 1);";

			$conn->query($sql_3);
		}
		else
		{
			$sql_3 = "UPDATE emails 
				SET N_orders = N_orders+1
				WHERE email = '$email';";
		
			$conn->query($sql_3);
		}
		
		$conn->close();
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}
?>