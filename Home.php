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
           <form method="POST" action="Home.php" style="display: flex; justify-content: center;" >
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
                if ($post['userID'] == $SessionUser) {
                    
                            //edit button
                            echo("<form class='editButton' method='POST' action='Home.php'>");
                            echo("<button type = 'submit' name = 'edit$postID'>edit</button>");
                            echo("</form>");

                            //delete button
                            echo("<form class='deleteButton' method='POST' action='Home.php'>");
                            echo("<button type = 'submit' name = 'delete$postID'>delete</button>");
                            echo("</form>");

                            //delete post
                            if (isset($_POST["delete$postID"])) {
                                $deletePost = mysqli_query($mysqli, "DELETE FROM blogpost WHERE blogPostID = '$postID'");
                                echo "<script> window.location.href='Home.php'</script>";
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
                                    echo "<script> window.location.href='Home.php'</script>";
                                }
                }

                }
                    
                    
                    
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
                echo("<textarea readonly style = 'background: green; border: none;'>{$post['blogPostText']}</textarea>");
                echo("<img class='tempPostImage' src= '{$post['blogPostImage']}'>");
                echo($post['blogPostLink']);
                echo($post['blogPostVideo']);            
                echo("</div>");

    
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