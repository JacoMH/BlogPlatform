<?php
    require_once('includes/config.php');
    session_start();

 
    if (!(isset($_SESSION['userID']))) {  //tells different parts of the site to disable/enable features based on if we are logged in or not
        $SessionUser = "signedOut"; 
    }   
    

    $userProfile = $_GET['user'];

    //fetch user posts
    $userPostQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$userProfile'");

    //fetch the rest of profile
    $UserQuery = mysqli_query($mysqli,"SELECT * FROM user WHERE UserID = '$userProfile'");
    

    if (isset($_SESSION['userID'])) {
        $SessionUser = $_SESSION['userID'];
        //checks if user is your profile or not
        if ($userProfile) {
            //if you are the user that is clicked on
            if ($userProfile == $SessionUser) {
                $_SESSION['TempStoreImage'] = "";
                $_SESSION['TempStoreLink'] = "";
                $_SESSION['TempStoreVideo'] = "";
                $_SESSION['TempPostText'] = "";
                echo "<script> window.location.href='Profile.php'</script>";
            }

            $checkIfAdminQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$userProfile'");
            
            while ($checkIfAdmin = mysqli_fetch_assoc($checkIfAdminQuery)) {
                if ($checkIfAdmin['jobTitle'] == "Admin") {
                    echo "<script> window.location.href='Home.php'</script>";
                }
            }
        }
    }
    //fetch total likes
    $profileLikesQuery = "SELECT profileLikes FROM user WHERE userID = '$userProfile'";
    $profileLikesLink = mysqli_query($mysqli, $profileLikesQuery);
    $profileLikesDisplay = mysqli_fetch_assoc($profileLikesLink);

    //filter
    $currentFilter = "";

    if (isset($_POST['Filters'])) {
        if ($_POST['Filters'] == "Most Recent") {
            $userPostQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$userProfile' ORDER BY DateAndTime DESC"); 
            $currentFilter = "Most Recent";
        }
        else if($_POST['Filters'] == "Most Commented") {
            //put on number of comments next to comment link, include comment number with post
            $userPostQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$userProfile' ORDER BY NumOfComments DESC");
            $currentFilter = "Most Commented";
        }
        else if($_POST['Filters'] == "Oldest") {
            $userPostQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$userProfile' ORDER BY DateAndTime ASC");
            $currentFilter = "Oldest";
        }
    }
    else{
        $userPostQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$userProfile' ORDER BY DateAndTime DESC"); 
        $currentFilter = "Most Recent";
    }
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
    if (!empty($_SESSION['userID'])) {
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
               //flag account
               if ($_SESSION['jobTitle'] == 'Moderator') {
                echo("<form method='POST'>");
                echo("<button type='submit' name='FlagAccount'>Flag Account</button>");
                echo("</form>");
            }
    
            if (isset($_POST['FlagAccount'])) {
                $flagAccountQuery = mysqli_query($mysqli, "UPDATE user SET Flagged = 'yes' WHERE userID = '$userProfile'");
            }
        echo("</div>");
        //profile picture
        echo("<div class='userPhoto'>");
        echo("<img class='userPhoto' src= '{$user['profilePicture']}'>");
        echo("</div>");

        echo("<div class='realName'>");
        echo($user['firstName']); echo(' '); echo($user['lastName']); 
        echo("</div>");
        echo("<span class='username'>");
        echo($user['username']);
        echo("</span>");

 
    echo("<main class='AllPostsContainer'>");
    
    ?>
    <form style= "text-align: center;" method="POST" action="user.php?user=<?php echo($userProfile)?>">
    <label>Sort By:</label>
    <select name="Filters">
        <option selected="" disabled="" style='display: none;'><?php echo($currentFilter); ?></option>
        <option value="Most Recent">Most Recent</option>
        <option value="Most Commented">Most Commented</option>
        <option value="Oldest">Oldest</option>
    </select>
    <button type="submit" name="filterConfirm">Go</button>
    </form>
    
    <?php
    //profile posts
    while ($row = mysqli_fetch_assoc($userPostQuery)) {
        if ($row > 0) {
            //post content
            echo("<div class='post'>");
                
            //profile
            $getUserQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$userProfile'");
            While ($getUser = mysqli_fetch_assoc($getUserQuery)) {
                echo("<div class='BloggerProfile' style='padding-right: 10px;'>");
                        echo("<div class='userPhoto'>");
                        echo("<img class='userPhoto' src= '{$getUser['profilePicture']}'>");
                        echo("</div>");
    
                        echo("<div class='username' style='font-size: large; display: flex; justify-content: center;'>");
                        echo("<span style='font-size: large; display: flex; justify-content: center;'> {$getUser['username']}</span>");
                        echo("</div>");
            }
    
                                //gather number of comments
                        $numOfCommentsQuery = mysqli_query($mysqli, "SELECT COUNT(blogPostID) AS NumOfComments FROM commentblogpost WHERE blogPostID = '{$row['blogPostID']}'");
    
                        
                        While ($numOfComments = mysqli_fetch_assoc($numOfCommentsQuery)) {
                            
                            $commentNum = $numOfComments['NumOfComments'];
                            
                            $numCommentsStore = mysqli_query($mysqli, "UPDATE blogpost SET NumOfComments = '$commentNum' WHERE blogPostID = '{$row['blogPostID']}'"); //UPDATE total number of comments in blogpost record
                            
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
    
                $postID = $row['blogPostID'];
            //postContent
            echo("<div class='postContent'>");
            echo("<textarea readonly style='background: green; padding: 10px; border-radius: 8px; border: none;'>{$row['blogPostText']}</textarea>");
            if ($_SESSION['jobTitle'] != "signedOut") {
                echo("<img class='tempPostImage' src= '{$row['blogPostImage']}'>");
            }
            else if(!empty($row['blogPostImage'])){
                echo("<span style='background: #adb013; border: 3px solid black;'>Create Account Or Sign In to View Photos</span>");
            }
            
            if ($_SESSION['jobTitle'] != "signedOut") {
                echo("<a href='{$row['blogPostLink']}'>{$row['blogPostLink']}</a>");
            }
            else if(!empty($row['blogPostLink'])){
                echo("<span style='background: #adb013; border: 3px solid black;'>Create Account Or Sign In to View Links</span>");
            }
            //video
            if (!empty($row['blogPostVideo']) && $_SESSION['jobTitle'] != "signedOut") {
                $parse = parse_url($row['blogPostVideo']); 
                $query = $parse['query'];
                $final=substr($query,2);
                echo("<iframe width='420' height='315' src='https://www.youtube.com/embed/{$final}'></iframe>");
                }
                else if(!empty($row['blogPostVideo'])){
                    echo("<span style='background: #adb013; border: 3px solid black;'>Create Account Or Sign In to View Videos</span>");
                }

                if (!empty($row['BlogPostContext'])) {
                    echo("<div style = 'display: flex; flex-direction: column; background: grey; border: 1px solid black; margin-top: 3px;'>");
                    echo("<label>Admin/Moderator gave context for this post:</label>");
                    echo("<textarea style='background: grey; border: none;'>{$row['BlogPostContext']}</textarea>");
                    echo("</div>");
                }
            echo("</div>");
    
            if ($_SESSION['on'] = "yes") {
                if ($_SESSION['jobTitle'] == 'Admin' || $_SESSION['jobTitle'] == 'Moderator') {
    
    
                    //if admin or moderator, can delete post
                    echo("<form class='deleteButton' method='POST' action='user.php?user=$userProfile'>");
                    echo("<button type = 'submit' name = 'deleted$postID'>delete</button>");
                    echo("</form>");

                    //delete post
                    if (isset($_POST["deleted$postID"])) {
                        $deleteCommentQuery = mysqli_query($mysqli, "DELETE FROM blogpost WHERE blogPostID = '$postID'");
                        echo "<script> window.location.href='comment.php?post=$currentPost''</script>";
                        }

                    
                        //add context button
                        echo("<form class='contextButton' method='POST' action='user.php?user=$userProfile' style='padding: 3px;'>");
                        echo("<button type = 'submit' name = 'context$postID'> Add Context</button>");
                        echo("</form>");
    
                        //add context menu
                        if (isset($_POST["context$postID"])) {
                            echo("<form method='POST' action='user.php?post=$userProfile'>");
                            echo("<textarea name='newContext'>{$row['BlogPostContext']}</textarea>");
                            echo("<button type='submit' name='contextSubmit$postID'>Submit Changes</button>");
                            echo("</form>");
                        }
    
                        //submit context
                        if (isset($_POST["contextSubmit$postID"]) && $_POST['newContext'] != "") {
                            $updateContext = mysqli_query($mysqli, "UPDATE blogpost SET BlogPostContext = '{$_POST['newContext']}' WHERE blogPostID = '$postID'");
                            echo "<script> window.location.href='user.php?user=$userProfile'</script>";
                        }
                    }
                    

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
            if (isset($_POST[$row['blogPostID']]) && $SessionUser != "signedOut") {
                $checkIfLikedQuery = mysqli_query($mysqli, "SELECT * FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '{$row['blogPostID']}'");
                $CheckLikedNumRows = mysqli_num_rows($checkIfLikedQuery); //found a method of liking and unliking a post without inserting duplicate keys into the database here: https://stackoverflow.com/questions/2848904/check-if-record-exists By User Dominic Rodger
    
                if ($CheckLikedNumRows > 0) {
                    if (!(empty($_SESSION['userID']))) {
                    $removeLike = $mysqli->prepare("DELETE FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '{$row['blogPostID']}'");
                    $removeLike->execute();
                    }
                }
                else {
                    if (!(empty($_SESSION['userID']))) {
                        $addLike = $mysqli->prepare("INSERT INTO userlikedposts (userID, blogPostID) VALUES($SessionUser, '{$row['blogPostID']}')");
                        $addLike->execute();
                    }
    
                }
                echo "<script> window.location.href='user.php?user=$userProfile'</script>";
            }









            }
    
    
    
    
    
    

        }
        else {
            echo("<div class='username'>no posts</div>");
        echo("</div>");
        }
        echo("</div>");
        echo("</div>");
        }
    }
    echo("</main>");
    ?>
    <?php
    include("Includes/footer.php");
    ?>
        </div>
    </main>
</body>
</html>