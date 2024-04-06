<?php
    require_once('includes/config.php');
    session_start();
    $SessionUser = $_SESSION['userID'];
    $userProfile = $_GET['user'];
    //checks if user is your profile or not
    if ($userProfile) {
        //if you are the user that is clicked on
        if ($userProfile == $SessionUser) {
            header("Refresh:0; url=Profile.php");
        }
        $userPostQuery = "SELECT * FROM blogpost WHERE userID = '$userProfile'";
        $result = mysqli_query($mysqli, $userPostQuery);
        $row = mysqli_fetch_assoc($result);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User | </title>
</head>
<body>
    <?php
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
                }
                echo("</div>");
                $postID = $row['blogPostID'];
                $blogPostText = $row['blogPostText'];
                
                //toggle comments
                if ($row['commentsEnabled'] == "on") {
                    echo("<a href='comments.php?post=$postID'>Comments</a>");
                }
                else if ($row['commentsEnabled'] == "") {
                    echo("<div class='smallCommentText'>");
                    echo("Comments Disabled");
                    echo("</div>");
                }
                echo("<div class='smallCommentText'>");
                    echo($row['DateAndTime']);
                echo("</div>");
                
                if(isset($_POST['CommentButton'])) {
                    echo("hello");
                }

                //like/dislike post
                if (isset($_POST[$postID])) {
                    //add something where it ignores the error if it doesnt add to the database as it is just saying that the like is already there
                    $addLike = $mysqli->prepare("INSERT INTO userlikedposts (userID, blogPostID) VALUES($SessionUser, $postID)");
                    $addLike->execute();
                    header("Refresh:0");
                }
            echo("</form>");
            }
            ?>
        </div>
</body>
</html>