<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Establish database connection
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'user_db'; 

    $connection = new mysqli($host, $user, $password, $database);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    
}
// Destroy the session and unset the session variables
$_SESSION = [];
session_unset();
session_destroy();

header("Location: signin.php");
exit();

?>
