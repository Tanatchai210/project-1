<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arw";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Connection successful
    // echo "Connected successfully"; // Uncomment for debugging
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
