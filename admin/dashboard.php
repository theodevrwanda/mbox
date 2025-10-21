
<?php
// Database connection
require('../dbconf.php');
// Check connection
session_start();
if (isset($_SESSION['username'])|| isset($_COOKIE['username'])){
    $username = $_SESSION['username'];
}
else{
    header('Location:../common/home.php');
    exit();
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch recently movie 5 from movies table
$recetltym = "SELECT * FROM movies ORDER BY id DESC limit 10";
$rmr = $conn->query($recetltym);
// calc tottal number of movies with in database
$tms = "SELECT * FROM movies ";
$tmsr = $conn->query($tms);
// total movies
$totalmovies = 0;
while($tm = $tmsr->fetch_assoc()) {
    $totalmovies ++;
}
// calc total users number  with in database
$tur = "SELECT * FROM myusers ";
$turr = $conn->query($tur);
// total users
$totalusers = 0;
while($tuser = $turr->fetch_assoc()) {
    $totalusers ++;
}
// select fetch user  ....
$users = "SELECT * FROM myusers ORDER BY id DESC LIMIT 20";
$userr = $conn->query($users);
?> 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox | Admin Dashboard </title>
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
    <link rel="stylesheet" href="../issets/styles/dashboard.css">
</head>
<body>
        <div class="sidebar">
           <div class="userprofire">
            <img src="../issets/avatar/mlogo.jpeg" alt="">
           </div>
        <ul>
            <li><small><?php echo $username;?> Admin</small></li>
        </ul>
        <?php
$wallpaper = "SELECT * FROM movies ORDER BY id DESC LIMIT 1";
$wallpaperR = $conn->query($wallpaper);
$wallR = $wallpaperR->fetch_assoc();
if($wallR){
    echo '<img src='.$wallR['poster_link'].' alt="" style="border-radius:  10px;width: 80%;left: 20px;margin-left: 20px;height: 200px;">';
}
else
{
    echo '<img src="../issets/img/Black_Adam.jpg" alt="" style="border-radius:  10px;width: 80%;left: 20px;margin-left: 20px;height: 200px;">';
}
   ?>
        <ul style="position: absolute;bottom: 0;">
            <li><a href="feedback.php">feedback Management</a></li>
            <li><a href="moviescontroller.php">Movies Management</a></li>
            <li><a href="usercontroller.php">User Management</a></li>
            <li><a href="../logout.php">Log Out</a></li>
        </ul>
    </div>

    <div class="main-content">
        <!-- Dashboard Section -->
        <section id="dashboard">
            <h1>Dashboard</h1>
            <div class="stats">
                <div class="stat-box">
                    <h2>Total Movies</h2>
                    <p>
                        <?php
                          echo $totalmovies;
                        ?>
                    </p>
                </div>
                <div class="stat-box">
                    <h2>Total Users</h2>
                    <p><?php echo $totalusers?></p>
                </div>
                <div class="stat-box">
                    <h2>Total Feedbacks</h2>
                    <?php
                    $feed = "select * from feedback";
                    $r =$conn->query($feed);
                    for($feedn = 0 ; $res = $r->fetch_assoc();$feedn++){

                    }
                    ?>
                    <p><?php echo $feedn;?></p>
                </div>
            </div>
        </section>

        <!-- Movies Management Section -->
        <div class="category-header">
            <h2>Recently Movies
            <hr>
            </h2>
             <div class="nav-buttons">
                <button id="prevBtn">❮</button>
                <button id="nextBtn">❯</button>
             </div>
        </div>
    
          <section class="movie-row" id="movieRow">
            <!-- Movie items (initially visible) -->

            <?php
                        while($movies = $rmr->fetch_assoc()) 
                       
                        {
                            echo '<div class="movies-container">';
                            echo '<div class="movie-item">';
                            echo '<div class="movie-image-container">';
                            echo '<img src="' . $movies['poster_link'] . '" alt="Movie 1">';
                            echo '<a href="private/pages/views.php?movie_id=' . $movies['id'] . '" target="_blank" class="play-icon">▶</a>';
                            echo '</div>';
                            echo '<h1>' . $movies['name'] . '</h1>';
                            echo '<p>' . $movies['title'] . '</p>';
                            echo '<small>' . $movies['translator'] . '</small>';
                            echo '<button class="download"><a style="text-decoration:none;color:white;font-size:16px;" href="' . $movies['download_link'] . '">Latest Uploaded</a></button>';
                            echo '</div>';
                        echo '</div>';
                        }
                        
                        ?>

  <!-- Add more movies as needed -->
           </section>
    </section>

        <!-- User Management Section -->
        <section id="users">
            <h1>Recently Users</h1>
            <div class="user-list">
                <table>
                    <thead>
                        <tr><th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>password</th>
                        </tr>
                    </thead>
                    <tbody>
                             <?php
                         while($user = $userr->fetch_assoc()) 
                         {  
                            echo '<tr>';

                            echo '<td>'.$user['id'].'</td>';
                            echo ' <td>'.$user['name'].'</td>';
                            echo '<td>'.$user['email'].'</td>';
                            echo ' <td>'.$user['password'].'</td>';
                            
                            echo '</tr>';
                         }
                             ?>
                        <!-- Repeat for more users -->
                    </tbody>
                </table>
            </div>
        </section>
        <footer 
        style="
        width: 100%;
        height: 40px;
        background: grey;
        display: flex;
        justify-content: center;
        align-items: center;
        ">pruduct of Rwadev : &lt/ Theo iradukunda> </footer>
    </div>
</body>
</html>
