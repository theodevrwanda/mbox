<?php
require('../dbconf.php');
session_start();
if (isset($_SESSION['username']) || isset($_COOKIE['username'])){
      header('Location:../admin/dashboard.php');
      exit();
}

if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])){
    header('Location:../index.php');
    exit();
}

//  top 6 movies new upload on webisite...
$top = "SELECT * FROM movies WHERE stts = 'popular' ORDER BY id DESC LIMIT 6";
$result = $conn->query($top);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox | streaming</title>
    <!-- <link rel="stylesheet" href="../../assets/style/index.css"> -->
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
    <link rel="stylesheet" href="../issets/styles/home.css">
</head>
<body>
    <header>
        <div class="logo">
             <h1>Mbox</h1>
        </div>
      <a href="login.php"><button class="sign-in-btn">Sign In</button></a>
    </header>
    
    <main class="hero-section">
        <div class="background-overlay">
            <div class="content">
                <h2>Unlimited movies, TV shows, and more</h2>
                <p>Starts With Subscribe and Sign In</p>
            </div>
        </div>
    </main>
    <!-- trending movies show  -->
    <section class="movie-category" style="background: transparent;">
        <!-- trending movies section  -->
            <div class="category-header">
                <h2>Trending Movies<span>🔥</span>
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
                 $counter = 6;
                 $i =0;
                while ($topmovies = $result->fetch_assoc()) 
                        {
                        $i++;
                        echo '<div class="movies-container">';
                        echo '<div class="movie-item">';
                        echo '<div class="movie-image-container">';
                        echo '<img src="' . $topmovies['poster_link'] . '" alt="Movie 1">';
                        echo '<a href="../client/views.php?id=' . $topmovies['id'] . '"  class="play-icon">▶</a>';
                        echo '</div>';
                        echo '<h1>' . $topmovies['name'] . '</h1>';
                        echo '<p>' . $topmovies['title'] . '</p>';
                        echo '<small>'. $topmovies['translator'] . '</small>';
                        echo '<a class="button" href="../client/views.php?id=' . $topmovies['id'] . '"  style="vertical-align:middle; text-decoration:none;"><button style="background: transparent;border:none;color:white;"><span>Download </span></button></a>';
                        echo '</div>';
                        echo '</div>';
                        }
                        for ($i; $i < $counter ; $i++) 
                        {
                        echo '<div class="movies-container">';
                        echo '<div class="movie-item">';
                        echo '<div class="movie-image-container">';
                        echo '</div>';
                        echo '<h1>LOADING...</h1>';
                        echo '<p>Load Title..</p>';
                        echo '<small>Load Translator name..</small>';
                        echo '</div>';
                        echo '</div>';
                        }
?>        
                <!-- Add more movies as needed -->
            </section>
        </section>
<!-- reasons for this join -->
<section class="reasons-to-join">
    <h2>Why Join Us</h2>
    <div class="reason-cards">
        <div class="card">
            <p>Watch and Download Trending moveis for free</p>
            <span>🎬</span>
        </div>
        <div class="card">
            <p>Share Movies To Others</p>
            <span>🤝</span>
        </div>
        <div class="card">
            <p>Keep Favorite Movies To list</p>
            <span>❤️</span>
        </div>
        <div class="card">
            <p>Get All Update For This Website</p>
            <span>📺</span>
        </div>
    </div>
</section>

<div class="faq-section">
    <h2 class="faq-title">How It Works</h2>

    <div class="faq-item">
        <button>What is Mbox?</button>
        <div class="faq-content">
            <p>Mbox is a streaming movies Website that offers a wide variety of TV shows, movies, anime and more on thousands of internet-connected devices.</p>
        </div>
    </div>

    <div class="faq-item">
        <button>How much does Mbox cost?</button>
        <div class="faq-content">
            <p>Watch Mbox on your smartphone, tablet, Smart TV, laptop, or streaming device, all for free. But you must ensure you are Subscriber on YouTube channel and all social-Media.</p>
        </div>
    </div>

    <div class="faq-item">
        <button>Where can I watch?</button>
        <div class="faq-content">
            <p>Watch anywhere, anytime, on an unlimited number of devices. Sign in with your mbox account to watch instantly on the web at mbox.com from your personal computer or on any internet-connected device that offers mbox web app.</p>
        </div>
    </div>
    <div class="faq-item">
        <button>What can I watch on Mbox?</button>
        <div class="faq-content">
            <p>Mbox has an extensive library of feature films,TV shows or Seasons, anime, award-winning Mbox originals, and more.</p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Get all the FAQ buttons
    const faqButtons = document.querySelectorAll('.faq-item button');
    
    faqButtons.forEach(button => {
        // Add click event listener to each button
        button.addEventListener('click', function() {
            const faqContent = this.nextElementSibling; // Get the next sibling (the .faq-content)

            // Toggle the visibility of the corresponding content
            faqContent.style.display = (faqContent.style.display === 'block') ? 'none' : 'block';
        });
    });
});

    </script>
</div>
<!-- footer -->
<footer>
    <div class="footer-container">
        <div class="section">
            <h3>Most Recently</h3>
            <ul>
                <?php
              $name = "SELECT * FROM movies order by id desc limit 3";
              $nameresult = $conn->query($name);
              while ($threename=$nameresult->fetch_assoc()){
             echo'<li><a href="#">'.$threename['name'].'</li>';
              }
       

                ?>
               
            </ul>
        </div>

        <div class="section">
            <h3>Social Media</h3>
            <ul>
                <li><a href="#">YouTube</a></li>
                <li><a href="#">Instagram</a></li>
                <li><a href="#">Tik Tok</a></li>
            </ul>
        </div>

        <div class="section">
            <h3>Contact Us</h3>

            <ul>
                <li><a href="#">Gmail</li>
                <li><a href="mailto:irascreator@gmail.com">SendMe</a></li>
            </ul>
        </div>
    </div>
    <div class="copyright">
        <p>Copyright © 2024 Mbox Films. All Rights Reserved.</p>
        <p><a href="#">Privacy Policy and Terms & Conditions</a></p>
    </div>
</footer>
</body>
</html>
