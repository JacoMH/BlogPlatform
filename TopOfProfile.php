<?php
    //select profile picture from db
    $TopOfProfileQuery = mysqli_query($mysqli, "SELECT * FROM user WHERE userID = '$SessionUser'");
    
    //if submit profile picture image
    if (isset($_POST['confirmProfilePicture'])) {
        $updatedPic = $_POST['profilePictureLink'];
        $updateProfilePic = mysqli_query($mysqli, "UPDATE user SET profilePicture = '$updatedPic' WHERE userID = '$SessionUser'");
        $_SESSION['profilePicture'] = $_POST['profilePictureLink'];  
    } 

    //if submit banner picture image
    if (isset($_POST['confirmBannerPicture'])) {
        $updatedBannerPic = mysqli_query($mysqli, "UPDATE user SET bannerPicture = '{$_POST['bannerPictureLink']}' WHERE userID = '$SessionUser'");
        $_SESSION['bannerPicture'] = $_POST['bannerPictureLink'];
    }
?>


<!DOCTYPE html>
<html lang="en">
<body>
    <?php
    echo("<form class='banner' method='POST'>");
    $currentBannerPicQuery = mysqli_query($mysqli, "SELECT bannerPicture FROM user WHERE userID = '$SessionUser'");
    While ($currentBannerPic = mysqli_fetch_assoc($currentBannerPicQuery)) {
        echo("<button type='Submit' style= 'all: unset;' name='BannerPicBtn'><img class = 'bannerPhoto' src= {$currentBannerPic['bannerPicture']} alt='Profile Picture'></button>"); // import image from image address on web
    }
    echo("</form>");

    //change banner picture
    $GetBanner = mysqli_query($mysqli, "SELECT bannerPicture FROM user WHERE userID = '$SessionUser'");
    if (isset($_POST['BannerPicBtn'])) {
        While ($getLinkOfBanner = mysqli_fetch_assoc($GetBanner)) {
            echo("<form method='POST' action='Profile.php'>");
            echo("<input type='text' name='bannerPictureLink' value = '{$getLinkOfBanner['bannerPicture']}' placeholder = 'image address here...'>");
            echo("<button type='submit' name='confirmBannerPicture'>add</button>");
            echo("</form>");
        }
    }

    echo("<div class = 'welcome'>");
    
    echo("<div>");
    echo("Welcome back, "); echo($_SESSION["firstName"]);
    echo("</div>");

    While($profileInfo = mysqli_fetch_assoc($TopOfProfileQuery)) {
    $profileLikes = $profileInfo['profileLikes'];

    echo("Profile Likes: "); echo($_SESSION['profileLikes']);
    echo("</div>");

    echo("<form class='profilePhoto' method='POST'>");
        echo("<button type='Submit' style= 'all: unset;' name='profilePic'><img class = 'profilePhoto' src= {$profileInfo['profilePicture']} alt='Profile Picture'></button>"); // import image from image address on web
    echo("</form>");
    
}   
    //change profile picture
    $profilePicQuery = mysqli_query($mysqli, "SELECT profilePicture FROM user WHERE userID = '$SessionUser'");
    if (isset($_POST['profilePic'])) {
        While ($currentProfilePic = mysqli_fetch_assoc($profilePicQuery)) {
                echo("<form method='POST' action='Profile.php'>");
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