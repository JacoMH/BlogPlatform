<?php
    session_start();
    if(empty($_SESSION['username'])) {
        echo "<script> window.location.href='login.php'</script>";
    }
    
    if($_SESSION['jobTitle'] == "Admin") {
        echo "<script> window.location.href='admin/Home.php'</script>";
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
            <form class="createPostContainer" style= "margin: auto; padding: 10px; display: flex; flex-direction: column;" action="Profile.php" method="POST">
                        <div class= "AllPostsContainer">
                        <div style='display:flex; flex-direction: row;'>
                            <textarea name="textContent" rows="4" cols="50"></textarea>
                            <button class = "createButton" type="submit" style='padding: 24px;' name="makePostButton">Create Post</button>
                        </div>
                        
                       <?php
                        

                       //image
                       if (!empty($_SESSION['TempStoreImage'])) {
                        echo("<form method='POST'>");
                        echo("<img name= 'postImage' class='tempPostImage' src='{$_SESSION['TempStoreImage']}'>");
                        echo("</form>");
                       }

                       //link
                       if (!empty($_SESSION['TempStoreLink'])) {
                        echo("<form method='POST'>");
                        echo("<a name= 'Link' class='tempPostLink' href='{$_SESSION['TempStoreLink']}'>{$_SESSION['TempStoreLink']}</a>");
                        echo("</form>");
                       }


                       //video
                       if (!empty($_SESSION['TempStoreVideo'])) {
                        $parse = parse_url($_SESSION['TempStoreVideo']); //learnt how to parse links here: https://www.scriptol.com/how-to/parsing-url.php#:~:text=1)%20Using%20the%20parse_url%20function,parameters%20into%20variables%20and%20values.
                        $query = $parse['query'];
                        $final=substr($query,2);
                        echo("<iframe width='420' height='315' src='https://www.youtube.com/embed/{$final}'></iframe>"); //learnt how to embed youtube videos here: https://developers.google.com/youtube/player_parameters
                       }
                       
                       ?>
                       <div style='display: flex; flex-direction: row;'>
                        <form method= 'POST'>
                        <button class = "createButton" type="submit" name="imageContent">Add Image</button>
                        </form>
                        <form method= 'POST'>
                        <button class = "createButton" type="submit" name="linkContent">Add Link</button>
                        </form>
                        <form method = 'POST'>
                        <button class = "createButton" type="submit" name="videoContent">Add Video</button>
                        </form>
                        <input type="checkbox" name="commentToggle" checked>Enable Comments</input>
                        </div>
                        </div>
            </form>
                    <?php
                    //image
                        if (isset($_POST['imageContent']) && $_SESSION['TempStoreImage'] != "") {
                            echo("<form method='POST' action='Profile.php'>");
                            echo("<input type='text' name='postImageLink' value = '{$_SESSION['TempStoreImage']}' placeholder = 'image address here...'>");
                            echo("<button type='submit' name='confirmImagePicture'>add</button>");
                            echo("</form>");
                        }
                        else if (isset($_POST['imageContent'])){
                            echo("<form method='POST' action='Profile.php'>");
                            echo("<input type='text' name='postImageLink' placeholder = 'image address here...'>");
                            echo("<button type='submit' name='confirmImagePicture'>add</button>");
                            echo("</form>");
                        }

                        if (isset($_POST['confirmImagePicture'])) {
                            $_SESSION['TempStoreImage'] = $_POST['postImageLink'];
                            echo "<script> window.location.href='Profile.php'</script>";
                        }




                    //link
                        if (isset($_POST['linkContent']) && $_SESSION['TempStoreLink'] != "") {
                    
                            echo("<form method='POST' action='Profile.php'>");
                            echo("<input type='text' name='postLink' value = '{$_SESSION['TempStoreLink']}' placeholder = 'link address here...'>");
                            echo("<button type='submit' name='confirmLink'>add</button>");
                            echo("</form>");
                        }
                        else if (isset($_POST['linkContent'])) {
                            echo("<form method='POST' action='Profile.php'>");
                            echo("<input type='text' name='postLink' placeholder = 'link address here...'>");
                            echo("<button type='submit' name='confirmLink'>add</button>");
                            echo("</form>");
                        }

                        if (isset($_POST['confirmLink'])) {
                            $_SESSION['TempStoreLink'] = $_POST['postLink'];
                            echo "<script> window.location.href='Profile.php'</script>";
                        }

                    
                        //video
                         if (isset($_POST['videoContent']) && $_SESSION['TempStoreVideo'] != "") {
                            echo("<form method='POST' action='Profile.php'>");
                            echo("<input type='text' name='postImageLink' value = '{$_SESSION['TempStoreVideo']}' placeholder = 'YouTube address here...'>");
                            echo("<button type='submit' name='confirmVideo'>add</button>");
                            echo("</form>");
                        }
                        else if (isset($_POST['videoContent'])) {
                            echo("<form method='POST' action='Profile.php'>");
                            echo("<input type='text' name='postVideo' placeholder = 'YouTube address here...'>");
                            echo("<button type='submit' name='confirmVideo'>add</button>");
                            echo("</form>");
                        }

                        if (isset($_POST['confirmVideo'])) {
                            $_SESSION['TempStoreVideo'] = $_POST['postVideo'];
                            echo "<script> window.location.href='Profile.php'</script>";
                        }

                        if (isset($_POST['makePostButton']) && $_POST['textContent'] != "") {
                            $postContent = $_POST['textContent'] ?? null;
                            $commentToggle = $_POST['commentToggle'] ?? null;
                            $imageContent = $_SESSION['TempStoreImage'] ?? null;
                            $postLink = $_SESSION['TempStoreLink'] ?? null;
                            $postVideo = $_SESSION['TempStoreVideo'] ?? null;
                            $addToPosts = $mysqli->prepare("INSERT INTO blogpost (userID, blogPostText, commentsEnabled, blogPostImage, blogPostLink, blogPostVideo, DateAndTime) VALUES ('$SessionUser', '$postContent', '$commentToggle', '$imageContent', '$postLink', '$postVideo', now())");
                            $addToPosts->execute();
                            $_SESSION['TempStoreImage'] = "";
                            $_SESSION['TempStoreLink'] = "";
                            $_SESSION['TempStoreVideo'] = "";
                            $_SESSION['TempPostText'] = "";
                            echo "<script> window.location.href='Profile.php'</script>";
                        }
                        else if (isset($_POST['makePostButton'])) {
                            $_SESSION['TempStoreImage'] = "";
                            $_SESSION['TempStoreLink'] = "";
                            $_SESSION['TempStoreVideo'] = "";
                            $_SESSION['TempPostText'] = "";
                           echo "<script> window.location.href='Profile.php'</script>";
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