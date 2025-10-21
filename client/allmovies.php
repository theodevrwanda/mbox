<?php
// import database setting in index file
require('../dbconf.php');

session_start();
if (isset($_SESSION['username']) || isset($_COOKIE['username'])){
      header('Location:../admin/dashboard.php');
      exit();
}elseif(isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])){
    $userid = $_SESSION['user_id'];
}else{
    header('Location:../common/home.php');
    exit();
}

if (isset($_POST['query'])) {
  $searchQuery = $_POST['query'];
  $stmt = $conn->prepare("SELECT * FROM movies WHERE name LIKE CONCAT('%', ?, '%') OR translator LIKE CONCAT('%',?,'%')");
  $stmt->bind_param('ss', $searchQuery,$searchQuery);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          echo '<div class="result_itm">';
          echo '<img src="' . $row['poster_link'] . '" alt="' . $row['name'] . '" style="width: 50px; height: 70px;">';
          echo '<div class="movie-info">';
          echo '<h3><a href="private/pages/views.php?movie_id=' . $row['id'] . '" target="_blank">' . $row['name'] . '</a></h3>';            
          echo '<p>' . $row['translator'] . '</p>';
          echo '</div>';
          echo '</div>';
      }
  } else {
      echo '<div class="no-result">No movies found...</div>';
  }
  $stmt->close();
  exit; // To prevent the rest of the HTML from being sent in AJAX response
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox | All movies </title>
    <link rel="shortcut icon" href="#" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../issets/styles/index.css">
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
</head>
<body>
    <!-- header navbar -->
    <header>
        <div class="logo">  
            <img src="../issets/img/newmbox.png" alt="Mbox Logo" class="web-logo">
        </div>

        <!-- navbar list -->
        <nav>
            <ul>
                <li><a href="../index.php" >Home<span></span></a></li>
                <li><a href="allmovies.php" class="active" >All Movies<span></span></a></li>
            </ul>
        </nav>
<!-- Search form -->
<form action="#" method="post" class="search" id="searchForm">
<input type="text" name="search" id="searchInput" placeholder="Search movies..."  onkeyup="liveSearch()">
<button type="button" onclick="expandSearch()"><i class="fa fa-search"></i></button>
</form>
<script>
function expandSearch() {
    var searchInput = document.getElementById('searchInput');
    searchInput.classList.toggle('show-input'); // Toggle class to show/hide input
}

function liveSearch() {
    var searchQuery = document.getElementById('searchInput').value;

    // Check if the search query is not empty
    if (searchQuery.length > 0) {
        // Create an AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true); // Same file for processing
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Update the search result div with the response
                document.getElementById('searchResults').innerHTML = xhr.responseText;
            }
        };

        // Send the search query to the PHP part of the same file
        xhr.send('query=' + encodeURIComponent(searchQuery));
    } else {
        // If search input is empty, clear the result div
        document.getElementById('searchResults').innerHTML = '';
    }
}
</script>
<!-- user profire figure section  -->
<a href="../index.php" class="sign">Back</a>
    </header>
<!-- Result div to show search results -->
<section id="searchResults" class="result">    
</section>

<!-- content -->
 <main>

    <div class="card-group">
    <?php
// Query to fetch the latest 6 movies from the database
$recentmovies = "SELECT * FROM movies ORDER BY id DESC LIMIT 6";
$recentm = $conn->query($recentmovies);

// Check if there are recent movies in the result
if ($recentm->num_rows > 0) {
    // Loop through each movie and display
    while ($recent = $recentm->fetch_assoc()) {
        echo '<article class="card">';
        echo '<div class="product-buttons">';
        echo ' <a href="views.php?id=' . $recent['id'] . '" class="add-to-cart" target="_blank">';
        echo '<i class="fas fa-play"></i>';
        echo '</a>';
        echo '</div>';
        echo '<div class="product-image">';
        echo '<img src="' . $recent['poster_link'] . '" alt="Movie Poster">';
        echo '</div>';
        echo '<div class="product-name">' . htmlspecialchars($recent['name']) . '</div>';
        echo '<div class="product-price">' . htmlspecialchars($recent['translator']) . '</div>';
        echo '</article>';
    }
} else {
    // If no recent movies are found, display 6 placeholder cards
    for ($i = 0; $i < 6; $i++) {
        echo '<article class="card">';
        echo '<div class="product-buttons">';
        echo ' <button class="add-to-cart">';
        echo '<i class="fas fa-play"></i>';
        echo '</button>';
        echo '</div>';
        echo '<div class="product-image">';
        echo '<img src="https://via.placeholder.com/150x225?text=No+Image" alt="Placeholder Poster">';
        echo '</div>';
        echo '<div class="product-name">No Movie Available</div>';
        echo '<div class="product-price">No Translator Available</div>';
        echo '</article>';
    }
}
?>

 </div>
