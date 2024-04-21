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
        While ($post = mysqli_fetch_assoc($mostLikedPostsQuery)) {
            echo("<div class = 'post'>");
            echo("<div class='BloggerProfile' style='padding-right: 10px;'>");
            echo("<div class = 'userPhoto'>");

                //get the poster profile
                $getProfilePictureQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '{$post['userID']}'");
                
                $postID = $post['blogPostID'];
                While ($PosterProfile = mysqli_fetch_assoc($getProfilePictureQuery)) {
                    echo("<a href='user.php?user={$post['userID']}'><img class='userPhoto' src = '{$PosterProfile['profilePicture']}' alt = 'Profile Picture'></a>");
                    echo("</div>");
                    echo("<span class='username' style='font-size: large; display: flex; justify-content: center;'> {$PosterProfile['username']}</span>");
                    $postUserID = $PosterProfile['userID'];
                    
                    //find number of comments
                    $numOfCommentsQuery = mysqli_query($mysqli, "SELECT COUNT(blogPostID) AS NumOfComments FROM commentblogpost WHERE blogPostID = '$postID'");
                    
                    While ($numOfComments = mysqli_fetch_assoc($numOfCommentsQuery)) {
                        $commentNum = $numOfComments['NumOfComments'];
                        if ($_SESSION['userID']) {
                            //toggle comments
                        if ($post['commentsEnabled'] == "on") {
                            echo("<span><a href='comment.php?post=$postID'>Comments($commentNum)</a></span>");
                        }
                        else if ($post['commentsEnabled'] == "") {
                            echo("<div class='smallCommentText'>");
                            echo("Comments Disabled");
                            echo("</div>");
                        }
                        echo("<div class='smallCommentText'>");
                            echo($post['DateAndTime']);
                        echo("</div>");
                    }
                }
                echo("</div>");
                
                echo("<div style='background: green; padding: 10px; border-radius: 8px;'>");
                echo($post["blogPostText"]);
                echo($post['blogPostImage']);
                echo($post['blogPostLink']);
                echo($post['blogPostVideo']);            
                echo("</div>");

                //create like button
                echo("<form class='likeButton' method='POST' action='Home.php'>");
                echo("<button name = '$postID'>like</button>");
                echo("</form>");

                //gather total likes and adds it to post to display and store in the database
                $likesQuery = mysqli_query($mysqli, "SELECT count(blogpostID) As 'likeCount' FROM userlikedposts WHERE blogpostID = '$postID'");
                $LikeNum = mysqli_fetch_assoc($likesQuery);
                echo($LikeNum['likeCount']);

                //updates likes in post table
                $storeLikesQuery = $mysqli->prepare("UPDATE blogpost SET likesOnPost = '{$LikeNum['likeCount']}' WHERE blogpostID = '$postID'");
                $storeLikesQuery->execute();

                //updates total likes in user table
                $totalLikesQuery = $mysqli->prepare("UPDATE user SET profileLikes = (SELECT SUM(likesOnPost) As 'totalLikes' FROM blogpost WHERE userID = '$postUserID') WHERE userID = '$postUserID'");
                $totalLikesQuery->execute();

                //like/dislike post
                if (isset($_POST[$postID])) {
                    $checkIfLikedQuery = mysqli_query($mysqli, "SELECT * FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '$postID'"); //check if user has liked post or not
                    $CheckLikedNumRows = mysqli_num_rows($checkIfLikedQuery); //found a method of liking and unliking a post without inserting duplicate keys into the database here: https://stackoverflow.com/questions/2848904/check-if-record-exists By User Dominic Rodger

                    if ($CheckLikedNumRows > 0) {
                        $removeLike = $mysqli->prepare("DELETE FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '$postID'");
                        $removeLike->execute();
                        
                    }
                    else {
                        $addLike = $mysqli->prepare("INSERT INTO userlikedposts (userID, blogPostID) VALUES($SessionUser, $postID)");
                        $addLike->execute();

                    }
                    header("refresh:0; url='Home.php'");
                }
                }
            echo("</div>");
        }
        echo("</div>");
        ?>
        <?php
        include("Includes/footer.php");
        ?>
    </main>
</body>
</html>