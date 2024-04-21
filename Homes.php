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
            $SessionUser = $_SESSION['userID'];
        }
        else {
            require_once('includes/loginReg.php');
        }
        ?>
        </div>
        
        <search>
            <form class="search" method="GET">
                <input  class = "search" name="searchQuery" type="text" placeholder="Search For User..">
                <button type="submit" name="searchButton" style="padding: 10px 5px;">Search</button>
            </form>
        </search>
            <?php
                //get search url
                if (isset($_GET["searchButton"])) {
                    $searchQuery = $_GET['searchQuery'];
                    header("Refresh:0; url='search.php?s=$searchQuery'");
                }
            
        echo("Most Liked Bloggers");
        $bloggerQuery = mysqli_query($mysqli, "SELECT * FROM user ORDER BY profileLikes DESC LIMIT 3");     //learnt limits here: https://www.w3schools.com/mysql/mysql_limit.asp
        echo("<div class='MostLikedBloggers'>");
        While ($blogger = mysqli_fetch_assoc($bloggerQuery)) {
            echo("<div class='BloggerProfile'>");
            echo("<div class='userPhoto'>");
                echo("<a href='user.php?user={$blogger['userID']}'><img class='userPhoto' src = '{$blogger['profilePicture']}' alt = 'Profile Picture'></a>");
                echo("</div>");
                echo("<span class='username' style='font-size: large; display: flex; justify-content: center;'>{$blogger['username']}</span>");
            echo("</div>");
        }
        echo("</div>");

        echo("Most Liked posts");
        $mostLikedPostsQuery = mysqli_query($mysqli, "SELECT * FROM blogpost ORDER BY likesOnPost DESC LIMIT 5");

        echo("<div class='AllPostsContainer'>");
        while ($mostLikedPosts = mysqli_fetch_assoc($mostLikedPostsQuery)) {
            $postID = $mostLikedPosts['blogPostID'];
            
            $userProfileQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$mostLikedPosts['userID']'");
            while ($userProfile = mysqli_)
            
            
            echo("<div class='post'>");
                    echo($mostLikedPosts['blogPostText']);
            echo("</div>");




            }
        }
        ?>
        <?php
        include("Includes/footer.php");
        ?>
    </main>
</body>
</html>