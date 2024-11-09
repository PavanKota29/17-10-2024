<!DOCTYPE html>

<html>

<head>

    <title>User Data Collection</title>

</head>

<body>

<?php

// AWS S3 configuration

$bucket = 's3bucketforface';

$region = 'ap-south-1';


// MySQL database configuration

$servername = "192.168.1.30";

$username = "udc";

$password ="123";



use Aws\S3\S3Client;

use Aws\Exception\AwsException;


// Create a database connection

$conn = new mysqli($servername, $username, $password);


// Check connection

if ($conn->connect_error) {

    die('Connection failed: ' . $conn->connect_error);

}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect user data

    $name = $_POST['name'];

    $age = $_POST['age'];

    $country = $_POST['country'];


    // Insert data into the MySQL database

    $sql = "INSERT INTO udc.users (name, age, country) VALUES ('$name', $age, '$country')";

    if ($conn->query($sql) === TRUE) {

        echo 'User data has been successfully stored in the database.<br>';

    } else {

        echo 'Error: ' . $sql . '<br>' . $conn->error;

    }


    // Handle file upload to S3

    if ($_FILES['userfile']['error'] === UPLOAD_ERR_OK) {

        $tempFile = $_FILES['userfile']['tmp_name'];

        $s3Key = 'uploads/' . basename($_FILES['userfile']['name']);


        require 'vendor/autoload.php'; // Include the AWS SDK for PHP





        $s3 = new S3Client([

            'version' => 'latest',

            'region' => $region,

            'credentials' => [

                'key' => 'AKIATS5AD4KH6MEZZNVZ',

                'secret' => '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',

            ],

        ]);


        try {

            $s3->putObject([

                'Bucket' => $bucket,

                'Key' => $s3Key,

                'Body' => fopen($tempFile, 'r'),

                'ACL' => 'public-read',

            ]);


            echo 'File is valid, and it has been successfully uploaded to S3.<br>';

        } catch (AwsException $e) {

            echo 'Error uploading file to S3: ' . $e->getMessage() . '<br>';

        }

    } else {

        echo 'File upload failed.<br>';

    }

}

?>

<h2>Enter User Information</h2>

<form method="post" enctype="multipart/form-data">

    Name: <input type="text" name="name"><br>

    Age: <input type="number" name="age"><br>

    Country: <input type="text" name="country"><br>

    File Upload: <input type="file" name="userfile"><br>

    <input type="submit" value="Submit">

</form>

<?php

// Close the database connection

$conn->close();

?>

</body>

</html>

