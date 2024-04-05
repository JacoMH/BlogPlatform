<?php
    require_once('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home | Blog Site</title>
       <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
</head>
<body>
    <main class="container">
        <?php
        include("Includes/header1.php");
        ?>
        </div>

            <div class="search">
                <input  class = "search" type="text" placeholder="Enter..">
                <button type="submit"><i class="searchButton"></i>Search</button>
            </div>
          
            
        Most Liked Bloggers
        <?php 
            //carousel of maximum 3 top Bloggers in terms of total likes
            $blogger = "SELECT username, profileLikes, userID FROM user"; //found out how to gather a finite amount of most liked blogs here: https://stackoverflow.com/questions/4874731/how-can-i-select-the-top-10-largest-numbers-from-a-database-column-using-sql
            $bloggerResult = mysqli_query($mysqli, $blogger);
            $blogger = mysqli_fetch_assoc($bloggerResult);

            while (mysqli_fetch_assoc($bloggerResult)) {    //add profile pictures aswell when that works
                echo("<div class='postContent'>");
                echo($blogger['username']);
                echo($blogger['profileLikes']);
                echo($blogger['userID']);
                echo("</div>");
            }
        ?>
        <?php
        include("Includes/footer.php");
        ?>
    </main>
</body>
</html>