<?php
    require_once('includes/config.php');
    $Query = $_GET['s'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type="text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
    <title>Search | <?php echo($Query) ?></title>
</head>
<body>
    <main class="container">
    <?php
    require_once('includes/HomeShortcut.php');
    ?>
    <search>
        <form class="search" method="POST">
            <input  class = "search" name="searchQuery" type="text" placeholder="Search For User.." value= <?php echo("$Query"); ?>>
            <button type="submit" style="padding: 10px 5px;" name="searchButton">Search</button>
        </form>
    </search>
            <?php
                //search from this page
                if (isset($_POST['searchButton'])) {
                    $search = $_POST['searchQuery'];
                    header("Refresh:0; url='search.php?s=$search'");

                }

                //search
                if ($_GET['s'] && $_GET['s'] != "") {
                    $findUsersQuery = "SELECT * FROM user WHERE username LIKE '%$Query%' OR userID LIKE '%$Query%'";
                    $findUsersLink = mysqli_query($mysqli, $findUsersQuery);           
                    $findUsersResult = mysqli_fetch_assoc($findUsersLink);
                    echo("hello");
                    echo("<div class='AllPostsContainer' style='padding: 30px 100%; max-padding: 30px 400px;'>");
                        while ($findUsersResult = mysqli_fetch_assoc($findUsersLink)) { //if results
                            echo($findUsersResult['username']);
                            $username = $findUsersResult['username'];
                            $userID = $findUsersResult['userID'];
                        echo("<div class='BloggerProfile'>");
                            echo("<a href='user.php?user=$userID'><img class = 'userPhoto' src='{$findUsersResult['profilePicture']}'></a>");
                            echo("<span class = 'username' style='font-size: large; display: flex; justify-content: center;'>$username</span>");
                        echo("</div>");
                    }
                    echo("</div>");

                }
                else{ //if no results
                    echo("No Results.");
                }
            ?>
    </div>
    </main>

</body>
</html>