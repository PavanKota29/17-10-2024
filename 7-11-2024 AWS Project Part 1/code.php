<!DOCTYPE html>
<html>
<head>
<title>User Data Collection</title>
</head>
<body>
<?php
// MySQL database configuration
$servername = "192.168.1.30";
$username = "udc";
$password = "123";
// Create a database connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Collect user data
$name = $_POST["name"];
$age = $_POST["age"];
$country = $_POST["country"];
// Insert data into the MySQL database
$sql = "INSERT INTO udc.users (name, age, country) VALUES ('$name', $age, '$country')";
if ($conn->query($sql) === TRUE) {
echo "User data has been successfully stored in the database.<br>";
} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}
}
?>
<h2>Enter User Information</h2>
<form method="post" enctype="multipart/form-data">
Name: <input type="text" name="name"><br>
Age: <input type="number" name="age"><br>
Country: <input type="text" name="country"><br>
<input type="submit" value="Submit">
</form>
<?php
// Close the database connection
$conn->close();
?>
</body>
</html>

