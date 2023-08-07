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
        header("Location: register.php?error=duplicate");
        exit();
    } elseif ($password !== $confirmPassword) {
        echo "<script> alert('Passwords do not match. Please try again'); </script>";
        header("Location: register.php?error=password_mismatch");
        exit();
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the table
        $sql = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$hashedPassword')";
        if ($connection->query($sql) === TRUE) {
            header("Location: signin.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }

    // Close the database connection
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="./assets/css/style.css">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body id="#top">

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
    <div class="container">

      <div class="overlay" data-overlay></div>

      <a href="./index.php" class="logo">
        <img src="./assets/images/logo.svg" alt="Filmlane logo">
      </a>

      <div class="header-actions">

        <button class="search-btn">
          <ion-icon name="search-outline"></ion-icon>
        </button>

        <div class="lang-wrapper">
          <label for="language">
            <ion-icon name="globe-outline"></ion-icon>
          </label>

          <select name="language" id="language">
            <option value="en">EN</option>
            <option value="ro">RO</option>
          </select>
        </div>

        <button class="btn btn-primary" onclick="window.location.href = './signin.php';">Sign in</button>

      </div>

      <button class="menu-open-btn" data-menu-open-btn>
        <ion-icon name="reorder-two"></ion-icon>
      </button>

      <nav class="navbar" data-navbar>

        <div class="navbar-top">

          <a href="./index.php" class="logo">
            <img src="./assets/images/logo.svg" alt="Filmlane logo">
          </a>

          <button class="menu-close-btn" data-menu-close-btn>
            <ion-icon name="close-outline"></ion-icon>
          </button>

        </div>

        <ul class="navbar-list">

          <li>
            <a href="#top" class="navbar-link">Home</a>
          </li>

          <li>
            <a href="#schedule" class="navbar-link">Schedule</a>
          </li>

          <li>
            <a href="#events" class="navbar-link">Events</a>
          </li>

          <li>
            <a href="#tickets" class="navbar-link">Tickets & Access</a>
          </li>

          <li>
            <a href="#contact" class="navbar-link">Contact</a>
          </li>

        </ul>

        <ul class="navbar-social-list">

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-twitter"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-pinterest"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-youtube"></ion-icon>
            </a>
          </li>

        </ul>

      </nav>

    </div>
  </header>


  <main>
    <article>

      <!-- 
        - #TV SERIES
      -->
      <script>
        // Check if the 'error' query parameter exists
        const params = new URLSearchParams(window.location.search);
        if (params.get('error') === 'duplicate') {
            alert('Username or email has already been taken');
        }
        if (params.get('error') === 'password_mismatch') {
            alert('Passwords do not match. Please try again');
        }
      </script>
      

      <section class="tv-series">
        <div class="container">

          <p class="section-subtitle">Join the best Cinema in your city!</p>

          <h2 class="h2 section-title">Register now!</h2>

          <form class="registration-form" action="register.php" method="POST">  
            <input type="name" id="name" name="name" required class="h-input" placeholder="Full name">

            <input type="email" id="email" name="email" required class="h-input" placeholder="Email">

            <input type="tel" id="phone" name="phone" required class="h-input" placeholder="Phone number">

            <input type="password" id="password" name="password" required class="h-input" placeholder="Password">

            <input type="password" id="confirm-password" name="confirm-password" required class="h-input" placeholder="Confirm password">
  
            <div class="button-container">
              <button type="submit" class="h-button">Register</button>
              
            </div>
            
          </form>
          <div id="response-message"></div>

          <script>
              function submitForm() {
                  const form = document.querySelector('.registration-form');
                  const formData = new FormData(form);

                  fetch('register.php', {
                      method: 'POST',
                      body: formData
                  })
                  .then(response => response.text())
                  .then(message => {
                      document.getElementById('response-message').textContent = message;
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      document.getElementById('response-message').textContent = 'An error occurred. Please try again.';
                  });

                  return false; 
              }
          </script>
        </div>
      </section>

    </article>
  </main>





  <!-- 
    - #FOOTER
  -->

  <footer class="footer">

    <div class="footer-top">
      <div class="container">

        <div class="footer-brand-wrapper">

          <a href="./index.php" class="logo">
            <img src="./assets/images/logo.svg" alt="Filmlane logo">
          </a>

          <ul class="footer-list">

            <li>
              <a href="./index.php" class="footer-link">Home</a>
            </li>

            <li>
              <a href="#" class="footer-link">Movie</a>
            </li>

            <li>
              <a href="#" class="footer-link">TV Show</a>
            </li>

            <li>
              <a href="#" class="footer-link">Web Series</a>
            </li>

            <li>
              <a href="#" class="footer-link">Pricing</a>
            </li>

          </ul>

        </div>

        <div class="divider"></div>

        <div class="quicklink-wrapper">

          <ul class="quicklink-list">

            <li>
              <a href="#" class="quicklink-link">Faq</a>
            </li>

            <li>
              <a href="#" class="quicklink-link">Help center</a>
            </li>

            <li>
              <a href="#" class="quicklink-link">Terms of use</a>
            </li>

            <li>
              <a href="#" class="quicklink-link">Privacy</a>
            </li>

          </ul>

          <ul class="social-list">

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-facebook"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-twitter"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-pinterest"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-linkedin"></ion-icon>
              </a>
            </li>

          </ul>

        </div>

      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">

        <p class="copyright">
          &copy; 2023 <a href="#">Catalin</a>. All Rights Reserved
        </p>

        <img src="./assets/images/footer-bottom-img.png" alt="Online banking companies logo" class="footer-bottom-img">

      </div>
    </div>

  </footer>





  <!-- 
    - #GO TO TOP
  -->

  <a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up"></ion-icon>
  </a>





  <!-- 
    - custom js link
  -->
  <script src="./assets/js/script.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>