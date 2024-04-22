<?php
    session_start();
    if(empty($_SESSION['username'])) {
        echo "<script> window.location.href='login.php'</script>";
    }
    require_once('includes/config.php');
    $SessionUser = $_SESSION['userID'];

    //fetch total profile likes
    $fetchUserInfo = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$SessionUser'");
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

                include("topOfProfile.php");
            ?>
            <!-- make post -->
            <form class="createPostContainer" style= "margin: auto; padding: 10px;" action="Profile.php" method="POST">
                        <div class= "AllPostsContainer" style="display: flex; flex-direction: row;">
                            <textarea id="w3review" name="textContent" rows="4" cols="50"></textarea>
                            <button class = "createButton" type="submit" name="makePostButton">Create Post</button>
                        
                       <?php
                       if (empty($_POST['postImageLink'])) {
                        echo("<img name= 'postImage' class='tempPostImage' src=''>");
                       }
                       else {
                        echo("<img name= 'postImage' class='tempPostImage' src='{$_POST['postImageLink']}'>");
                        $_SESSION['TempStoreImage'] = $_POST['postImageLink'];
                       }
                       
                       ?>
                            <link></link>
                        </div>
                        <div style="display: flex; flex-direction: row;">
                        <form method= 'POST'>
                        <button class = "createButton" type="submit" name="imageContent">Add Image</button>
                        </form>
                        <button class = "createButton" type="button" name="linkContent">Add Link</button>
                        <button class = "createButton" type="button" name="videoContent">Add Video</button>
                        <input type="checkbox" name="commentToggle" checked>Enable Comments</input>
                        </div>
            </form>
                    <?php
                        $_POST['postImageLink'] = "";
                        if (isset($_POST['imageContent']) && $_POST['postImageLink'] != "") {
                            echo("<form method='POST' action='Profile.php'>");
                            echo("<input type='text' name='postImageLink' value = '{$_POST['postImage']}' placeholder = 'image address here...'>");
                            
                            echo("<button type='submit' name='confirmImagePicture'>add</button>");
                            echo("</form>");
                        }
                        else if (isset($_POST['imageContent']) && $_POST['postImageLink'] == "") {
                            echo("<form method='POST' action='Profile.php'>");
                            echo("<input type='text' name='postImageLink' placeholder = 'image address here...'>");
                            echo("<button type='submit' name='confirmImagePicture'>add</button>");
                            echo("</form>");
                        }

                        if (isset($_POST['makePostButton']) && $_POST['textContent'] != "") {
                            $postContent = $_POST['textContent'] ?? null;
                            $commentToggle = $_POST['commentToggle'] ?? null;
                            $imageContent = $_SESSION['TempStoreImage'];
                            $postLink = 
                            $postVideo = 
                            $addToPosts = $mysqli->prepare("INSERT INTO blogpost (userID, blogPostText, commentsEnabled, blogPostImage, DateAndTime) VALUES ('$SessionUser', '$postContent', '$commentToggle', '$imageContent', now())");
                            $addToPosts->execute();
                        //    echo "<script> window.location.href='Profile.php'</script>";
                        }
        ?>
        </div>
            
            
            <?php
                include("ProfilePosts.php");
            ?>


 
            

    </main>
    <script src="js/main.js"></script>
</body>
</html>