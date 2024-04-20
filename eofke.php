
<?php
    session_start();
    if(empty($_SESSION['username'])) {
        header("refresh:0; url='login.php'");
    }
    require_once('includes/config.php');
    $SessionUser = $_SESSION['userID'];
    $postContent = "";
    //display posts made by user
    $userPostQuery = "SELECT * FROM blogpost WHERE userID = '$SessionUser'";
    $result = mysqli_query($mysqli, $userPostQuery);

    //fetch total likes
    $profileLikesQuery = "SELECT profileLikes FROM user WHERE userID = '$SessionUser'";
    $profileLikesLink = mysqli_query($mysqli, $profileLikesQuery);
    $profileLikesDisplay = mysqli_fetch_assoc($profileLikesLink);
    echo($profileLikesDisplay['profileLikes']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
    <title>Profile | <?php echo($_SESSION["username"])?></title>
</head>
<body>
    <main class="container">
        <?php
            include("Includes/HomeShortcut.php");
        ?>
        <div class="banner">
            
        </div>
        <?php
        //welcome
        echo("<div>");
        echo("Welcome back, "); echo($_SESSION["firstName"]);
        echo("</div>");
        //total likes on page
        echo("<div>");
        $profileLikes = $profileLikesDisplay['profileLikes'];
        echo($profileLikes); echo(" "); echo("Profile Likes");
        echo("</div>");
        ?>
        </div>
        <form method="POST">
        <button type="Submit" name="profilePic">Open File Dialog</button> <!-- import image from image address on web -->
        </form>

        <div class="profilePic">
            
        </div>

        
         <?php   if (isset($_POST['profilePic'])) { ?>
                <div class='imgChange' method='POST' action='Profile.php'>
                    <input type='text' name='imageLink' placeholder='image address here...'>
                    <button type='submit' name='imgButton' >add</button>
                </div>
                <?php
                //if submit
                if (isset($_POST['imgButton']) && !empty($_POST['imgButton'])) {
                    echo("hello");
                    print_r($_POST);
                    echo("efhiaoejfioajefoiajefoiaje");          
                }
            } 
            //if submit                     //figure out how to store the image address in database, refresh, load it and when dropdown menu comes up it displays it.
?>

        
        <div class="realName">
            <?php
            echo($_SESSION["firstName"]); echo(" "); echo($_SESSION["lastName"]); 
            ?>
        </div> 
        <span class="username">
            <?php
            echo($_SESSION["username"]);
            ?>
        </span>
        <form class="createPostContainer" action="Profile.php" method="POST">
            <div style="display: flex; flex-direction: row;">
                <textarea id="w3review" name="textContent" rows="4" cols="50"></textarea>
                <button class = "createButton" type="submit" name="makePostButton">Create Post</button>
            </div>
            <div style="display: flex; flex-direction: row;">
            <button class = "createButton" type="button" name="imageContent">Add Image</button>
            <button class = "createButton" type="button" name="linkContent">Add Link</button>
            <button class = "createButton" type="button" name="videoContent">Add Video</button>
            <input type="checkbox" name="commentToggle" checked>Enable Comments</input>
            </div>
        </form>
        <?php
            if (isset($_POST['makePostButton']) && $_POST['textContent'] != "") {
                $postContent = $_POST['textContent'] ?? null;
                $commentToggle = $_POST['commentToggle'] ?? null;
                $addToPosts = $mysqli->prepare("INSERT INTO blogpost (userID, blogPostText, commentsEnabled, DateAndTime) VALUES ('$SessionUser', '$postContent', '$commentToggle', now())");
                $addToPosts->execute();
                header("location = Profile.php");
            }
        ?>
        <div class="AllPostsContainer">
            <?php
            //filter will fetch everything again but in different orders, refresh page to do it
            //add like button, works by having a seperate table to identify all the posts that have been liked by the same user.
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
    
                    if (empty($row)) {
                        echo("not filled");
                    }
                    else{
                        echo("filled");
                    }
    
    
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
            
            ?>
        </div>
    
        <?php
                //query which selects all profile stuff, lists posts etc. made. If query returns nothing then 2 buttons are displayed telling the user to log in or reg
                include("Includes/footer.php");
        ?>

    </main>
    <script src="js/main.js"></script>
</body>
</html>