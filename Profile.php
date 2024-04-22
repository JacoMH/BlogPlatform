<?php
    session_start();
    if(empty($_SESSION['username'])) {
        header("refresh:0; url='login.php'");
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
                echo($_SESSION['profileLikes']);
                include("Includes/HomeShortcut.php");

                include("topOfProfile.php");
            ?>
            <!-- make post -->
            <form class="createPostContainer" style= "margin: auto; padding: 10px;" action="Profile.php" method="POST">
                        <div class= "AllPostsContainer" style="display: flex; flex-direction: row;">
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
        </div>
            
            
            <?php
                include("ProfilePosts.php");
            ?>


 
            

    </main>
    <script src="js/main.js"></script>
</body>
</html>