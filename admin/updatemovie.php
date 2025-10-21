<?php
// Get movie id using GET method
$movie_key = $_GET['id']; // Get the movie_id from the URL
if (empty($movie_key)) {
    header('Location:moviescontroller.php');
    exit();
}

// Build connection 
require('../dbconf.php');
if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])){
    header('Location:../index.php');
}
session_start();
if (isset($_SESSION['username'])|| isset($_COOKIE['username'])){
    $username = $_SESSION['username'];
}
else{
    header('Location:../common/home.php');
    exit();
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch movie details based on the movie_id
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $movie_key); // "i" is for integer
$stmt->execute();
$result = $stmt->get_result();
$detials = $result->fetch_assoc();

// If form is submitted to update the movie
if (isset($_POST['movie_update'])) {
    // Sanitize and assign form values to variables
    $movie_id = $_POST['id']; // Get the movie_id from the hidden input
    $name = $_POST['name'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $stts = $_POST['stts'];
    $category = $_POST['movie-category'];
    $relationship = $_POST['relationship'];
    $translator = $_POST['translator'];
    $poster_url = $_POST['poster_url'];
    $trailer_url = $_POST['trailer_url'];
    $download_link = $_POST['download_link'];
   
    // Prepare the UPDATE SQL query
    $updateQuery = "UPDATE movies SET name=?, title=?, relationship=?, description=?, category=?, translator=?, poster_link=?, trail_url=?, download_link=?, stts=? WHERE id=?";
    // Prepare and bind parameters
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssssssssi", $name, $title, $relationship, $description, $category, $translator, $poster_url, $trailer_url, $download_link, $stts, $movie_id);

    // Execute the query
    if ($stmt->execute()) {
        $message ="Movie updated successfully!";
    } else {
        $message= "Updating movie not completed!";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox | Movie Update</title>
    <link rel="stylesheet" href="../issets/styles/movieupdate.css">
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
</head>
<body>
    <div class="container">
        <!-- Section 1: Movie Image Display (30%) -->
        <div class="movie-image">
            <img src='<?php echo $detials["poster_link"]; ?>' alt="movie_profile" style="border-radius: 20px;">
        </div>

        <!-- Section 2: Movie Update Form (70%) -->
        <div class="movie-form">
            <h2>Movie Update</h2>
            <form action="" method="POST">
            <div class="dis_mess">
                <strong>
                    <?php if (!empty($message)) { echo $message; } ?>
                </strong>
            </div>
            <input type="hidden" name="id" value="<?php echo $detials['id']; ?>">

            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $detials['name']; ?>" required>

            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo $detials['title']; ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" required><?php echo $detials['description']; ?></textarea>

            <label for="status">Movie status:</label>
            <select name="stts" required>
                <option value="<?php echo $detials['stts']; ?>" selected><?php echo $detials['stts']; ?></option>
                <option value="none">none</option>
                <option value="popular">popular</option>
            </select>

            <label for="category">Category:</label>
            <select name="movie-category" required>
                <option value="<?php echo $detials['category']; ?>" selected><?php echo $detials['category']; ?></option>
                <option value="action">Action</option>
                <option value="scifi">Sci-Fi</option>
                <option value="indian">Indian</option>
                <option value="comedy">Comedy</option>
                <option value="drama">Drama</option>
                <option value="horror">Horror</option>
                <option value="cartoon">Cartoon</option>
                <option value="season">Season</option>
                <option value="other">Others</option>
            </select>

            <label for="relationship">Relationship:</label>
            <input type="text" name="relationship" value="<?php echo $detials['relationship']; ?>" required>

            <label for="translator">Translator:</label>
            <input type="text" name="translator" value="<?php echo $detials['translator']; ?>" required>

            <label for="poster_url">Poster URL:</label>
            <input type="text" name="poster_url" value="<?php echo $detials['poster_link']; ?>" required>

            <label for="trailer_url">Trailer URL:</label>
            <input type="text" name="trailer_url" value='<?php echo $detials["trail_url"]; ?>' required>

            <label for="download_link">Download Link:</label>
            <input type="text" name="download_link" value="<?php echo $detials['download_link']; ?>" required>

            <button type="submit" name="movie_update">Update Movie</button>
        </form>
        </div>
    </div>
</body>
</html>
