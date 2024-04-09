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
        session_start();
        if (!empty($_SESSION['username'])) {
            require_once('includes/profileShortcut.php');
        }
        else {
            require_once('includes/loginReg.php');
        }
        ?>
        </div>
        <search>
            <form class="search" method="GET">
                <input  class = "search" name="searchQuery" type="text" placeholder="Search For User..">
                <button type="submit" name="searchButton">Search</button>
            </form>
        </search>
            <?php
                //get search url
                if (isset($_GET["searchButton"])) {
                    $searchQuery = $_GET['searchQuery'];
                    header("Refresh:0; url='search.php?s=$searchQuery'");
                }
                
            ?>
          
            
        Most Liked Bloggers
        <?php

            //carousel of maximum 3 top Bloggers in terms of total likes
            $bloggerQuery = "SELECT * FROM user ORDER BY profileLikes DESC LIMIT 6"; //found out how to gather a finite amount of most liked blogs here: https://stackoverflow.com/questions/4874731/how-can-i-select-the-top-10-largest-numbers-from-a-database-column-using-sql
            $bloggerResult = mysqli_query($mysqli, $bloggerQuery);              //learnt limits here: https://www.w3schools.com/mysql/mysql_limit.asp
            $bloggerObject = mysqli_fetch_assoc($bloggerResult);

            while ($bloggerObject = mysqli_fetch_assoc($bloggerResult)) {    //add profile pictures aswell when that works
                echo("<div class='postContent'>");
                echo($bloggerObject['username']);
                echo($bloggerObject['profileLikes']);
                echo($bloggerObject['userID']);
                $userID = $bloggerObject['userID'];
                echo("<a href='user.php?user=$userID'>Profile</a>");
                echo("</div>");
            }
        ?>

        <?php
        include("Includes/footer.php");
        ?>
    </main>
</body>
</html>