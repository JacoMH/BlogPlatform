<header>
    <div id="logo">
    <a href="Home.php"><h2>Blog Platform</h2></a> 
    </div>
    <nav>
        <ul>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </nav>
    <nav>
        <ul>
            <li class = "profilePhoto"><a href="Profile.php"><img src=<?php echo($_SESSION['profilePicture'])?> alt = "Profile Picture"></a></li>
            <p> <?php echo($_SESSION['username']); ?> </p>
        </ul>
    </nav>
</header>