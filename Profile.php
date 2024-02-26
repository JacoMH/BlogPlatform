
<?php
    require_once('includes/config.php');
    session_start();
    $SessionUser = $_SESSION['userID'];
    $postContent = "";
    
    //display posts made by user
    $userPostQuery = "SELECT * FROM blogpost WHERE userID = '$SessionUser'";
    $result = mysqli_query($mysqli, $userPostQuery);
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
            include("Includes/header2.php");
        ?>
        <div class="banner">
            
        </div>
        <div class="topbanner">
        <?php
        echo("Welcome back, "); echo($_SESSION["firstName"]);
        ?>
        </div>
        <div class="profilePic">
            
        </div>
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
            <input type="checkbox" name="commentToggle" checked>Enable Comments
            </div>
        </form>
        <?php
            if (isset($_POST['makePostButton']) && $_POST['textContent'] != "") {
                $postContent = $_POST['textContent'] ?? null;
                $commentToggle = $_POST['commentToggle'] ?? null;
                $addToPosts = $mysqli->prepare("INSERT INTO blogpost (userID, blogPostText, commentsEnabled, DateAndTime) VALUES ('$SessionUser', '$postContent', '$commentToggle', now())");
                $addToPosts->execute();
                header("Refresh:0");
            }
        ?>
        <div class="AllPostsContainer">
            <?php
            //filter will fetch everything again but in different orders, refresh page to do it
            //add like button, works by having a seperate table to identify all the posts that have been liked by the same user.
            //do if statements depending on the type of post it is, e.g. if its just text then do a just text post, with images has a diff format etc.
            echo("<form method='POST' action='comments.php'>");
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
                if ($row['blogPostText'] != "" && $row['blogPostImage'] == "" && $row['blogPostLink'] == "" && $row['blogPostVideo'] == ""){
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo("</div>");
                }
                else if ($row['blogPostText'] != "" && $row['blogPostImage'] != "" && $row['blogPostLink'] == "" && $row['blogPostVideo'] == "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostImage']);
                    echo("</div>");
                }
                else if($row['blogPostText'] != "" && $row['blogPostImage'] != "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] == "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostImage']);
                    echo($row['blogPostLink']);
                    echo("</div>");
                }
                else if($row['blogPostText'] != "" && $row['blogPostImage'] != "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] != "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostImage']);
                    echo($row['blogPostLink']);
                    echo($row['blogPostVideo']);
                    echo("</div>");
                }
                else if($row['blogPostText'] != "" && $row['blogPostImage'] == "" && $row['blogPostLink'] != "" && $row['blogPostVideo'] != "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostLink']);
                    echo($row['blogPostVideo']);
                    echo("</div>");
                }
                else if($row['blogPostText'] != "" && $row['blogPostImage'] == "" && $row['blogPostLink'] == "" && $row['blogPostVideo'] != "") {
                    echo("<div class = 'postContent'>");
                    echo($row["blogPostText"]);
                    echo($row['blogPostVideo']);
                    echo("</div>");
                }
                echo("</div>");
                //toggle comments
                if ($row['commentsEnabled'] == "on") {
                    echo("<button type='submit' name='commentsButton'>Comments</button>");
                }
                else if ($row['commentsEnabled'] == "") {
                    echo("<div class='smallCommentText'>");
                    echo("Comments Disabled");
                    echo("</div>");
                }

            if(isset($_POST['commentsButton'])) {
                alert("hello");
                $_SESSION['postUsername'] = $profile['username'] ?? null;
                $_SESSION['postText'] = $row['blogPostText'] ?? null;
                $_SESSION['postImage'] = $row['blogPostImage'] ?? null;
                $_SESSION['postLink'] = $row['blogPostLink'] ?? null;
                $_SESSION['postVideo'] = $row['blogPostVideo'] ?? null;
            }
            echo("</form>");
            }
            ?>
        </div>
        
        <?php
                //query which selects all profile stuff, lists posts etc. made. If query returns nothing then 2 buttons are displayed telling the user to log in or reg
                include("Includes/footer.php");
        ?>
    </main>
</body>
</html>