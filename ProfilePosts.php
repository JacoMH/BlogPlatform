<?php
    $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser'");

     //updates total likes in user table
     $totalLikesQuery = $mysqli->prepare("UPDATE user SET profileLikes = (SELECT SUM(likesOnPost) As 'totalLikes' FROM blogpost WHERE userID = '$SessionUser') WHERE userID = '$SessionUser'");
     $totalLikesQuery->execute();

        //filter
        $currentFilter = "";
        if (isset($_POST['Filters'])) {
            if ($_POST['Filters'] == "Most Recent") {
                $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser' ORDER BY DateAndTime DESC"); 
                $currentFilter = "Most Recent";
            }
            else if($_POST['Filters'] == "Most Commented") {
                //put on number of comments next to comment link, include comment number with post
                $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser' ORDER BY NumOfComments DESC");
                $currentFilter = "Most Commented";
            }
            else if($_POST['Filters'] == "Oldest") {
                $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser' ORDER BY DateAndTime ASC");
                $currentFilter = "Oldest";
            }
        }
        else{
            $postQuery = mysqli_query($mysqli, "SELECT * FROM blogpost WHERE userID = '$SessionUser' ORDER BY DateAndTime DESC"); 
            $currentFilter = "Most Recent";
        }

?>

<!DOCTYPE html>
<html lang="en">
<body>
    <div class="AllPostsContainer">
    
    <form method="POST" action="Profile.php" style='padding-bottom: 10px; display: flex; justify-content: center;'>
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

        While ($post = mysqli_fetch_assoc($postQuery)) {
        echo("<main style='display: flex; flex-direction: row;'>");    
            echo("<div style= 'padding-right: 10px;'>");
            echo("<div class = 'userPhoto'>");
            echo("<img src='{$_SESSION['profilePicture']}' alt= 'Profile Picture'>");
            echo("</div>");
            echo("<span class='username' style='font-size: large; display: flex; justify-content: center;'> {$_SESSION['username']}</span>");
            echo("</div>");
            
            if($post['blogPostText'] != "") { //add these param afterwards && $row['blogPostImage'] != "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] != ""
                echo("<div class = 'postContent'>");
                $textContent = $post['blogPostText'];
                echo("<textarea readonly style='background: green; border: none;'>{$textContent}</textarea>");
                echo("<img class='tempPostImage' src='{$post['blogPostImage']}'>");
                echo($post['blogPostLink']);
                echo($post['blogPostVideo']);
                echo("</div>");
                $postID = $post['blogPostID'];
                $blogPostText = $post['blogPostText'];
        echo("</main>");        
                //
        echo("<span style='display: flex; flex-direction: row; padding: 3px; justify-content: center;'>");
                //create like/dislike post
                $PostLikes = $post['likesOnPost'];
                echo("<form class='likeButton' style='padding: 3px;' method='POST' action='Profile.php'>");
                echo("<button type = 'submit' name = '$postID'>like</button>");
               // echo("<label>{$LikeNum['likeCount']}</label>");
                echo("</form>");
                

                //edit button
                echo("<form class='editButton' style='padding: 3px;' method='POST' action='Profile.php'>");
                echo("<button type = 'submit' name = 'edit$postID'>edit</button>");
                echo("</form>");

                //delete button
                echo("<form class='deleteButton'  style='padding: 3px;' method='POST' action='Profile.php'>");
                echo("<button type = 'submit' name = 'delete$postID'>delete</button>");
                echo("</form>");
        echo("</span>");
                //delete post
                if (isset($_POST["delete$postID"])) {
                    $deletePost = mysqli_query($mysqli, "DELETE FROM blogpost WHERE blogPostID = '$postID'");
                    echo "<script> window.location.href='Profile.php'</script>";
                }

                //edit post
                if (isset($_POST["edit$postID"])) {
                    echo("Hllo");
                    echo("<form method='POST'>");
                    echo("<textarea name='newText'>{$post['blogPostText']}</textarea>");
                    echo("<button type='submit' name='SubmitEdit$postID'>Submit Changes</button>");
                    echo("</form>");
                }

                if (isset($_POST["SubmitEdit$postID"])) {
                    echo("still working");
                    if($_POST['newText'] != "") { //improve upon this as you can just put in blank spaces
                        $updateBlogPostQuery = mysqli_query($mysqli, "UPDATE blogpost SET blogPostText = '{$_POST['newText']}' WHERE blogPostID = '$postID'");
                        echo "<script> window.location.href='Profile.php'</script>";
                    }
                }

                echo("<div id='overlay'>");
                echo("</div>");

                //gather total likes and adds it to post to display and store in the database
                $likesQuery = mysqli_query($mysqli, "SELECT count(blogpostID) As 'likeCount' FROM userlikedposts WHERE blogpostID = '$postID'");
                $LikeNum = mysqli_fetch_assoc($likesQuery);

                //updates likes in post table
                $storeLikesQuery = $mysqli->prepare("UPDATE blogpost SET likesOnPost = '{$LikeNum['likeCount']}' WHERE blogpostID = '$postID'");
                $storeLikesQuery->execute();

                //updates total likes in user table
                $totalLikesQuery = $mysqli->prepare("UPDATE user SET profileLikes = (SELECT SUM(likesOnPost) As 'totalLikes' FROM blogpost WHERE userID = '$SessionUser') WHERE userID = '$SessionUser'");
                $totalLikesQuery->execute();
                
                //update display
                $displayTotalLikes = mysqli_query($mysqli, "SELECT profileLikes FROM user WHERE userID = '$SessionUser'");
                While ($displayLikes = mysqli_fetch_assoc($displayTotalLikes)) {
                $_SESSION['profileLikes'] = $displayLikes['profileLikes']; 
                }

                //find number of comments
                $numOfCommentsQuery = mysqli_query($mysqli, "SELECT COUNT(blogPostID) AS NumOfComments FROM commentblogpost WHERE blogPostID = '$postID'");

                While ($numOfComments = mysqli_fetch_assoc($numOfCommentsQuery)) {
                    
                    
                    $commentNum = $numOfComments['NumOfComments'];#
                    
                    
                    $numCommentsStore = mysqli_query($mysqli, "UPDATE blogpost SET NumOfComments = '$commentNum' WHERE blogPostID = '$postID'"); //UPDATE total number of comments in blogpost record
                    
                    
                    //toggle comments
                    if ($post['commentsEnabled'] == "on") {
                        echo("<span><a href='comment.php?post=$postID'>Comments($commentNum)</a></span>");
                    }
                    else if ($post['commentsEnabled'] == "") {
                        echo("<div class='smallCommentText'>");
                        echo("Comments Disabled");
                        echo("</div>");
                    }
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
                    echo "<script> window.location.href='Profile.php'</script>";
                }
            }
        }
    ?>
    </div>
</body>
</html>