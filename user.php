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
    $userPostQuery = "SELECT * FROM blogpost WHERE userID = '$userProfile'";
    $result = mysqli_query($mysqli, $userPostQuery);
    $row = mysqli_fetch_assoc($result);

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
        echo("<div class='banner'>");
        echo("</div>");


        $profileLikes = $profileLikesDisplay['profileLikes'];
        echo($profileLikes); echo(" "); echo("Profile Likes");
        echo("</div>");

        echo("<div class='realName'>");
        echo($user['firstName']); echo(' '); echo($user['lastName']); 
        echo("</div>");
        echo("<span class='username'>");
        echo($user['username']);
        echo("</span>");
    }

    //copy pasted a section of my code from the profile page
    while ($row = mysqli_fetch_assoc($result)) {
                $profileID = $row['userID'];
                echo("<div class='post'>");
                //fetch their profile
                $fetchUserProfile = "SELECT profilePicture, username FROM user where userID = '$profileID'";

                //profile pic
                echo("<div class = 'userProfilePic'>");
                $profileResult = mysqli_query($mysqli, $fetchUserProfile);
                $profile = mysqli_fetch_assoc($profileResult);
                echo($profile["profilePicture"]);
                echo("</div>");


                echo("<div class='AllPostsContainer'>");
                if (empty($result)) {
                    echo("No Posts Yet");
                }
                else {
                    echo("<form method='POST' action='Profile.php'>");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $profileID = $row['userID'];
                        echo("<div class='post'>");
                        //fetch their profile
                        $fetchUserProfile = "SELECT profilePicture, username FROM user where userID = '$profileID'";
        
                        //profile pic
                        echo("<div class = 'userProfilePic'>");
                        $profileResult = mysqli_query($mysqli, $fetchUserProfile);
                        $profile = mysqli_fetch_assoc($profileResult);
                        echo($profile["profilePicture"]);
                        echo("</div>");
        
        

        
                        //fetch the post
                        if($row['blogPostText'] != "") { //add these param afterwards && $row['blogPostImage'] != "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] != ""
                            echo("<div class = 'postContent'>");
                            echo($row["blogPostText"]);
                            echo($row['blogPostImage']);
                            echo($row['blogPostLink']);
                            echo($row['blogPostVideo']);
                            echo("</div>");
                            $postID = $row['blogPostID'];
                            $blogPostText = $row['blogPostText'];
                            
                            //create like/dislike post
                            $PostLikes = $row['likesOnPost'];
                            echo("<form class='likeButton' method='POST' action='Profile.php'>");
                            echo("<button name = '$postID'>like</button>");
        
                            //gather total likes and adds it to post to display and store in the database
                            $likesQuery = "SELECT count(blogpostID) As 'likeCount' FROM userlikedposts WHERE blogpostID = '$postID'";
                            $likes = mysqli_query($mysqli, $likesQuery);
                            $LikeNum = mysqli_fetch_assoc($likes);
                            echo($LikeNum['likeCount']);
                            
                            //updates likes in post table
                            $likestore = $LikeNum['likeCount'];
                            $storeLikesQuery = $mysqli->prepare("UPDATE blogpost SET likesOnPost = '$likestore' WHERE blogpostID = '$postID'");
                            $storeLikesQuery->execute();
        
                            //updates total likes in user table
                            $totalLikesQuery = $mysqli->prepare("UPDATE user SET profileLikes = (SELECT SUM(likesOnPost) As 'totalLikes' FROM blogpost WHERE userID = '$profileID') WHERE userID = '$profileID'");
                            $totalLikesQuery->execute();
        
                            //toggle comments
                            if ($row['commentsEnabled'] == "on") {
                                echo("<a href='comment.php?post=$postID'>Comments</a>");
                            }
                            else if ($row['commentsEnabled'] == "") {
                                echo("<div class='smallCommentText'>");
                                echo("Comments Disabled");
                                echo("</div>");
                            }
                            echo("<div class='smallCommentText'>");
                                echo($row['DateAndTime']);
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
                        else {
                            echo("<h2>No Posts Made</h2>");
                            echo("please work");
                        }
                        echo("</div>");
                        $postID = $row['blogPostID'];
                        $blogPostText = $row['blogPostText'];
                        
                    echo("</form>");
                    }
                }
            }
            ?>
        </div>
    </main>

</body>
</html>