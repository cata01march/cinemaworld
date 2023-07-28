<?php
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

    $nameemail = $_POST["nameemail"];
    $password = $_POST["password"];

    // Hash the provided password for comparison
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $connection->prepare("SELECT * FROM users WHERE name = ? OR email = ?");
    $stmt->bind_param("ss", $nameemail, $nameemail);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if(mysqli_num_rows($result) > 0){
        $row = $result->fetch_assoc();
        // Verify the password against the hashed password from the database
        if(password_verify($password, $row["password"])){
            $_SESSION['login'] = true;
            $_SESSION['name'] = $row["name"];
            $_SESSION['id'] = $row["id"];
            header("Location: home.php");
            exit();
        }
        else{
            header("Location: signin.html?error=passwordnotfound");
            exit();
        }
    }
    else{
        header("Location: signin.html?error=usernotfound");
        exit();
    }
}
?>
