<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve JSON payload
    $jsonPayload = file_get_contents('php://input');
    $data = json_decode($jsonPayload, true);

    // Extract data from JSON
    $movieId = $data['movieId'];
    $updatedSeatOccupancy = json_encode($data['seatOccupancy']);

    // Update seat occupancy data in the database based on $movieId
    $sql = "UPDATE seats SET is_occupied = ? WHERE movie_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('si', $updatedSeatOccupancy, $movieId);
    $stmt->execute();
    $stmt->close();

    // Return a success response
    echo json_encode(array('success' => true));
}
?>