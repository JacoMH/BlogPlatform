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
            $bloggerQuery = "SELECT * FROM user ORDER BY profileLikes DESC LIMIT 4"; //found out how to gather a finite amount of most liked blogs here: https://stackoverflow.com/questions/4874731/how-can-i-select-the-top-10-largest-numbers-from-a-database-column-using-sql
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
        Most Liked posts
        <?php
        $mostLikedPostsQuery = mysqli_query($mysqli, "SELECT * FROM blogpost ORDER BY likesOnPost DESC LIMIT 5");
        
        While ($post = mysqli_fetch_assoc($mostLikedPostsQuery)) {
                echo("<div class = 'userPhoto'>");

                //get the poster profile
                $getProfilePictureQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '{$post['userID']}'");
                
                While ($PosterProfile = mysqli_fetch_assoc($getProfilePictureQuery)) {
                    echo("<img src='{$PosterProfile['profilePicture']}' alt= 'Profile Picture'>");
                    echo("</div>");
                    echo("<div class='username' style='font-size: large;'> {$PosterProfile['username']}</div>");
                }


                echo("<div class = 'postContent'>");
                echo($post["blogPostText"]);
                echo($post['blogPostImage']);
                echo($post['blogPostLink']);
                echo($post['blogPostVideo']);
                $postID = $post['blogPostID'];
                echo("</div>");

                if ($_SESSION['userID']) {
                                    //toggle comments
                if ($post['commentsEnabled'] == "on") {
                    echo("<a href='comments.php?post=$postID'>Comments</a>");
                }
                else if ($post['commentsEnabled'] == "") {
                    echo("<div class='smallCommentText'>");
                    echo("Comments Disabled");
                    echo("</div>");
                }
                echo("<div class='smallCommentText'>");
                    echo($post['DateAndTime']);
                echo("</div>");

                //like/dislike post
                if (isset($_POST[$postID])) {
                    $checkIfLikedQuery = mysqli_query($mysqli, "SELECT * FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '$postID'");
                    $CheckLikedNumRows = mysqli_num_rows($checkIfLikedQuery); //found a method of liking and unliking a post without inserting duplicate keys into the database here: https://stackoverflow.com/questions/2848904/check-if-record-exists By User Dominic Rodger

                    if ($CheckLikedNumRows > 0) {
                        $removeLike = $mysqli->prepare("DELETE FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '$postID'");
                        $removeLike->execute();
                        
                    }
                    else {
                        $addLike = $mysqli->prepare("INSERT INTO userlikedposts (userID, blogPostID) VALUES($SessionUser, $postID)");
                        $addLike->execute();

                    }
                    header("refresh:0; url='Profile.php'");
                }
                }
        }

        ?>
        <?php
        include("Includes/footer.php");
        ?>
    </main>
</body>
</html>