<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'user_db';

$connection = new mysqli($host, $user, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['movieId'])) {
    $movieId = $_GET['movieId'];

    // Fetch seat occupancy data from the database based on $movieId

    // Construct and execute the SQL query
    $sql = "SELECT is_occupied FROM seats WHERE movie_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $movieId);
    $stmt->execute();
    $stmt->bind_result($seatOccupancy);
    $stmt->fetch();
    $stmt->close();

    // Return the seat occupancy data as JSON
    $response = array('seatOccupancy' => json_decode($seatOccupancy));
    echo json_encode($response);
}
?>