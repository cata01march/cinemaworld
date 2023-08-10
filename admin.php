<?php
session_start();

if (isset($_SESSION['login']) && $_SESSION['login'] === true && $_SESSION['role'] === 'admin') {
  $host = 'localhost';
  $user = 'root';
  $password = '';
  $database = 'user_db'; // Assuming the name of your movie database

  $connection = new mysqli($host, $user, $password, $database);

  // Check connection
  if ($connection->connect_error) {
      die("Connection failed: " . $connection->connect_error);
  }

  if (isset($_POST["addMovie"])) {
    // Retrieve form inputs
    $movie = $_POST['movie'];
    $director = $_POST['director'];
    $rating = $_POST['rating'];
    $genre = $_POST['genre'];
    $year = $_POST['year'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];

    // Add more fields as needed for your movie details

    $duplicate = mysqli_query($connection, "SELECT * FROM movies WHERE movie = '$movie'");
    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script> alert('Movie name has already been taken'); </script>";
        header("Location: admin.php?error=duplicate");
        exit();
    } 
    else {
      // Insert data into the table
      $imgName = $_FILES['image']['name'];
      $sql = "INSERT INTO movies (movie, director, rating, genre, year, duration, description, image) 
              VALUES ('$movie', '$director', '$rating', '$genre', '$year', '$duration', '$description', '$imgName')";

      if ($connection->query($sql) === TRUE) {
        // Create the subpage with the movie name as the URL slug
        $slug = strtolower(str_replace(' ', '-', $movie));

        // Read the template file and replace placeholders with actual movie details
        $templateContent = file_get_contents('movie-details.php');
        $templateContent = str_replace('%MOVIE_TITLE%', $movie, $templateContent);
        $templateContent = str_replace('%MOVIE_DIRECTOR%', $director, $templateContent);
        $templateContent = str_replace('%MOVIE_RATING%', $rating, $templateContent);
        $templateContent = str_replace('%MOVIE_GENRE%', $genre, $templateContent);
        $templateContent = str_replace('%MOVIE_YEAR%', $year, $templateContent);
        $templateContent = str_replace('%MOVIE_DURATION%', $duration, $templateContent);
        $templateContent = str_replace('%MOVIE_DESCRIPTION%', $description, $templateContent);
        $templateContent = str_replace('%MOVIE_IMAGE%', $imgName, $templateContent);
        // Add more replacements for other movie details as needed
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $posterTmpName = $_FILES['image']['tmp_name'];
            $posterName = $_FILES['image']['name'];

            // Move the uploaded poster to the posters directory
            $uploadDir = './assets/images/';
            $posterPath = $uploadDir . $posterName;

            if (move_uploaded_file($posterTmpName, $posterPath)) {
                // File uploaded successfully, proceed with adding the movie to the database
                // Insert the movie details and the poster path into your database table
                // ...

                echo "Movie added successfully!";
            } else {
                echo "Error uploading poster.";
            }
        } else {
            echo "No poster uploaded.";
        }

        // Create a new PHP file with the unique slug as the file name
        $subpageFile = fopen("movies/$slug.php", "w");
        fwrite($subpageFile, $templateContent);
        fclose($subpageFile);

        // Redirect to the newly created subpage
        header("Location: movies/$slug.php?movie=$slug");
        exit();
      } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
      }
      
    }


    // Close the database connection
    $connection->close();
}
else if(isset($_POST["scheduleMovie"])) {
  $inputMovie = $_POST['inputMovie'];
  $date = $_POST['date'];
  $hour = $_POST['hour'];
  $image = "emoji.png";

  $sql = "INSERT INTO scheduled_movies (movie_id, scheduled_date, scheduled_hour)
          VALUES ('$inputMovie', '$date', '$hour');
  ";

  $connection->query($sql);
}
else if(isset($_POST["deleteMovie"])) {
  $inputMovie = $_POST['inputMovie'];

  $sql = "DELETE FROM scheduled_movies WHERE movie_id = '$inputMovie'";
  $connection->query($sql);

  $sql = "DELETE FROM movies WHERE movieId = '$inputMovie'";
  $connection->query($sql);
}
  $sql = "SELECT * FROM movies";
  $result = $connection->query($sql);
  $formMovies = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formMovies[] = $row;
    }
}
} 
else {
  header("Location: home.php"); 
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

          <a href="./home.php" class="logo">
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
      
      <script>
        // Check if the 'error' query parameter exists
        const params = new URLSearchParams(window.location.search);
        if (params.get('error') === 'duplicate') {
            alert('Movie name has already been taken');
        }
      </script>
      

      <section class="tv-series">
        <div class="container">


          <h2 class="h2 section-title">Dashboard</h2>
            <div class="form-list">
              <!-- ADD MOVIES -->
              <form class="registration-form" action="admin.php" method="POST" enctype="multipart/form-data">  
                <input type="movie" id="movie" name="movie" required class="h-input" placeholder="Movie title">

                <input type="director" id="director" name="director" required class="h-input" placeholder="Director">

                <input type="rating" id="rating" name="rating" required class="h-input" placeholder="Rating">

                <input type="genre" id="genre" name="genre" required class="h-input" placeholder="Genre">

                <input type="year" id="year" name="year" required class="h-input" placeholder="Year">

                <input type="number" id="duration" name="duration" required class="h-input" placeholder="Duration">

                <textarea name="description" style="width:100%; height:100px;">Short description of the movie</textarea>

                <label for="image" class="drop-container" id="dropcontainer">
                  <span class="drop-title">Drop files here</span>
                  or
                  <input type="file" id="images" name="image" accept="image/*" required>
                </label>
      
                <div class="button-container">
                  <button type="submit" class="h-button" name="addMovie">Add movie</button>
                </div>
                
              </form>
              <!-- ASSIGN DATE AND HOUR -->
              <form class="registration-form" action="admin.php" method="POST" enctype="multipart/form-data">  
              <label for="movie" class="search-btn">Select Movie:</label>
              <select name="inputMovie" id="movieSelect" class="search-btn">
                <?php foreach($formMovies as $m):?>
                    <option value="<?php echo $m['movieId']?>"><?php echo $m['movie']?></option>
                <?php endforeach ?>
              </select>
              
              <label for="date" class="search-btn">Select Date:</label>
              <input type="date" name="date" required class="h-input">
              
              <label for="hour" class="search-btn">Select Hour:</label>
              <input type="time" name="hour" required class="h-input">

              <label for="hour" class="search-btn">Additional images for movie</label>
              <label for="image" class="drop-container" id="dropcontainer">
                <span class="drop-title">Drop files here</span>
                or
                <input type="file" id="images" name="image" accept="image/*">
              </label>
              
              <div class="button-container">
                <button type="submit" class="h-button" name="scheduleMovie">Schedule</button>
              </div>
                
              </form>
              <!-- DELETE MOVIE -->
              <form class="registration-form" action="admin.php" method="POST">  
              <label for="movie" class="search-btn">Select Movie:</label>
              <select name="inputMovie" id="movieDelete" class="search-btn">
                <?php foreach($formMovies as $m):?>
                    <option value="<?php echo $m['movieId']?>"><?php echo $m['movie']?></option>
                <?php endforeach ?>
              </select>
              <div class="button-container">
                <button type="submit" id="deleteButton" class="h-button" name="deleteMovie">Delete movie and schedules</button>
              </div>
                
              </form>
                </div>
          <div id="response-message"></div>

          <script>
              function submitForm() {
                  const form = document.querySelector('.registration-form');
                  const formData = new FormData(form);

                  fetch('admin.php', {
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
              document.getElementById("deleteButton").addEventListener("click", function() {
                if (confirm("Are you sure you want to delete this movie?")) {
                    document.getElementById("deleteForm").submit();
                }
              });
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

          <a href="./home.php" class="logo">
            <img src="./assets/images/logo.svg" alt="Filmlane logo">
          </a>

          <ul class="footer-list">

            <li>
              <a href="./home.php" class="footer-link">Home</a>
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