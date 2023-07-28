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
    // Retrieve form inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    $duplicate = mysqli_query($connection, "SELECT * FROM users WHERE name = '$name' OR email = '$email'");
    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script> alert('Username or email has already been taken'); </script>";
        header("Location: register.html?error=duplicate");
        exit();
    } elseif ($password !== $confirmPassword) {
        echo "<script> alert('Passwords do not match. Please try again'); </script>";
        header("Location: register.html?error=password_mismatch");
        exit();
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the table
        $sql = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$hashedPassword')";
        if ($connection->query($sql) === TRUE) {
            header("Location: signin.html");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }

    // Close the database connection
    $connection->close();
}
?>
