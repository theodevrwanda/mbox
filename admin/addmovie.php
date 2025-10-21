<?php
// Start session if needed
require('../dbconf.php');
session_start();
if (isset($_SESSION['username'])|| isset($_COOKIE['username'])){
    $username = $_SESSION['username'];
}
else{
    header('Location:../common/home.php');
    exit();
}// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    if(isset($_POST['addmovies']))
    {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $relationship = $_POST['relationship'];
    $description = $_POST['description'];
    $stts = $_POST['stts'];
    $category = (empty($_POST['movie-category'])) ? 'other' : $_POST['movie-category'];
    $translator = $_POST['translator'];
    $poster_url = $_POST['poster_url'];
    $trailer_url = $_POST['trailer_url'];
    $download_link = $_POST['Download_link'];
    }
    $Message = '';

    // Check for empty fields (you can also apply more validation if needed)
    if (empty($name)  || empty($relationship) || empty($description) || empty($translator) || empty($poster_url) || empty($trailer_url) || empty($download_link)) {
        $Message = "missing record.";
    } else {
        // Prepare the SQL query to insert data into the movies table
        $sql = "INSERT INTO `movies`(name, title, relationship, description, category, translator, poster_link, trail_url, download_link,stts) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $data_uploaded = date("Y-m-d H:i:s"); // current date and time

        // Bind parameters
        $stmt->bind_param("ssssssssss", $name, $title, $relationship, $description, $category, $translator, $poster_url,$trailer_url, $download_link,$stts);

        // Execute the query and check if the data was inserted successfully
        if ($stmt->execute()) {
            $Message = "Movie added successfully!";
        } else {
            $Message = "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox | Add Movie</title>
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
    <link rel="stylesheet" href="../issets/styles/addmovie.css">
</head>
<body>
    <div class="container">
        <!-- Section 1: Movie Image Display (30%) -->
        <div class="movie-image">
        </div>

        <!-- Section 2: Movie Add Form (70%) -->
        <div class="movie-form">
            <h2>Add Movie</h2>
            <form action="" method="POST">
                <div class="dis_mess">
                    <strong>
                    <?php
           if(!empty($Message)){echo $Message;}
                    ?>
                    </strong>
                </div>
                <label for="title">Name:</label>
                <input type="text"  name="name" placeholder="Enter movie Name" required>
    
                <label for="title">Title:</label>
                <input type="text"  name="title" placeholder="Enter movie title" required>

                <label for="relationship">relationship:</label>
                <input type="text"  name="relationship" placeholder="Enter movie relationship" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Enter movie description" required></textarea>

                <label for="release_date">movie status:</label>
                
                <select name="stts" id="movie-category">
                    <option value="none" disabled selected>Choose status</option>
                    <option value="none">none</option>
                    <option value="popular">popular</option>            
                </select>  

                <select name="movie-category" id="movie-category">
                    <option value="" disabled selected>Choose a category</option>
                    <option value="action">Action</option>
                    <option value="scifi">scifi</option>
                    <option value="indian">indian</option>
                    <option value="comedy">Comedy</option>
                    <option value="drama">Drama</option>
                    <option value="horror">Horror</option>
                    <option value="cartoon">Cartoon</option>
                    <option value="season">season</option>
                    <option value="other">other's</option>
                </select>    
                <label for="poster_url">Translator</label>
                <input type="text"  name="translator" placeholder="Enter Translator name" required>

                <label for="poster_url">Poster URL:</label>
                <input type="text" id="postetext" name="poster_url" placeholder="Enter poster image URL" required>

                <label for="trailer_url">Trailer URL:</label>
                <input type="text" id="trailer_url" name="trailer_url" placeholder="Enter trailer URL" required>

                <label for="poster_url">Download Link:</label>
                <input type="text" name="Download_link" placeholder="Enter Download Link " required>

                <button type="submit" name='addmovies'>Add Movie</button>
            </form>
        </div>
    </div>
</body>
</html>
