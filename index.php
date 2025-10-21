<?php
// import database setting in index file
require('dbconf.php');
session_start();
if (isset($_SESSION['username']) || isset($_COOKIE['username'])){
      header('Location:admin/dashboard.php');
      exit();
}elseif(isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])){
    $userid = (empty($_SESSION['user_id'])) ? $_COOKIE['user_id'] : $_SESSION['user_id'];
}else{
    header('Location:common/home.php');
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
          echo '<h3><a href="client/views.php?id=' . $row['id'] . '" target="_blank">' . $row['name'] . '</a></h3>';            
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
    <title>Mbox | agasobanuye</title>
    <link rel="shortcut icon" href="#" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="issets/styles/index.css">
    <link rel="shortcut icon" href="issets/img/mbox.svg" type="image/x-icon">
</head>
<body>
    <!-- header navbar -->
    <header>
        <div class="logo">  
            <img src="issets/img/newmbox.png" alt="Mbox Logo" class="web-logo">
        </div>

        <!-- navbar list -->
        <nav>
            <ul>
                <li><a href="index.php" class="active">Home<span></span></a></li>
                <li><a href="client/allmovies.php"  >All Movies<span></span></a></li>
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
<a href="logout.php" class="sign">Logout</a>
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
        echo ' <a href="client/views.php?id=' . $recent['id'] . '" class="add-to-cart" target="_blank">';
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
 <section class="movies">
  <!-- populary  -->
    <div class="movie-category">

            <div class="head">
                <h1>Most Populary</h1>
            </div>
   
            <div class="items">
            <?php
// Query to fetch the latest 12 movies from the database
$populary = "SELECT * FROM movies WHERE stts='popular' ORDER BY id DESC LIMIT 12";
$actionMoviesResult = $conn->query($populary);

// Define the number of blocks to display
$totalBlocks = 12;
$i=0;
// Check if there are movies in the result
if ($actionMoviesResult->num_rows > 0) {
    // Loop through each movie and display
    while ($popularyR = $actionMoviesResult->fetch_assoc()) {
        $i++;
        echo ' <div class="item" style="background: url(' . $popularyR['poster_link'] . ');background-repeat: no-repeat;background-size: cover;background-position: center;">';
        echo '<div class="content">';
        echo '<h2 class="title">' . htmlspecialchars($popularyR['name']) . '<br><small>' . htmlspecialchars($popularyR['translator']) . '</small></h2>';
        echo '<p class="copy">' . htmlspecialchars($popularyR['description']) . '</p>';
        echo '<a class="button" href="client/views.php?id='.$popularyR['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
        echo '</div></div>';
    }
} 
    // If no movies are found, display 12 placeholder blocks
    for ($i; $i < $totalBlocks; $i++) {
        echo ' <div class="item" style="background: #ccc; height: 300px; display: flex; align-items: center; justify-content: center;">';
        echo '<div class="content">';
        echo '<h2 class="title">No Movie Available</h2>';
        echo '<p class="copy">No description available.</p>';
        echo '<a class="button" href="#" style="vertical-align:middle; text-decoration:none;">';
        echo '<button style="background: transparent;border:none;color:white;"><span>Wait </span></button>';
        echo '</a>';
        echo '</div></div>';
    }

?>
       
  </div>
        
    </div>
      <!-- Action  -->
      <div class="movie-category">

        <div class="head">
            <h1>Action Movies</h1>
        </div>

        <div class="items">
        
        <?php
// Query to get movies in the "action" category, limited to 6
$populary = "SELECT * FROM movies WHERE category = 'action' ORDER BY id LIMIT 6";
$actionMoviesResult = $conn->query($populary);

// Initialize counter for the number of movies fetched
$counter = 0;

// Fetch and display the movies
while ($action = $actionMoviesResult->fetch_assoc()) {
    echo '<div class="item" style="background: url(' . $action['poster_link'] . '); background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">' . $action['name'] . '<br><small>' . $action['translator'] . '</small></h2>';
    echo '<p class="copy">' . $action['description'] . '</p>';
    echo '<a class="button" href="client/views.php?id='.$action['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each movie displayed
}

// Add empty divs for the remaining slots if there are fewer than 6 movies
while ($counter < 6) {
    echo '<div class="item" style="background: #ddd; background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">No Content<br><small>Translator</small></h2>';
    echo '<p class="copy">Description not available.</p>';
    echo '<a class="button" href="#" style="vertical-align:middle; text-decoration:none;"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each empty slot
}
?>

</div>
<center style="margin-top:20px;"><a href="client/more.php?category=action" style="color:red;font-size:20px;">more movies</a></center>
  <!-- Hot Season  -->
  <div class="movie-category">

    <div class="head">
        <h1>Hot Season</h1>
    </div>

    <div class="items">
    
    <?php
// Query to get movies in the "season" category, limited to 6
$populary = "SELECT * FROM movies WHERE category = 'season' ORDER BY id LIMIT 6";
$actionMoviesResult = $conn->query($populary);

// Initialize counter for the number of movies fetched
$counter = 0;

// Fetch and display the movies
while ($hotseason = $actionMoviesResult->fetch_assoc()) {
    echo '<div class="item" style="background: url(' . $hotseason['poster_link'] . '); background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">' . $hotseason['name'] . '<br><small>' . $hotseason['translator'] . '</small></h2>';
    echo '<p class="copy">' . $hotseason['description'] . '</p>';
    echo '<a class="button" href="client/views.php?id='.$hotseason['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each movie displayed
}

// Add empty divs for the remaining slots if there are fewer than 6 movies
while ($counter < 6) {
    echo '<div class="item" style="background: #ddd; background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">No Content<br><small>Translator</small></h2>';
    echo '<p class="copy">Description not available.</p>';
    echo '<a class="button" href="#" style="vertical-align:middle; text-decoration:none;"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each empty slot
}
?>


</div>
<center style="margin-top:20px;"><a href="client/more.php?category=season" style="color:red;font-size:20px;">more movies</a></center>
  <!-- indian  -->
  <div class="movie-category">

    <div class="head">
        <h1>Indians Movies</h1>
    </div>

    <div class="items">
    
    <?php
// Query to get movies in the "indian" category, limited to 6
$populary = "SELECT * FROM movies WHERE category = 'indian' ORDER BY id LIMIT 6";
$actionMoviesResult = $conn->query($populary);

// Initialize counter for the number of movies fetched
$counter = 0;

// Fetch and display the movies
while ($indian = $actionMoviesResult->fetch_assoc()) {
    echo '<div class="item" style="background: url(' . $indian['poster_link'] . '); background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">' . $indian['name'] . '<br><small>' . $indian['translator'] . '</small></h2>';
    echo '<p class="copy">' . $indian['description'] . '</p>';
    echo '<a class="button" href="client/views.php?id='.$indian['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each movie displayed
}

// Add empty divs for the remaining slots if there are fewer than 6 movies
while ($counter < 6) {
    echo '<div class="item" style="background: #ddd; background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">No Content<br><small>Translator</small></h2>';
    echo '<p class="copy">Description not available.</p>';
    echo '<a class="button" href="#" style="vertical-align:middle; text-decoration:none;"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each empty slot
}
?>


</div>
<center style="margin-top:20px;"><a href="client/more.php?category=indian" style="color:red;font-size:20px;">more movies</a></center>
  <!-- Drama & Romance  -->
  <div class="movie-category">

    <div class="head">
        <h1>Drama & Romance</h1>
    </div>

    <div class="items">
    
    <?php
// Query to get movies in the "drama" category, limited to 6
$populary = "SELECT * FROM movies WHERE category = 'drama' ORDER BY id LIMIT 6";
$actionMoviesResult = $conn->query($populary);

// Initialize counter for the number of movies fetched
$counter = 0;

// Fetch and display the movies
while ($drama = $actionMoviesResult->fetch_assoc()) {
    echo '<div class="item" style="background: url(' . $drama['poster_link'] . '); background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">' . $drama['name'] . '<br><small>' . $drama['translator'] . '</small></h2>';
    echo '<p class="copy">' . $drama['description'] . '</p>';
    echo '<a class="button" href="client/views.php?id='.$drama['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each movie displayed
}

// Add empty divs for the remaining slots if there are fewer than 6 movies
while ($counter < 6) {
    echo '<div class="item" style="background: #ddd; background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">No Content<br><small>Translator</small></h2>';
    echo '<p class="copy">Description not available.</p>';
    echo '<a class="button" href="client/views.php?id='.$counter['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each empty slot
}
?>
 

</div>
<center style="margin-top:20px;"><a href="client/more.php?category=drama" style="color:red;font-size:20px;">more movies</a></center>
      <!-- Sci-Fi  -->
      <div class="movie-category">

        <div class="head">
            <h1>Sci-Fi Movies</h1>
        </div>

        <div class="items">
        <?php
// Query to get movies in the "scifi" category, limited to 6
$populary = "SELECT * FROM movies WHERE category = 'scifi' ORDER BY id LIMIT 6";
$actionMoviesResult = $conn->query($populary);

// Initialize counter for the number of movies fetched
$counter = 0;

// Fetch and display the movies
while ($scifi = $actionMoviesResult->fetch_assoc()) {
    echo '<div class="item" style="background: url(' . $scifi['poster_link'] . '); background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">' . $scifi['name'] . '<br><small>' . $scifi['translator'] . '</small></h2>';
    echo '<p class="copy">' . $scifi['description'] . '</p>';
    echo '<a class="button" href="client/views.php?id='.$scifi['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each movie displayed
}

// Add empty divs for the remaining slots if there are fewer than 6 movies
while ($counter < 6) {
    echo '<div class="item" style="background: #ddd; background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">No Content<br><small>Translator</small></h2>';
    echo '<p class="copy">Description not available.</p>';
    echo '<a class="button" href="#" style="vertical-align:middle; text-decoration:none;"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each empty slot
}
?>

    
</div>
<center style="margin-top:20px;"><a href="client/more.php?category=scifi" style="color:red;font-size:20px;">more movies</a></center>
  <!-- Horror  -->
  <div class="movie-category">

    <div class="head">
        <h1>Horror Movies</h1>
    </div>

    <div class="items">
    
    <?php
// Query to get movies in the "horror" category, limited to 6
$populary = "SELECT * FROM movies WHERE category = 'horror' ORDER BY id LIMIT 6";
$actionMoviesResult = $conn->query($populary);

// Initialize counter for the number of movies fetched
$counter = 0;

// Fetch and display the movies
while ($horror = $actionMoviesResult->fetch_assoc()) {
    echo '<div class="item" style="background: url(' . $horror['poster_link'] . '); background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">' . $horror['name'] . '<br><small>' . $horror['translator'] . '</small></h2>';
    echo '<p class="copy">' . $horror['description'] . '</p>';
    echo '<a class="button" href="client/views.php?id='.$horror['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each movie displayed
}

// Add empty divs for the remaining slots if there are fewer than 6 movies
while ($counter < 6) {
    echo '<div class="item" style="background: #ddd; background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">No Content<br><small>Translator</small></h2>';
    echo '<p class="copy">Description not available.</p>';
    echo '<a class="button" href="#" style="vertical-align:middle; text-decoration:none;"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each empty slot
}
?>
 

</div>
<center style="margin-top:20px;"><a href="client/more.php?category=horror" style="color:red;font-size:20px;">more movies</a></center>
  <!-- comedy movies  -->
  <div class="movie-category">

    <div class="head">
        <h1>Comedy Movies</h1>
    </div>

    <div class="items">
    
    <?php
// Query to get comedy movies, limited to 6
$populary = "SELECT * FROM movies WHERE category = 'comedy' ORDER BY id LIMIT 6";
$actionMoviesResult = $conn->query($populary);

// Initialize counter for the number of movies fetched
$counter = 0;

// Fetch and display the movies
while ($comedy = $actionMoviesResult->fetch_assoc()) {
    echo '<div class="item" style="background: url(' . $comedy['poster_link'] . '); background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">' . $comedy['name'] . '<br><small>' . $comedy['translator'] . '</small></h2>';
    echo '<p class="copy">' . $comedy['description'] . '</p>';
    echo '<a class="button" href="client/views.php?id='.$comedy['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each movie displayed
}

// Add empty divs for the remaining slots if there are fewer than 6 movies
while ($counter < 6) {
    echo '<div class="item" style="background: #ddd; background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">No Content<br><small>Translator</small></h2>';
    echo '<p class="copy">Description not available.</p>';
    echo '<a class="button" href="#" style="vertical-align:middle; text-decoration:none;"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each empty slot
}
?>

</div>
<center style="margin-top:20px;"><a href="client/more.php?category=comedy" style="color:red;font-size:20px;">more movies</a></center>
  <!-- populary  -->
  <div class="movie-category">

    <div class="head">
        <h1>Anim & Cartoon</h1>
    </div>

    <div class="items">
    
    <?php
// Query to get cartoon movies, limited to 6
$populary = "SELECT * FROM movies WHERE category = 'cartoon' ORDER BY id LIMIT 6";
$actionMoviesResult = $conn->query($populary);

// Initialize counter for the number of movies fetched
$counter = 0;

// Fetch and display the movies
while ($cartoon = $actionMoviesResult->fetch_assoc()) {
    echo '<div class="item" style="background: url(' . $cartoon['poster_link'] . '); background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">' . $cartoon['name'] . '<br><small>' . $cartoon['translator'] . '</small></h2>';
    echo '<p class="copy">' . $cartoon['description'] . '</p>';
    echo '<a class="button" href="client/views.php?id='.$cartoon['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each movie displayed
}

// Add empty divs for the remaining slots if there are fewer than 6 movies
while ($counter < 6) {
    echo '<div class="item" style="background: #ddd; background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">No Content<br><small>Translator</small></h2>';
    echo '<p class="copy">Description not available.</p>';
    echo '<a class="button" href="#" style="vertical-align:middle; text-decoration:none;"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each empty slot
}
?>
 

</div>
<center style="margin-top:20px;"><a href="client/more.php?category=cartoon" style="color:red;font-size:20px;">more movies</a></center>
  <!-- Others  -->
  <div class="movie-category">

    <div class="head">
        <h1>Others Movies</h1>
    </div>

    <div class="items">
    
    <?php
// Query to get movies in the "others" category, limited to 6
$populary = "SELECT * FROM movies WHERE category = 'other' ORDER BY id DESC LIMIT 6";
$actionMoviesResult = $conn->query($populary);

// Initialize counter for the number of movies fetched
$counter = 0;

// Fetch and display the movies
while ($others = $actionMoviesResult->fetch_assoc()) {
    echo '<div class="item" style="background: url(' . $others['poster_link'] . '); background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">' . $others['name'] . '<br><small>' . $others['translator'] . '</small></h2>';
    echo '<p class="copy">' . $others['description'] . '</p>';
    echo '<a class="button" href="client/views.php?id='.$others['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each movie displayed
}

// Add empty divs for the remaining slots if there are fewer than 6 movies
while ($counter < 6) {
    echo '<div class="item" style="background: #ddd; background-repeat: no-repeat; background-size: cover; background-position: center;">';
    echo '<div class="content">';
    echo '<h2 class="title">No Content<br><small>Translator</small></h2>';
    echo '<p class="copy">Description not available.</p>';
    echo '<a class="button" href="#" style="vertical-align:middle; text-decoration:none;"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
    echo '</div></div>';

    $counter++; // Increment counter for each empty slot
}
?>


</div>
<center style="margin-top:20px;"><a href="client/more.php?category=other" style="color:red;font-size:20px;">more movies</a></center>
 </section>
<!-- feedback input -->
 <section class="feed">
       <form action="feed.php" method="post" class="feedback">
        <textarea name="feed" placeholder="write your feedback here"></textarea>
        <button type="submit" name="sendfromindex">Send</button>
      </form>
 </section>
 
 </main>
<!-- footer -->
<footer>
  <div class="footer-container">
      <div class="footer-logo">
          <img src="issets/img/mbox.svg" alt="mbox website logo">
          <p>Mbox: Quality Films and Production</p>
          <?php
          $hiquery = "select * from users where id='$userid'";
          $hiresult = $conn->query($hiquery);
          $hifinal = $hiresult->fetch_assoc();
          ?>
          <p>Hi ,<a><?php echo $hifinal['email'];?></a></p>
          <p>Contact us: <a href="mailto:irascreator@gmail.com">irascreator@gmail.com</a></p>
      </div>

      <div class="footer-links">
          <h3>Latest Update</h3><hr><br>
          <ul><li><a href="client/allmovies.php">See all Movies</a></li>
          <?php
// Query to fetch 3 latest movies from the 'movies' table, ordered by id
$latestupdate = "SELECT * FROM movies ORDER BY id DESC LIMIT 3";
$latestMoviesResult = $conn->query($latestupdate);

// Loop through the results and display each movie
while ($latestMovie = $latestMoviesResult->fetch_assoc()) {
  echo '<li>';
  echo '<img src="' . $latestMovie['poster_link'] . '" style="width: 50px;height: auto;">';  // Use movie poster from the database
  echo '<a href="client/views.php?id=' . $latestMovie['id'] . '" target="_blank">' . $latestMovie['name'] . '</a>'; // Link to the movie details
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
              <span><a href="tel+072495532"><i class="fa fa-phone"></i></a></span>
            </li>
            <li class="icon twitter">
              <span class="tooltip">Twitter</span>
              <span><a href="https://www.x.com/mbox_rw"><i class="fab fa-twitter"></i></a></span>
            </li>
            <li class="icon instagram">
              <span class="tooltip">Instagram</span>
              <span><a href="https://www.instagram.com/mbox_rw"><i class="fab fa-instagram"></i></a></span>
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