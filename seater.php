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

// 2. Fetch data from the database
$sql = "SELECT * FROM movies";
$result = $connection->query($sql);

// 3. Process the data
$movies = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
}

$sql = "SELECT scheduled_movies.*, movies.*, DATE_FORMAT(scheduled_movies.scheduled_date, '%W, %M %e') AS formatted_date, DATE_FORMAT(scheduled_movies.scheduled_hour, ', %H:%i') AS formatted_hour 
        FROM scheduled_movies 
        LEFT JOIN movies 
        ON scheduled_movies.movie_id = movies.movieId
        WHERE scheduled_movies.scheduled_date >= CURDATE()
        ORDER BY scheduled_movies.scheduled_date, scheduled_movies.scheduled_hour ASC";
$result = $connection->query($sql);

$scheduledMovies = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $scheduledMovies[] = $row;
    }
}

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
  header("Location: index.php");
  exit();
}
function isUserLoggedInAdmin() {
  return isset($_SESSION['login']) && $_SESSION['login'] === true && $_SESSION['role'] === 'admin';
}
function isUserLoggedInUser() {
  return isset($_SESSION['login']) && $_SESSION['login'] === true && $_SESSION['role'] === 'user';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cineflix</title>

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

<body id="top">

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
    <div class="container">

      <div class="overlay" data-overlay></div>

      <a href="./home.php" class="logo">
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

        <?php if (isUserLoggedInAdmin()): ?>
          <button class="btn btn-primary" onclick="window.location.href = './admin.php';">Dashboard</button>
          <button class="btn btn-primary" onclick="window.location.href = './logout.php';">Logout</button>
        <?php elseif(isUserLoggedInUser()): ?>
          <button class="btn btn-primary" onclick="window.location.href = './logout.php';">Logout</button>
        <?php else: ?>
          <button class="btn btn-primary" onclick="window.location.href = './signin.php';">Sign In</button>
        <?php endif; ?>

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
            <a href="#events" class="navbar-link">Events</a>
          </li>

          <li>
            <a href="#schedule" class="navbar-link">Schedule</a>
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

  <!-- 
    seat selector
   -->
  <style>
    body {
      display: flex;
      flex-direction: column;
      color: white;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
  </style>

  <div class="movie-container">
    <label><?php echo $movie['movie'];?><?php echo $movie['formatted_date'];?><<?php echo $movie['formatted_date'];?>/label>
  </div>

    <ul class="showcase">
      <li>
        <div class="seat"></div>
        <small>N/A</small>
      </li>

      <li>
        <div class="seat selected"></div>
        <small>Selected</small>
      </li>

      <li>
        <div class="seat occupied"></div>
        <small>Occupied</small>
      </li>
    </ul>

    <div class="seatContainer">
      <div class="screen"></div>
      <div class="row">
        <div class="seat" id="seat01"></div>
        <div class="seat" id="seat02"></div>
        <div class="seat" id="seat03"></div>
        <div class="seat" id="seat04"></div>
        <div class="seat" id="seat05"></div>
        <div class="seat" id="seat06"></div>
        <div class="seat" id="seat07"></div>
        <div class="seat" id="seat08"></div>
      </div>
      <div class="row">
        <div class="seat" id="seat11"></div>
        <div class="seat" id="seat12"></div>
        <div class="seat" id="seat13"></div>
        <div class="seat" id="seat14"></div>
        <div class="seat" id="seat15"></div>
        <div class="seat" id="seat16"></div>
        <div class="seat" id="seat17"></div>
        <div class="seat" id="seat18"></div>
      </div>

      <div class="row">
        <div class="seat" id="seat21"></div>
        <div class="seat" id="seat22"></div>
        <div class="seat" id="seat23"></div>
        <div class="seat" id="seat24"></div>
        <div class="seat" id="seat25"></div>
        <div class="seat" id="seat26"></div>
        <div class="seat" id="seat27"></div>
        <div class="seat" id="seat28"></div>
      </div>

      <div class="row">
        <div class="seat" id="seat31"></div>
        <div class="seat" id="seat32"></div>
        <div class="seat" id="seat33"></div>
        <div class="seat" id="seat34"></div>
        <div class="seat" id="seat35"></div>
        <div class="seat" id="seat36"></div>
        <div class="seat" id="seat37"></div>
        <div class="seat" id="seat38"></div>
      </div>

      <div class="row">
        <div class="seat" id="seat41"></div>
        <div class="seat" id="seat42"></div>
        <div class="seat" id="seat43"></div>
        <div class="seat" id="seat44"></div>
        <div class="seat" id="seat45"></div>
        <div class="seat" id="seat46"></div>
        <div class="seat" id="seat47"></div>
        <div class="seat" id="seat48"></div>
      </div>

      <div class="row">
        <div class="seat" id="seat51"></div>
        <div class="seat" id="seat52"></div>
        <div class="seat" id="seat53"></div>
        <div class="seat" id="seat54"></div>
        <div class="seat" id="seat55"></div>
        <div class="seat" id="seat56"></div>
        <div class="seat" id="seat57"></div>
        <div class="seat" id="seat58"></div>
      </div>
    </div>

    <p class="text">
      You have selected <span id="count">0</span> seats for a price of $<span id="total">0</span>
    </p>
    <script src="assets/js/seat.js" defer></script>
    <script src="assets/js/script.js"></script>

  <!-- 
    - #FOOTER
  -->

  <footer class="footer">

    <div class="footer-top">
      <div class="container">

        <div class="footer-brand-wrapper">

          <a href="./index" class="logo">
            <img src="./assets/images/logo.svg" alt="Filmlane logo">
          </a>

          <ul class="footer-list">

            <li>
              <a href="#top" class="footer-link">Home</a>
            </li>

            <li>
              <a href="#movies" class="footer-link">Movie</a>
            </li>

            <li>
              <a href="#tvseries" class="footer-link">TV Show</a>
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