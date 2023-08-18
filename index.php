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

//Get Upcoming Movies
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
// Function to check if the user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['login']) && $_SESSION['login'] === true;
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

        

        <div class="lang-wrapper">
          <label for="language">
            <ion-icon name="globe-outline"></ion-icon>
          </label>

          <select name="language" id="language">
            <option value="en">EN</option>
            <option value="ro">RO</option>
          </select>
        </div>

        <?php if (isUserLoggedIn()): ?>
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

          <a href="./home.php" class="logo">
            <img src="./assets/images/logo.svg" alt="Filmlane logo">
          </a>

          <button class="menu-close-btn" data-menu-close-btn>
            <ion-icon name="close-outline"></ion-icon>
          </button>

        </div>

        <ul class="navbar-list">

          <li>
            <a href="#home" class="navbar-link">Home</a>
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





  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="hero" id="home">
        <div class="container">

          <div class="hero-content">

            <p class="hero-subtitle">Cineflix</p>

            <h1 class="h1 hero-title">
              Now in <strong>Cineflix</strong>
            </h1>

            <div class="meta-wrapper">

              <div class="badge-wrapper">
                <div class="badge badge-fill">PG 18</div>

                <div class="badge badge-outline">HD</div>
              </div>

              <div class="ganre-wrapper">
                <a href="#">Romance,</a>

                <a href="#">Drama</a>
              </div>

              <div class="date-time">

                <div>
                  <ion-icon name="calendar-outline"></ion-icon>

                  <time datetime="2022">2022</time>
                </div>

                <div>
                  <ion-icon name="time-outline"></ion-icon>

                  <time datetime="PT128M">128 min</time>
                </div>

              </div>

            </div>

            <button class="btn btn-primary">
              <ion-icon name="play"></ion-icon>

              <span>Watch now</span>
            </button>

          </div>

        </div>
      </section>

      <!-- 
        - #schedule
      -->

      <section class="top-rated" id="events">
        <div class="container">

          <p class="section-subtitle">Book a spot now!</p>

          <h2 class="h2 section-title">Movies</h2>

          <ul class="filter-list">

            <li>
              <button class="filter-btn">Movies</button>
            </li>

            <li>
              <button class="filter-btn">Documentary</button>
            </li>

            <li>
              <button class="filter-btn">Sports</button>
            </li>

          </ul>

          <ul class="movies-list">

          <?php foreach($movies as $movie): ?>
            <li>
              <div class="movie-card">

                <a href="./movie-details.php?movie=<?php echo $movie['movie'] ?>">
                  <figure class="card-banner">
                    <img src="./assets/images/<?php echo $movie['image'];?>" alt="<?php echo $movie['movie'] ?>">
                  </figure>
                </a>

                <div class="title-wrapper">
                  <a href="./movie-details.php">
                    <h3 class="card-title"><?php echo $movie['movie'] ?></h3>
                  </a>

                  <time datetime="2022"><?php echo $movie['year'] ?></time>
                </div>

                <div class="card-meta">
                  <div class="badge badge-outline">2K</div>

                  <div class="duration">
                    <ion-icon name="time-outline"></ion-icon>

                    <time datetime="PT122M"><?php echo $movie['duration'] ?> min</time>
                  </div>

                  <div class="rating">
                    <ion-icon name="star"></ion-icon>

                    <data><?php echo $movie['rating'] ?></data>
                  </div>
                </div>

              </div>
            </li>
          <?php endforeach ?>

          </ul>

        </div>
      </section>




      <!-- 
        - #UPCOMING
      -->

      <section class="upcoming" id = "schedule">
        <div class="container">

          <div class="flex-wrapper">

            <div class="title-wrapper">
              <p class="section-subtitle">Online Streaming</p>

              <h2 class="h2 section-title">Upcoming Movies</h2>
            </div>

            <ul class="filter-list">

              <li>
                <button class="filter-btn">Movies</button>
              </li>

              <li>
                <button class="filter-btn">TV Shows</button>
              </li>

              <li>
                <button class="filter-btn">Anime</button>
              </li>

            </ul>

          </div>

          <ul class="movies-list">
            <?php
            $currentDateTime = new DateTime();

            foreach ($scheduledMovies as $movie):
                $scheduledDateTime = new DateTime($movie['formatted_date'] . $movie['formatted_hour']);

                if ($scheduledDateTime > $currentDateTime):
            ?>
            <li>
                <div class="movie-card">
                    <a href="./movie-details.php?movie=<?php echo $movie['movie'] ?>">
                        <figure class="card-banner">
                            <img src="./assets/images/<?php echo $movie['image'];?>" alt="<?php echo $movie['movie'] ?>">
                        </figure>
                    </a>
                    <div class="title-wrapper">
                        <a href="./movie-details.php?movie=<?php echo $movie['movie'] ?>">
                            <h3 class="card-title"><?php echo $movie['movie'];?></h3>
                        </a>
                        <time datetime="<?php echo $movie['formatted_date'];?>"><?php echo $movie['formatted_date'], $movie['formatted_hour'];?></time>
                    </div>
                    <div class="card-meta">
                        <div class="badge badge-outline">HD</div>
                        <div class="duration">
                            <ion-icon name="time-outline"></ion-icon>
                            <time datetime="<?php echo $movie['duration'] ?>"><?php echo $movie['duration'] ?> min</time>
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?php echo $movie['rating'] ?></data>
                        </div>
                    </div>
                </div>
            </li>
            <?php
                endif;
            endforeach;
            ?>
          </ul>


        </div>
      </section>





      <!-- 
        - #SERVICE
      -->

      <section class="service" id = "tickets">
        <div class="container">

          <div class="service-content">

            <p class="service-subtitle">Our Services</p>

            <h2 class="h2 service-title">Pay as you want.</h2>

            <p class="service-text">
              Some screenings may have a single price equivalent to the full price or one of the reduced price.
            </p>

            <ul class="service-list">

              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h3 class="h3 card-title">Full price</h3>
                    <p class="card-text">RON20</p>
                  </div>

                </div>
              </li>

              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h3 class="h3 card-title">Discounted price - students & retirees</h3>
                    <p class="card-text">RON15</p>
                  </div>

                </div>
              </li>
              
              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h3 class="h3 card-title">Discounted price - childs under 12</h3>
                    <p class="card-text">RON10</p>
                  </div>

                </div>
              </li>
              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h3 class="h3 card-title">Discounted price - certificate needed</h3>
                    <p class="card-text">FREE</p>
                  </div>

                </div>
              </li>

            </ul>

          </div>

          <div class="service-content">


            <h2 class="h2 service-title">Multicultural events</h2>

            <p class="service-text">
              Certain events hosted in our cinema and organized by independent cultural operators may have a different price.
            </p>

            <ul class="service-list">

            <li>
                <div class="service-card">

                  <div class="card-content">
                    <h3 class="h3 card-title">Full price, depending on the event</h3>
                    <p class="card-text">RON30/40/50</p>
                  </div>

                </div>
              </li>

              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h3 class="h3 card-title">Discounted price, depending on the event - students & retirees</h3>
                    <p class="card-text">RON20/30/40</p>
                  </div>

                </div>
              </li>
              
              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h3 class="h3 card-title">Discounted price - childs under 12</h3>
                    <p class="card-text">RON15/20</p>
                  </div>

                </div>
              </li>
              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h3 class="h3 card-title">Discounted price - certificate needed</h3>
                    <p class="card-text">FREE</p>
                  </div>

                </div>
              </li>

            </ul>

          </div>

          <div class="service-content">


            <h2 class="h2 service-title">You should read these too</h2>

            <p class="service-text">
              Access for people with disabilities or using a wheelchair is possible. The hall has: <br>
                - access ramp <br>
                - adapted toilets <br>
                - special space for the wheelchair in the last row 
            </p>

            <ul class="service-list">

              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h4 class="h4 card-title">Tickets can be purchased online or from the cinema cashier, open 30 minutes before each performance, subject to availability;</h4>
                  </div>

                </div>
              </li>

              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h4 class="h4 card-title">Discounted tickets can only be purchased upon presentation of a valid supporting document. If purchased online, the proof will be requested at the entrance to the cinema;</h4>
                  </div>

                </div>
              </li>
              
              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h4 class="h4 card-title">Tickets are valid only on the date and time printed on them;</h4>
                  </div>

                </div>
              </li>

              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h4 class="h4 card-title">The access of spectators to the cinema after the start time of the film, as written on the ticket, is not allowed;</h4>
                  </div>

                </div>
              </li>

              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h4 class="h4 card-title">Failure to show up on time at the show leads to cancellation of the ticket without the right to refund or use it at another performance.</h4>
                  </div>

                </div>
              </li>

              <li>
                <div class="service-card">

                  <div class="card-content">
                    <h4 class="h4 card-title">Out of respect for other spectators and out of care for space, please do not eat food in the cinema hall. Thank you!</h4>
                  </div>

                </div>
              </li>

            </ul>

          </div>

        </div>
      </section>

      <!-- 
        - #TV SERIES
      -->

      <!-- <section class="tv-series" id="tvseries">
        <div class="container">

          <p class="section-subtitle">Best TV Series</p>

          <h2 class="h2 section-title">World Best TV Series</h2>

          <ul class="movies-list">

            <li>
              <div class="movie-card">

                <a href="./movie-details">
                  <figure class="card-banner">
                    <img src="./assets/images/series-1.png" alt="Moon Knight movie poster">
                  </figure>
                </a>

                <div class="title-wrapper">
                  <a href="./movie-details">
                    <h3 class="card-title">Moon Knight</h3>
                  </a>

                  <time datetime="2022">2022</time>
                </div>

                <div class="card-meta">
                  <div class="badge badge-outline">2K</div>

                  <div class="duration">
                    <ion-icon name="time-outline"></ion-icon>

                    <time datetime="PT47M">47 min</time>
                  </div>

                  <div class="rating">
                    <ion-icon name="star"></ion-icon>

                    <data>8.6</data>
                  </div>
                </div>

              </div>
            </li>

            <li>
              <div class="movie-card">

                <a href="./movie-details">
                  <figure class="card-banner">
                    <img src="./assets/images/series-2.png" alt="Halo movie poster">
                  </figure>
                </a>

                <div class="title-wrapper">
                  <a href="./movie-details">
                    <h3 class="card-title">Halo</h3>
                  </a>

                  <time datetime="2022">2022</time>
                </div>

                <div class="card-meta">
                  <div class="badge badge-outline">2K</div>

                  <div class="duration">
                    <ion-icon name="time-outline"></ion-icon>

                    <time datetime="PT59M">59 min</time>
                  </div>

                  <div class="rating">
                    <ion-icon name="star"></ion-icon>

                    <data>8.8</data>
                  </div>
                </div>

              </div>
            </li>

            <li>
              <div class="movie-card">

                <a href="./movie-details">
                  <figure class="card-banner">
                    <img src="./assets/images/series-3.png" alt="Vikings: Valhalla movie poster">
                  </figure>
                </a>

                <div class="title-wrapper">
                  <a href="./movie-details">
                    <h3 class="card-title">Vikings: Valhalla</h3>
                  </a>

                  <time datetime="2022">2022</time>
                </div>

                <div class="card-meta">
                  <div class="badge badge-outline">2K</div>

                  <div class="duration">
                    <ion-icon name="time-outline"></ion-icon>

                    <time datetime="PT51M">51 min</time>
                  </div>

                  <div class="rating">
                    <ion-icon name="star"></ion-icon>

                    <data>8.3</data>
                  </div>
                </div>

              </div>
            </li>

            <li>
              <div class="movie-card">

                <a href="./movie-details">
                  <figure class="card-banner">
                    <img src="./assets/images/series-4.png" alt="Money Heist movie poster">
                  </figure>
                </a>

                <div class="title-wrapper">
                  <a href="./movie-details">
                    <h3 class="card-title">Money Heist</h3>
                  </a>

                  <time datetime="2017">2017</time>
                </div>

                <div class="card-meta">
                  <div class="badge badge-outline">4K</div>

                  <div class="duration">
                    <ion-icon name="time-outline"></ion-icon>

                    <time datetime="PT70M">70 min</time>
                  </div>

                  <div class="rating">
                    <ion-icon name="star"></ion-icon>

                    <data>8.3</data>
                  </div>
                </div>

              </div>
            </li>

          </ul>

        </div>
      </section> -->





      <!-- 
        - #CTA
      -->

      <section class="cta" id = "contact">
        <div class="container">

          <div class="title-wrapper">
            <h2 class="cta-title">Subscribe to our newsletter</h2>

            <p class="cta-text">
              Be in charge⚡️with our new movies and events.
            </p>
          </div>

          <form action="" class="cta-form">
            <input type="email" name="email" required placeholder="Enter your email" class="email-field">

            <button type="submit" class="cta-form-btn">Subscribe</button>
          </form>

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