
<?php
// Database connection
require('../dbconf.php');
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

// Fetch data from movies table
$sql = "SELECT * FROM movies ORDER BY id desc";
$result = $conn->query($sql);
// total movies
$total = "SELECT * FROM movies";
$tmr = $conn->query($total);

// live search 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['query']) && !empty($_POST['query'])) {
        // Search for movies based on the search query
        $searchQuery = $_POST['query'];
        $stmt = $conn->prepare("SELECT * FROM movies WHERE name LIKE CONCAT('%', ?, '%') OR stts LIKE CONCAT('%', ?, '%') OR translator LIKE CONCAT('%', ?, '%') OR category LIKE CONCAT('%', ?, '%') ORDER BY id desc");
        $stmt->bind_param('ssss', $searchQuery,$searchQuery,$searchQuery,$searchQuery);
    } else {
        // If no search query, select all movies
        $stmt = $conn->prepare("SELECT * FROM movies");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "<td>" . $row["category"] . "</td>";
            echo "<td>" . $row["translator"] . "</td>";
            echo "<td>" . $row["stts"] . "</td>";
            echo "<td>
                    <a href='updatemovie.php?id=" . $row["id"] . "' target='_blank'><button class='btn-edit'>Update</button></a><br><br>
                    <a href='deletem.php?id=" . $row["id"] . "'><button class='btn-delete'>Delete</button></a><br><br>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'><center>no movies uploaded (start,contain,end)with this word entered...</center></td></tr>";
    }

    $stmt->close();
    exit; // End AJAX response here
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox  | Movies Management</title>
    <link rel="stylesheet" href="../issets/styles/moviescontroller.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="userprofire">
        <img src="../issets/avatar/mlogo.jpeg" alt="">
        </div>
     <ul>
         <li><small>Mbox Admin</small></li>
     </ul>
      <?php
$wallpaper = "SELECT * FROM movies ORDER BY id desc  LIMIT 1";
$wallpaperR = $conn->query($wallpaper);
$wallR = $wallpaperR->fetch_assoc();
if($wallR){
    echo '<img src='.$wallR['poster_link'].' alt="" style="border-radius:  10px;width: 80%;left: 20px;margin-left: 20px;height: 200px;">';
}
else
{
    echo '<img src="../../assets/img/Black_Adam.jpg" alt="" style="border-radius:  10px;width: 80%;left: 20px;margin-left: 20px;height: 200px;">';
}
   ?>
     <ul style="position: absolute;bottom: 0;">
        
     <li><a href="feedback.php">FeeBack Management</a></li>
         <li><a href="moviescontroller.php">Movies Management</a></li>
         <li><a href="usercontroller.php" >User Management</a></li>
         <li><a href="dashboard.php">Dashboard</a></li>
         <li><a href="../logout.php">Log Out</a></li>
     </ul>
 </div>
 <!-- main content -->
   <main class="content"> 
    <!-- navbar  -->
       <nav>
        <div class="logo">
      <h1>Mbox</h1>
        </div>
  
        <!-- Search form -->
<form action="#" method="post" class="search" id="searchForm">
<input type="text" id="searchInput" onkeyup="liveSearch()" placeholder="Search for movies...">
    <button type="button" onclick="liveSearch()"><i class="fa fa-search"></i></button>
</form>
      <!-- ajax for live search  -->
      <script>
        function liveSearch() {
            var searchQuery = document.getElementById('searchInput').value;

            // Create an AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true); // Sending to the same PHP file
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Update the table body with search results
                    document.getElementById('searchResults').innerHTML = xhr.responseText;
                }
            };

            // Send the search query to the PHP part
            xhr.send('query=' + encodeURIComponent(searchQuery));
        }
    </script>
<div  class="result"></div>
<a href="addmovie.php" target="_blank"><button  class="button">ADD Movies</button></a>

       </nav>
       <!-- database information  -->
        <section class="allcontent">
            
            <section class="dashboard">
                <h1>Display Dashboard</h1>
                <div class="stats">

                    <div class="stat-box">
                        <h2>Total Movies Uploaded</h2>
                        <p><?php 
                          $totalm;
                          while($rows = $tmr->fetch_assoc()) {
                              $totalm ++;
                          }
                        echo $totalm;
                        ?></p>
                    </div> 
                </div>
            </section>
            <table class="user-table">
        <thead>
            <tr>
                <th>Movies_ID</th>
                <th>Name</th>
                <th>Title</th>
                <th>Description</th>
                <th>Genre</th>
                <th>Translator</th>
                <th>status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="searchResults">
            <!-- This is where the search results will appear dynamically -->
            <?php
            // Show all data on page load if no AJAX request is made
            $stmt = $conn->prepare("SELECT * FROM movies order by id desc");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                  
                    echo "<td>" . $row["category"] . "</td>";
                    echo "<td>" . $row["translator"] . "</td>";
                    echo "<td>" . $row["stts"] . "</td>";
                    echo "<td>
                            <a href='updatemovie.php?id=" . $row["id"] . "' target='_blank'><button class='btn-edit'>Update</button></a><br><br>
                            <a href='deletem.php?id=" . $row["id"] . "'><button class='btn-delete'>Delete</button></a><br><br>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'><center>No movies Uploaded..</center></td></tr>";
            }

            $stmt->close();
            ?>
        </tbody>
    </table>
        </section>
   </main>
    
</body>
</html>
<?php
// Close connection
$conn->close();
?>