<!-- movie section -->
 <section class=" movies">
  <!-- populary  -->
    <div class="movie-category">

            <div class="head">
                <h1>All movie</h1>
            </div>
            <div class="items" id="movie-list">
    <!-- Movies will be loaded here -->
   
           </div>

<div id="loading" style="display: none;">
    <img src="../issets/img/arrow.jpg" alt="Loading...">
</div>

<div id="no-more-movies" style="display: none;">
    <p>No more movies to load.</p>
</div>
<script>
let page = 1; // Start at the first page
const limit = 24; // Load 24 movies at a time
let loading = false; // Prevent multiple simultaneous loads
let allMoviesLoaded = false; // Flag to stop loading when no more movies

function loadMoreMovies() {
    if (loading || allMoviesLoaded) return;
    loading = true;
    document.getElementById('loading').style.display = 'block';

    // Fetch movies with AJAX
    fetch(`load_mo.php?page=${page}&limit=${limit}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            const movieList = document.getElementById('movie-list');

            // Append the new movies to the list
            if (data.trim()) {
                movieList.insertAdjacentHTML('beforeend', data);
                page++;
                loading = false; // Allow new load
            } else {
                // No more movies to load
                document.getElementById('no-more-movies').style.display = 'block';
                allMoviesLoaded = true; // Prevent further loading
                window.removeEventListener('scroll', handleScroll); // Stop infinite scroll
            }
        })
        .catch(error => {
            console.error('Error loading more movies:', error);
            loading = false;
        });
}

function handleScroll() {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100 && !loading) {
        loadMoreMovies();
    }
}

// Initial load of movies
document.addEventListener('DOMContentLoaded', loadMoreMovies);

// Attach scroll event listener
window.addEventListener('scroll', handleScroll);
</script>

    </div>

 </section>
<!-- feedback input -->
 <section class="feed">
       <form action="../feed.php" method="post" class="feedback">
        <input type="hidden" value=""> 
        <textarea name="feed" placeholder="write your feedback here"></textarea>
        <button type="submit" name="sendfromall">Send</button>
      </form>
 </section>
 
 </main>

<!-- footer -->
<footer>
  <div class="footer-container">
      <div class="footer-logo">
          <img src="../issets/img/mbox.svg" alt="mbox website logo">
          <p>Mbox: Quality Films and Production</p>
          <?php
          $hiquery = "select * from users where id='$userid'";
          $hiresult = $conn->query($hiquery);
          $hifinal = $hiresult->fetch_assoc();
          ?>
           <p>Hi , <?php echo $hifinal['name'];?></p>
          <p>Contact us: <a href="mailto:irascreator@gmail.com">irascreator@gmail.com</a></p>
      </div>

      <div class="footer-links">
          <h3>Latest Update</h3><hr><br>
          <ul>          <ul><li><a href="../index.php">Back to Home</a></li>

          <?php
// Query to fetch 3 latest movies from the 'movies' table, ordered by id
$latestupdate = "SELECT * FROM movies ORDER BY id DESC LIMIT 3";
$latestMoviesResult = $conn->query($latestupdate);

// Loop through the results and display each movie
while ($latestMovie = $latestMoviesResult->fetch_assoc()) {
  echo '<li>';
  echo '<img src="' . $latestMovie['poster_link'] . '" style="width: 50px;height: auto;">';  // Use movie poster from the database
  echo '<a href="views.php?id=' . $latestMovie['id'] . '" target="_blank">' . $latestMovie['name'] . '</a>'; // Link to the movie details
  echo '<span class="post-meta">' . $latestMovie['category'] . ' | ' . date("F d, Y", strtotime($latestMovie['data_uploaded'])) . '</span>'; // Display category and formatted date
  echo '</li>';
}
?>
</ul>
      </div>

      <div class="footer-categories">
      <?php
// Queries to count the number of movies in each category
$actionCountQuery = "SELECT COUNT(*) AS count FROM movies WHERE category = 'action'";
$indiansCountQuery = "SELECT COUNT(*) AS count FROM movies WHERE category = 'indian'";
$sciFiCountQuery = "SELECT COUNT(*) AS count FROM movies WHERE category = 'scifi'";
$horrorCountQuery = "SELECT COUNT(*) AS count FROM movies WHERE category = 'horror'";
$comedyCountQuery = "SELECT COUNT(*) AS count FROM movies WHERE category = 'comedy'";
$cartoonCountQuery = "SELECT COUNT(*) AS count FROM movies WHERE category = 'cartoon'";
$othersCountQuery = "SELECT COUNT(*) AS count FROM movies WHERE category = 'other'";

// Query to count the total number of movies
$totalCountQuery = "SELECT COUNT(*) AS count FROM movies";

// Execute the queries
$actionCountResult = $conn->query($actionCountQuery)->fetch_assoc()['count'];
$indiansCountResult = $conn->query($indiansCountQuery)->fetch_assoc()['count'];
$sciFiCountResult = $conn->query($sciFiCountQuery)->fetch_assoc()['count'];
$horrorCountResult = $conn->query($horrorCountQuery)->fetch_assoc()['count'];
$comedyCountResult = $conn->query($comedyCountQuery)->fetch_assoc()['count'];
$cartoonCountResult = $conn->query($cartoonCountQuery)->fetch_assoc()['count'];
$othersCountResult = $conn->query($othersCountQuery)->fetch_assoc()['count'];
$totalCountResult = $conn->query($totalCountQuery)->fetch_assoc()['count'];
?>

<h3>Movies Categories Uploaded</h3>
<hr>
<ul>
  <li>Action <span><?php echo $actionCountResult; ?></span></li>
  <li>Indians <span><?php echo $indiansCountResult; ?></span></li>
  <li>Sci-Fi <span><?php echo $sciFiCountResult; ?></span></li>
  <li>Horror <span><?php echo $horrorCountResult; ?></span></li>
  <li>Comedy <span><?php echo $comedyCountResult; ?></span></li>
  <li>Cartoon <span><?php echo $cartoonCountResult; ?></span></li>
  <li>Others <span><?php echo $othersCountResult; ?></span></li>
  <li>Total <span><?php echo $totalCountResult; ?></span></li>
</ul>

</div>

      <div class="footer-social">
          <h3>Follow Us</h3><hr>
          <ul class="wrapper">
            <li class="icon facebook">
              <span class="tooltip">0724935532</span>
              <span><a href="tel+0724935532"><i class="fa fa-phone"></i></a></span>
            </li>
            <li class="icon twitter">
              <span class="tooltip">Twitter</span>
              <span><a href="https://www.x.com/mbox_rw"><i class="fab fa-twitter"></i></a></span>
            </li>
            <li class="icon instagram">
              <span class="tooltip">Instagram</span>
              <span><a href="https://www.instagram.com/mbox_rw/"><i class="fab fa-instagram"></i></a></span>
            </li>
            <li class="icon youtube">
              <span class="tooltip">Youtube</span>
              <span><a href=""><i class="fab fa-youtube"></i></a></span>
            </li>
          </ul>
      </div>
  </div>

  <div class="footer-bottom">
      <p>© 2024 Mbox Movies website | All Rights Reserved | Watch Now</p>
      <ul class="footer-nav">
          <li><a href="client/about.php">About Us</a></li>
          <li><a href="client/about.php">Privacy</a></li>
          <li><a href="client/about.php">Advertising</a></li>
      </ul>
  </div>
</footer>

</body>
</html>