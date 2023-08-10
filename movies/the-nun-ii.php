<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'user_db';
$connection = new mysqli($host, $user, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Replace 'movie_id' with the actual column name that uniquely identifies each movie
$movie = $_GET['movie'];

// Query the database to retrieve movie details
$sql = "SELECT * FROM movies WHERE movie = '$movie'";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $movieId = $row['movieId'];
    $movie = $row['movie'];
    $image = $row['image'];
    $rating = $row['rating'];
    $genre = $row['genre'];
    $year = $row['year'];
    $duration = $row['duration'];
    $description = $row['description'];
} else {
    // Handle error if movie not found
    die("Movie not found");
}

// Close the database connection

// Function to check if the user is logged in
// if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
//   header("Location: index.php");
//   exit();
// }
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
  <title><?php echo $movie; ?></title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="/favicon.svg" type="image/svg+xml">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="/assets/css/style.css">

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

      <a href="/home.php" class="logo">
        <img src="/assets/images/logo.svg" alt="Filmlane logo">
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
          <button class="btn btn-primary" onclick="window.location.href = '/admin.php';">Dashboard</button>
          <button class="btn btn-primary" onclick="window.location.href = '/logout.php';">Logout</button>
        <?php elseif(isUserLoggedInUser()): ?>
          <button class="btn btn-primary" onclick="window.location.href = '/logout.php';">Logout</button>
        <?php else: ?>
          <button class="btn btn-primary" onclick="window.location.href = '/signin.php';">Sign In</button>
        <?php endif; ?>

      </div>

      <button class="menu-open-btn" data-menu-open-btn>
        <ion-icon name="reorder-two"></ion-icon>
      </button>

      <nav class="navbar" data-navbar>

        <div class="navbar-top">

          <a href="/home.php" class="logo">
            <img src="/assets/images/logo.svg" alt="Filmlane logo">
          </a>

          <button class="menu-close-btn" data-menu-close-btn>
            <ion-icon name="close-outline"></ion-icon>
          </button>

        </div>

        <ul class="navbar-list">

          <li>
            <a href="/home.php" class="navbar-link">Home</a>
          </li>

          <li>
            <a href="/home.php#events" class="navbar-link">Events</a>
          </li>

          <li>
            <a href="/home.php#schedule" class="navbar-link">Schedule</a>
          </li>

          <li>
            <a href="/home.php#tickets" class="navbar-link">Tickets & Access</a>
          </li>

          <li>
            <a href="/home.php#contact" class="navbar-link">Contact</a>
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
        - #MOVIE DETAIL
      -->

      <section class="movie-detail">
        <div class="container">

            <figure class="movie-detail-banner">
                <img src="<?php echo '/assets/images/'.$image; ?>" alt="<?php echo $movie; ?> movie poster">
                <button class="play-btn">
                    <ion-icon name="play-circle-outline"></ion-icon>
                </button>
            </figure>

            <div class="movie-detail-content">
                <p class="detail-subtitle">Now in cinema!</p>
                <h1 class="h1 detail-title">
                    <?php echo $movie; ?>
                </h1>

                <div class="meta-wrapper">
                    <div class="badge-wrapper">
                        <div class="badge badge-fill"><?php echo $rating; ?></div>
                    </div>

                    <div class="ganre-wrapper">
                      <?php
                      $genres = explode(',', $genre);
                      foreach ($genres as $genre) {
                          echo '<a href="#">' . trim($genre) . '</a>';
                      }
                      ?>
                    </div>

                    <div class="date-time">
                        <div>
                            <ion-icon name="calendar-outline"></ion-icon>
                            <time datetime="<?php echo $year; ?>"><?php echo $year; ?></time>
                        </div>
                        <div>
                            <ion-icon name="time-outline"></ion-icon>
                            <time datetime="PT<?php echo $duration; ?>"><?php echo $duration; ?> min</time>
                        </div>
                    </div>
                </div>

                <p class="storyline">
                    <?php echo $description; ?>
                </p>
            </div>
        </div>
      </section>





      <!-- 
        - #TV SERIES
      -->

      <section class="tv-series">
        <div class="container">

          <p class="section-subtitle">Neverending list</p>

          <h2 class="h2 section-title">Check out more of our movies</h2>

          <ul class="movies-list">
            <?php
            $sql = "SELECT * FROM movies ORDER BY RAND() LIMIT 4";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <li>
                        <div class="movie-card">
                            <a href="/movie-details.php?movie=<?php echo $movie; ?>">
                                <figure class="card-banner">
                                    <img src="<?php echo '/assets/images/'.$image; ?>" alt="<?php echo $movie; ?> movie poster">
                                </figure>
                            </a>
                            <div class="title-wrapper">
                                <a href="/movie-details.php?movie=<?php echo $movie; ?>">
                                    <h3 class="card-title"><?php echo $movie; ?></h3>
                                </a>
                                <time datetime="<?php echo $date; ?>"> <?php echo $date; ?></time>
                            </div>
                            <div class="card-meta">
                                <div class="badge badge-outline">2K</div>
                                <div class="duration">
                                    <ion-icon name="time-outline"></ion-icon>
                                    <time datetime="PT<?php echo $movie; ?>M"><?php echo $movie; ?> min</time>
                                </div>
                                <div class="rating">
                                    <ion-icon name="star"></ion-icon>
                                    <data><?php echo $rating; ?></data>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php
                }
            } else {
                echo "No movies found.";
            }
            ?>
          </ul>

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

          <a href="/index" class="logo">
            <img src="/assets/images/logo.svg" alt="Filmlane logo">
          </a>

          <ul class="footer-list">

            <li>
              <a href="/index" class="footer-link">Home</a>
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

        <img src="/assets/images/footer-bottom-img.png" alt="Online banking companies logo" class="footer-bottom-img">

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
  <script src="/assets/js/script.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>