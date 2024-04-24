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
        if (empty($_SESSION['userID'])) {
            $_SESSION['jobTitle'] = "";
        }

        if (!empty($_SESSION['username'])) {
            require_once('includes/profileShortcut.php');
            $SessionUser = $_SESSION['userID'];
            $_SESSION['on'] = "yes";
        }
        else if ($_SESSION['jobTitle'] == "user") {
            include("includes/HomeShortcut.php");
        }
        else {
            require_once('includes/loginReg.php');
            $_SESSION['on'] = "no";
            $_SESSION['jobTitle'] = "signedOut";  //tells different parts of the site to disable/enable features based on if we are logged in or not
            $SessionUser = "signedOut";
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
                    echo "<script> window.location.href='search.php?s=$searchQuery'</script>";
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

        
        //filter
        $currentFilter = "";
        if (isset($_POST['Filters'])) {
            if ($_POST['Filters'] == "Most Recent") {
                $mostLikedPostsQuery = mysqli_query($mysqli, "SELECT * FROM blogpost ORDER BY DateAndTime DESC LIMIT 10"); 
                $currentFilter = "Most Recent";
                echo($currentFilter);
            }
            else if($_POST['Filters'] == "Most Liked Posts") {
                //put on number of comments next to comment link, include comment number with post
                $mostLikedPostsQuery = mysqli_query($mysqli, "SELECT * FROM blogpost ORDER BY likesOnPost DESC LIMIT 10");
                $currentFilter = "Most Liked Posts";
                echo($currentFilter);
            }
            else if($_POST['Filters'] == "Most Commented") {
                $mostLikedPostsQuery = mysqli_query($mysqli, "SELECT * FROM blogpost ORDER BY NumOfComments DESC LIMIT 10");
                $currentFilter = "Most Commented";
                echo($currentFilter);
            }
        }
        else{
            $mostLikedPostsQuery = mysqli_query($mysqli, "SELECT * FROM blogpost ORDER BY likesOnPost DESC LIMIT 10"); 
            $currentFilter = "Most Liked Posts";
            echo($currentFilter);
        }

        echo("<div class='AllPostsContainer'>");
        ?>
            <!-- filter -->
           <form method="POST" action='Home.php' style="display: flex; justify-content: center;">
            <label>Sort By:</label>
            <select name="Filters">
                <option selected="" disabled="" style='display: none;'><?php echo($currentFilter); ?></option>
                <option value="Most Liked Posts">Most Liked Posts</option>
                <option value="Most Recent">Most Recent</option>
                <option value="Most Commented">Most Commented</option>
            </select>
            <button type="submit" name="filterConfirm">Go</button>
            </form>
        <?php
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
                    
                    //if sessionUser made the post they get these permissions on it
                    if ($_SESSION['on'] == "yes") {
                        if ($post['userID'] == $SessionUser) {
                            
                            echo("<div style='display: flex; flex-direction: row;'>");
                            //edit button
                            echo("<form class='editButton' method='POST' action='Home.php' style='padding: 3px;'>");
                            echo("<button type = 'submit' name = 'edit$postID'>edit</button>");
                            echo("</form>");

                            //delete button
                            echo("<form class='deleteButton' method='POST' action='Home.php' style='padding: 3px;'>");
                            echo("<button type = 'submit' name = 'delete$postID'>delete</button>");
                            echo("</form>");

                            //delete post
                            if (isset($_POST["delete$postID"])) {
                                $deletePost = mysqli_query($mysqli, "DELETE FROM blogpost WHERE blogPostID = '$postID'");
                                echo "<script> window.location.href='Home.php'</script>";
                            }

                            //edit post
                            if (isset($_POST["edit$postID"])) {
                                echo("<form method='POST'>");
                                echo("<textarea name='newText'>{$post['blogPostText']}</textarea>");
                                echo("<button type='submit' name='SubmitEdit$postID'>Submit Changes</button>");
                                echo("</form>");
                            }
                            echo("</div>");
                            if (isset($_POST["SubmitEdit$postID"])) {
                                if($_POST['newText'] != "") { //improve upon this as you can just put in blank spaces
                                    $updateBlogPostQuery = mysqli_query($mysqli, "UPDATE blogpost SET blogPostText = '{$_POST['newText']}' WHERE blogPostID = '$postID'");
                                    echo "<script> window.location.href='Home.php'</script>";
                                }

                    echo("</form>");
                }   
                }
                    }




                    



                    
                        if ($_SESSION['on'] = "yes") {
                            if ($_SESSION['jobTitle'] == 'Admin' || $_SESSION['jobTitle'] == 'Moderator') {
                                //if admin or moderator, can delete comment
                                echo("<form class='deleteButton' method='POST' action='Home.php'>");
                                echo("<button type = 'submit' name = 'deleted$postID'>delete</button>");
                                echo("</form>");
                                }
        
                                if (isset($_POST["deleted$postID"])) {
                                $deleteCommentQuery = mysqli_query($mysqli, "DELETE FROM commentblogpost WHERE commentID = '$commentID'");
                                echo "<script> window.location.href='comment.php?post=$currentPost''</script>";
                                }
                                
                                        
                                //update total likes on post (in post likes table)
                                $updateBlogPostLikesQuery = mysqli_query($mysqli, "SELECT count(blogPostID) As count FROM userlikedposts WHERE blogPostID = '$postID'");
                                $LikeNum = mysqli_fetch_assoc($updateBlogPostLikesQuery);


                                //update likes in post table
                                $LikeBlogPostQuery = mysqli_query($mysqli, "UPDATE blogpost SET LikesOnPost = '{$LikeNum['count']}' WHERE blogPostID = '$postID'");
                           
                                   if (!($post['userID'] == $_SESSION['userID'])) {
                                     //add context button
                                     echo("<form class='contextButton' method='POST' action='Home.php' style='padding: 3px;'>");
                                     echo("<button type = 'submit' name = 'context$postID'> Add Context</button>");
                                     echo("</form>");
                 
                                     //add context menu
                                     if (isset($_POST["context$postID"])) {
                                         echo("<form method='POST' action='Home.php'>");
                                         echo("<textarea name='newContext'>{$post['BlogPostContext']}</textarea>");
                                         echo("<button type='submit' name='contextSubmit$postID'>Submit Changes</button>");
                                         echo("</form>");
                                     }
                 
                                     //submit context
                                     if (isset($_POST["contextSubmit$postID"]) && $_POST['newContext'] != "") {
                                         $updateContext = mysqli_query($mysqli, "UPDATE blogpost SET BlogPostContext = '{$_POST['newContext']}' WHERE blogPostID = '$postID'");
                                         echo "<script> window.location.href='Home.php''</script>";
                                     }
                                   }


                                //create like button
                                echo("<form class='likeButton' method='POST' action='Home.php' style='display: flex; flex-direction: row;'>");
                                echo("<button name = 'Like$postID'>like</button>");
                                    echo("<label>{$LikeNum['count']}</label>");
                                echo("</form>");
                                
                                //like/dislike post
                                $BlogPostLikesQuery = mysqli_query($mysqli, "SELECT count(blogPostID) As count FROM userlikedposts WHERE blogPostID = '$postID'");
                                while($ShowLikes = mysqli_fetch_assoc($BlogPostLikesQuery)) {
                                    }
        
                                if (isset($_POST["Like$postID"]) && $SessionUser != "signedOut") {
                                    if (isset($_POST["Like$postID"]) && $SessionUser != "signedOut") {
                                        $checkIfLikedQuery = mysqli_query($mysqli, "SELECT * FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '$postID'"); //check if user has liked post or not
                                        $CheckLikedNumRows = mysqli_num_rows($checkIfLikedQuery); //found a method of liking and unliking a post without inserting duplicate keys into the database here: https://stackoverflow.com/questions/2848904/check-if-record-exists By User Dominic Rodger
                                        $truefalsebool = 'false';
                                        if ($CheckLikedNumRows > 0) {
                                            $removeLike = $mysqli->prepare("DELETE FROM userlikedposts WHERE userID = '$SessionUser' AND blogPostID = '$postID'");
                                            $removeLike->execute();
                                        }
                                        else {
                                            $addLike = $mysqli->prepare("INSERT INTO userlikedposts (userID, blogPostID) VALUES($SessionUser, $postID)");
                                            $addLike->execute();

        
                                        }
                                    }
                                    echo "<script> window.location.href=Home.php;</script>";
                                }
                        }
                        










                    
                    
                    //find number of comments
                    $numOfCommentsQuery = mysqli_query($mysqli, "SELECT COUNT(blogPostID) AS NumOfComments FROM commentblogpost WHERE blogPostID = '$postID'");
                    
                    While ($numOfComments = mysqli_fetch_assoc($numOfCommentsQuery)) {
                        $commentNum = $numOfComments['NumOfComments'];

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
                echo("</div>");
                
                echo("<div class = 'postContent' style='background: green; padding: 10px; border-radius: 8px;'>");
                echo("<textarea readonly style = 'background: green; border: none;'>{$post['blogPostText']}</textarea>");
              
                if ($_SESSION['jobTitle'] != "signedOut") {
                    echo("<img class='tempPostImage' src= '{$post['blogPostImage']}'>");
                }
                else if(!empty($post['blogPostImage'])){
                    echo("<span style='background: #adb013; border: 3px solid black;'>Create Account Or Sign In to View Photos</span>");
                }
                
                if ($_SESSION['jobTitle'] != "signedOut") {
                    echo("<a href='{$post['blogPostLink']}'>{$post['blogPostLink']}</a>");
                }
                else if(!empty($post['blogPostLink'])){
                    echo("<span style='background: #adb013; border: 3px solid black;'>Create Account Or Sign In to View Links</span>");
                }
                
                //video
                if (!empty($post['blogPostVideo']) && $_SESSION['jobTitle'] != "signedOut") {
                $parse = parse_url($post['blogPostVideo']); 
                $query = $parse['query'];
                $final=substr($query,2);
                echo("<iframe width='420' height='315' src='https://www.youtube.com/embed/{$final}'></iframe>");
                }
                else if(!empty($post['blogPostVideo'])){
                    echo("<span style='background: #adb013; border: 3px solid black;'>Create Account Or Sign In to View Videos</span>");
                }

                if (!empty($post['BlogPostContext'])) {
                    echo("<div style = 'display: flex; flex-direction: column; background: grey; border: 1px solid black; margin-top: 3px;'>");
                    echo("<label>Admin/Moderator gave context for this post:</label>");
                    echo("<textarea style='background: grey; border: none;'>{$post['BlogPostContext']}</textarea>");
                    echo("</div>");
                }
                echo("</div>");

    
                }
            echo("</div>");
        }
        echo("</div>");
        ?>
        <?php
        include("Includes/footer.php");
        ?>
        <script src="js/main.js">
            refresh();
        </script>
    </main>
</body>
</html>