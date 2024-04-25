<?php
    session_start();
    require_once('../includes/config.php');
    //checks if user is admin
    if ($_SESSION['jobTitle'] != 'Admin') {
        echo "<script> window.location.href='Home.php'</script>";
    }
    $SessionUser = $_SESSION['userID'];
    $userPostQuery = mysqli_query($mysqli, "SELECT * FROM blogpost ORDER BY DateAndTime DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="..\css\mobile.css">
    <link rel="stylesheet" type="text/css" href="..\css\desktop.css" media="only screen and (min-width: 800px)">
    <title>Admin | Latest</title>
</head>
<body>
    <main class='container'>

        <!-- header -->
        <header style= "display: flex; flex-direction: row; justify-content: space-between;">
    <div id="logo"><a href="../Home.php"><h2>Blog Platform</h2></a></div> 
    <div class="profileShortcut">
    <nav>
            <ul>
                <li><a href="Home.php">Home</a></li>
                <li><a href="latest.php">Lastest Posts</a></li>
                <li><a href="Flagged.php">Flagged</a></li>
            </ul>
        </nav>
    <div style="align-content: center; padding-right: 20px; height: 20px; border-radius: 8px; padding: 10px; margin: 5px; background: green;"><a class = "profileShortcut" href="logout.php">Log Out</a></div>
        <div style="">
        <div ><a href="../Home.php"><img class= 'profilePhoto' src=<?php echo("../{$_SESSION['profilePicture']}"); ?> alt = "Profile Picture"></a></div>
        <span style="display: flex; justify-content: center;"><?php echo($_SESSION['username']); ?></span>
        </div>
    </div>
</header>

    <div class='AllPostsContainer'>
    <?php

//profile posts
    $rowNum = mysqli_num_rows($userPostQuery);
    if ($rowNum > 0) {
        while ($row = mysqli_fetch_assoc($userPostQuery)) {
                    //post content
        echo("<div class='post'>");   
        //profile
        $getUserQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '{$row['userID']}'");
        While ($getUser = mysqli_fetch_assoc($getUserQuery)) {
            echo("<div class='BloggerProfile' style='padding-right: 10px;'>");
                    echo("<div class='userPhoto'>");
                    echo("<img class='userPhoto' src= '../{$getUser['profilePicture']}'>");
                    echo("</div>");

                    echo("<div class='username' style='font-size: large; display: flex; justify-content: center;'>");
                    echo("<span style='font-size: large; display: flex; justify-content: center;'> {$getUser['username']}</span>");
                    echo("</div>");


                            //gather Num Of Comments
                    $numOfPostsQuery = mysqli_query($mysqli, "SELECT COUNT(blogPostID) AS NumOfComments FROM commentblogpost WHERE blogPostID = '{$row['blogPostID']}'");
                    
                    While ($numOfComments = mysqli_fetch_assoc($numOfPostsQuery)) {
                        
                        $commentNum = $numOfComments['NumOfComments'];
                        
                        $numCommentsStore = mysqli_query($mysqli, "UPDATE blogpost SET NumOfComments = '$commentNum' WHERE blogPostID = '{$row['blogPostID']}'"); //UPDATE total number of comments in blogpost record
                        
                        //toggle comments
                        if ($row['commentsEnabled'] == "on") {
                            $commentLink = $row['blogPostID'];
                            echo("<span><a href='../comment.php?post=$commentLink'>Comments($commentNum)</a></span>");
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
        echo("<div class='postContent'>");
        echo("<textarea readonly style='background: green; padding: 10px; border-radius: 8px; border: none;'>{$row['blogPostText']}</textarea>");
        echo("<img class='tempPostImage' src='{$row['blogPostImage']}'>");
        echo("<a href='{$row['blogPostLink']}'>{$row['blogPostLink']}</a>");
        //video
        if (!empty($row['blogPostVideo'])) {
            $parse = parse_url($row['blogPostVideo']); 
            $query = $parse['query'];
            $final=substr($query,2);
            echo("<iframe width='420' height='315' src='https://www.youtube.com/embed/{$final}'></iframe>"); 
        }
        echo("</div>");

        if ($_SESSION['on'] = "yes") {
            if ($_SESSION['jobTitle'] == 'Admin' || $_SESSION['jobTitle'] == 'Moderator') {
                $postID = $row['blogPostID'];


                //if admin or moderator, can delete post
                echo("<form class='deleteButton' method='POST' action='latest.php'>");
                echo("<button type = 'submit' name = 'deleted$postID'>delete</button>");
                echo("</form>");
                }

                //delete post
                if (isset($_POST["deleted$postID"])) {
                $deleteCommentQuery = mysqli_query($mysqli, "DELETE FROM blogpost WHERE blogPostID = '$postID'");
                echo "<script> location.reload();</script>";
                }

                    //add context button
                    echo("<form class='contextButton' method='POST' action='latest.php' style='padding: 3px;'>");
                    echo("<button type = 'submit' name = 'context$postID'> Add Context</button>");
                    echo("</form>");

                    //add context menu
                    if (isset($_POST["context$postID"])) {
                        echo("<form method='POST' action='latest.php'>");
                        echo("<textarea name='newContext'>{$row['BlogPostContext']}</textarea>");
                        echo("<button type='submit' name='contextSubmit$postID'>Submit Changes</button>");
                        echo("</form>");
                    }

                    //submit context
                    if (isset($_POST["contextSubmit$postID"]) && $_POST['newContext'] != "") {
                        $updateContext = mysqli_query($mysqli, "UPDATE blogpost SET BlogPostContext = '{$_POST['newContext']}' WHERE blogPostID = '$postID'");
                        echo "<script> window.location.href='latest.php'</script>";
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
                $removeLike = $mysqli->prepare("DELETE FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '{$row['blogPostID']}'");
                $removeLike->execute();
                
            }
            else {
                $addLike = $mysqli->prepare("INSERT INTO userlikedposts (userID, blogPostID) VALUES($SessionUser, '{$row['blogPostID']}')");
                $addLike->execute();

            }
            echo "<script> window.location.href='latest.php'</script>";
        }
        echo("</div>");
        }
    }
    else {
        echo("<div class='username'>no posts</div>");
    echo("</div>");
    }
    echo("</div>");
    echo("</div>");
    ?>
    </div>
    <?php
        include("../Includes/footer.php");
        ?>
    </main>
</body>
</html>