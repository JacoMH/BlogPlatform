<?php
    //select profile picture from db
    $TopOfProfileQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$SessionUser'");
    
    //if submit profile picture image
    if (isset($_POST['confirmProfilePicture'])) {
        $updatedPic = $_POST['profilePictureLink'];
        $updateProfilePic = mysqli_query($mysqli, "UPDATE user SET profilePicture = '$updatedPic' WHERE userID = '$SessionUser'");
        $_SESSION['profilePicture'] = $_POST['profilePictureLink'];      
    } 
?>


<!DOCTYPE html>
<html lang="en">
<body>
    <div class="banner">
        <?php
    echo("<button type='Submit' style= 'all: unset;' name='BannerPicBtn'><img class = 'BannerPic' src= {$profileInfo['bannerPicture']} alt='Profile Picture'></button>"); // import image from image address on web
    ?>
    </div> 
    <?php
    echo("<div class = 'welcome'>");
    
    echo("<div>");
    echo("Welcome back, "); echo($_SESSION["firstName"]);
    echo("</div>");

    While($profileInfo = mysqli_fetch_assoc($TopOfProfileQuery)) {
    echo("Profile Likes: "); echo($profileInfo['profileLikes']);
    echo("</div>");

    echo("<form class='profilePhoto' method='POST'>");
        echo("<button type='Submit' style= 'all: unset;' name='profilePic'><img class = 'profilePhoto' src= {$profileInfo['profilePicture']} alt='Profile Picture'></button>"); // import image from image address on web
    echo("</form>");
    
}   
    $profilePicQuery = mysqli_query($mysqli, "SELECT profilePicture FROM user WHERE userID = '$SessionUser'");
    if (isset($_POST['profilePic'])) {
        While ($currentProfilePic = mysqli_fetch_assoc($profilePicQuery)) {
                echo("<form method='POST' action='Profile2.php'>");
                echo("<input type='text' name='profilePictureLink' value = '{$currentProfilePic['profilePicture']}' placeholder = 'image address here...'>");
                echo("<button type='submit' name='confirmProfilePicture'>add</button>");
                echo("</form>");
        }
    }
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
</body>
</html>