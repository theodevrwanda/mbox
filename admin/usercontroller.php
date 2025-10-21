<?php
require('../dbconf.php');
session_start();
if (isset($_SESSION['username'])|| isset($_COOKIE['username'])){
    $username = $_SESSION['username'];
}
else{
    header('Location:../common/home.php');
    exit();
}
$displayuser = "SELECT * FROM myusers";
$result = $conn->query($displayuser);
$number = $conn->query($displayuser);
$nuser = 0;
while ($tn = $number->fetch_assoc()) {
   $nuser++;
}

// live search 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['query']) && !empty($_POST['query'])) {
        // Search for movies based on the search query
        $searchQuery = $_POST['query'];
        $stmt = $conn->prepare("SELECT * FROM myusers WHERE name LIKE CONCAT('%', ?, '%') OR email LIKE CONCAT('%', ?, '%')");
        
        // Correct the bind_param to include two 's' for the two placeholders
        $stmt->bind_param('ss', $searchQuery, $searchQuery);
    } else {
        // If no search query, select all movies
        $stmt = $conn->prepare("SELECT * FROM myusers");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["password"] . "</td>";
            echo "<td>
                    <a href='updateuser.php?id=" . $row["id"] . "' target='_blank'><button class='btn-edit'>Update</button></a><br><br>
                    <a href='deleteu.php?id=" . $row["id"] . "'><button class='btn-delete'>Delete</button></a><br><br>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'><center>No User found..</center></td></tr>";
    }

    $stmt->close();
    exit; // End AJAX response here
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox  | user  Management</title>
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
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

     <img src="../issets/avatar/avatar1.png" alt="" style="border-radius:  10px;width: 80%;left: 20px;margin-left: 20px;height: 200px;">
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

        <form action="#" method="post">
        <input type="text" id="searchInput" onkeyup="liveSearch()" placeholder="Search for movies...">
            <button type="submit" name="searchbtn"><i class="fa fa-search"></i></button>
        </form>
        <!-- ajax live search  -->
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
         <a href="adduser.php" target="_blank"><button class="button">Register other</button></a>

       </nav>
       <!-- database information  -->
        <section class="allcontent">
            
            <section class="dashboard">
                <h1>Display Dashboard</h1>
                <div class="stats">

                    <div class="stat-box">
                        <h2>Total Users Registed</h2>
                        <p><?php echo $nuser;?></p>
                    </div> 
                </div>
            </section>
    <!-- all movies list in table -->
    <table class="user-table">
        <thead>
            <tr>
                <th>User_ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>action</th>
            
            </tr>
        </thead>
        <tbody id="searchResults">
            <!-- This is where the search results will appear dynamically -->
            <?php
            // Show all data on page load if no AJAX request is made
            $stmt = $conn->prepare("SELECT * FROM myusers order by id desc");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["password"] . "</td>";
                    echo "<td>
                            <a href='updateuser.php?id=" . $row["id"] . "' target='_blank'><button class='btn-edit'>Update</button></a><br><br>
                            <a href='deleteu.php?id=" . $row["id"] . "'><button class='btn-delete'>Delete</button></a><br><br>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'><center>No User Registed..</center></td></tr>";
            }

            $stmt->close();
            ?>
        </tbody>
    </table>
    
        </section>
   </main>
    
</body>
</html>