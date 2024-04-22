<header style= "display: flex; flex-direction: row; justify-content: space-between;">
    <div id="logo"><a href="Home.php"><h2>Blog Platform</h2></a></div> 
    <div class="profileShortcut">
    <div style="align-content: center; padding-right: 20px; height: 20px; border-radius: 8px; padding: 10px; margin: 5px; background: green;"><a class = "profileShortcut" href="logout.php">Log Out</a></div>
        <div style="">
        <div class = "profilePhoto" ><a href="admin/Home.php"><img class = "profilePhoto" style = ""src=<?php echo($_SESSION['profilePicture'])?> alt = "Profile Picture"></a></div>
        <span style="display: flex; justify-content: center;"><?php echo($_SESSION['username']); ?></span>
        </div>
    </div>
</header>