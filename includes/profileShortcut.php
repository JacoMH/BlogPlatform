<header>
    <div id="logo">
       <h2>Blog Platform</h2> 
    </div>
    <nav>
        <ul>
            <li><a href="Profile.php"><img src=<?php $_SESSION['profilePic'] ?>></a></li>
            <p> <?php echo($_SESSION['username']); ?> </p>
        </ul>
    </nav>
</header>