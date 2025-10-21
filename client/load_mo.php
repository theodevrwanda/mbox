<?php
require '../dbconf.php'; // Include your database connection file

// Get the page and limit values
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 24;
$offset = ($page - 1) * $limit;

// Query to fetch movies with pagination
$moviesall = "SELECT * FROM movies ORDER BY id DESC LIMIT $limit OFFSET $offset";
$actionMoviesResult = $conn->query($moviesall);

// Check if there are movies
if ($actionMoviesResult->num_rows > 0) {
    // Loop through the fetched movies and display
    while ($moviesallR = $actionMoviesResult->fetch_assoc()) {
        echo '<div class="item" style="background: url(' . $moviesallR['poster_link'] . ');background-repeat: no-repeat;background-size: cover;background-position: center;">';
        echo '<div class="content">';
        echo '<h2 class="title">' . htmlspecialchars($moviesallR['name']) . '<br><small>' . htmlspecialchars($moviesallR['translator']) . '</small></h2>';
        echo '<p class="copy">' . htmlspecialchars($moviesallR['description']) . '</p>';
        echo '<a class="button" href="views.php?id='.$moviesallR['id'].'" style="vertical-align:middle; text-decoration:none;"  target="_blank"><button style="background: transparent; border:none; color:white;"><span>Download </span></button></a>';
        echo '</div> </div>';
    }
} 
?>
