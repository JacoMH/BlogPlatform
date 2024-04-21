<?php
    require_once('includes/config.php');
    session_start();

    $userProfile = $_GET['user'];

    //fetch the rest of profile
    $UserQuery = mysqli_query($mysqli,"SELECT * FROM user WHERE UserID = '$userProfile'");


    if (isset($_SESSION['userID'])) {
        $SessionUser = $_SESSION['userID'];
       
        //checks if user is your profile or not
        if ($userProfile) {
            //if you are the user that is clicked on
            if ($userProfile == $SessionUser) {
                header("Refresh:0; url=Profile.php");
            }
        }
    }
    $userPostQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$userProfile'");

    //fetch total likes
    $profileLikesQuery = "SELECT profileLikes FROM user WHERE userID = '$userProfile'";
    $profileLikesLink = mysqli_query($mysqli, $profileLikesQuery);
    $profileLikesDisplay = mysqli_fetch_assoc($profileLikesLink);
    echo($profileLikesDisplay['profileLikes']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User | </title>
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
</head>
<body>
    <main class="container">
    <?php
    if (isset($_SESSION['userID'])) {
        include("includes/profileShortcut.php");
    }
    else{
        include("includes/loginReg.php");
    }
    //top of profile
    while ($user = mysqli_fetch_assoc($UserQuery)) {

        //banner
        echo("<div class='banner'>");
        echo("<img class='userBanner' src='{$user['bannerPicture']}'>");
        echo("</div>");

        echo("<div class='welcome'>");
        $profileLikes = $profileLikesDisplay['profileLikes'];
        echo($profileLikes); echo(" "); echo("Profile Likes");
        echo("</div>");
        //profile picture
        echo("<div class='userPhoto'>");
        echo("<img src= '{$user['profilePicture']}'>");
        echo("</div>");

        echo("<div class='realName'>");
        echo($user['firstName']); echo(' '); echo($user['lastName']); 
        echo("</div>");
        echo("<span class='username'>");
        echo($user['username']);
        echo("</span>");

    
    echo("<div class='AllPostsContainer'>");
    //profile posts
        while ($row = mysqli_fetch_assoc($userPostQuery)) {

            //post content
            echo("<div class='post'>");
            
                //profile
                $getUserQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$userProfile'");
                While ($getUser = mysqli_fetch_assoc($getUserQuery)) {
                    echo("<div class='BloggerProfile' style='padding-right: 10px;'>");
                            echo("<div class='userPhoto'>");
                            echo("<img src= '{$getUser['profilePicture']}'>");
                            echo("</div>");

                            echo("<div class='username' style='font-size: large; display: flex; justify-content: center;'>");
                            echo("<span style='font-size: large; display: flex; justify-content: center;'> {$getUser['username']}</span>");
                            echo("</div>");


                                    //gather number of comments
                            $numOfCommentsQuery = mysqli_query($mysqli, "SELECT COUNT(blogPostID) AS NumOfComments FROM commentblogpost WHERE blogPostID = '{$row['blogPostID']}'");

                            While ($numOfComments = mysqli_fetch_assoc($numOfCommentsQuery)) {
                                
                                $commentNum = $numOfComments['NumOfComments'];
                                
                                
                                //toggle comments
                                if ($row['commentsEnabled'] == "on") {
                                    $commentLink = $row['blogPostID'];
                                    echo("<span><a href='comment.php?post=$commentLink'>Comments($commentNum)</a></span>");
                                }
                                else if ($row['commentsEnabled'] == "") {
                                    echo("<div class='smallCommentText'>");
                                    echo("Comments Disabled");
                                    echo("</div>");
                                }
                            }
                            echo("<div class='smallCommentText'>");
                            echo($row['DateAndTime']);
                            echo("</div>");
                    echo("</div>");
    
                }
                //postContent
                echo("<span style='background: green; padding: 10px; border-radius: 8px;'>{$row['blogPostText']}</span>");

                
            //all stuff likes below

                //gather total likes and adds it to post to display and store in the database
                $likesQuery = mysqli_query($mysqli, "SELECT count(blogpostID) As 'likeCount' FROM userlikedposts WHERE blogpostID = '{$row['blogPostID']}'");
                $LikeNum = mysqli_fetch_assoc($likesQuery);

                //updates likes in post table
                $storeLikesQuery = $mysqli->prepare("UPDATE blogpost SET likesOnPost = '{$LikeNum['likeCount']}' WHERE blogpostID = '{$row['blogPostID']}'");
                $storeLikesQuery->execute();

                //create like/dislike post
                $PostLikes = $row['likesOnPost'];
                echo("<form class='likeButton' method='POST'>");
                echo("<button name = '{$row['blogPostID']}'>like</button>");
                echo("<label for='{$row['blogPostID']}'>{$LikeNum['likeCount']}</label>");
                echo("</form>");


                //like/dislike post
                if (isset($_POST[$row['blogPostID']])) {
                    $checkIfLikedQuery = mysqli_query($mysqli, "SELECT * FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '{$row['blogPostID']}'");
                    $CheckLikedNumRows = mysqli_num_rows($checkIfLikedQuery); //found a method of liking and unliking a post without inserting duplicate keys into the database here: https://stackoverflow.com/questions/2848904/check-if-record-exists By User Dominic Rodger

                    if ($CheckLikedNumRows > 0) {
                        $removeLike = $mysqli->prepare("DELETE FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '{$row['blogPostID']}'");
                        $removeLike->execute();
                        
                    }
                    else {
                        $addLike = $mysqli->prepare("INSERT INTO userlikedposts (userID, blogPostID) VALUES($SessionUser, '{$row['blogPostID']}')");
                        $addLike->execute();

                    }
                    header("refresh:0; url='user.php?user=$userProfile'");
                }
            echo("</div>");

        }
    echo("</div>");
    }
    ?>
        </div>
    </main>
</body>
</html>