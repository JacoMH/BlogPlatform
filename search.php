<?php
    require_once('includes/config.php');
    $Query = $_GET['s'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search | <?php echo($Query) ?></title>
</head>
<body>
    <?php
    require_once('includes/HomeShortcut.php');
    ?>
    <search>
        <form class="search" method="POST">
            <input  class = "search" name="searchQuery" type="text" placeholder="Search For User.." value= <?php echo("$Query"); ?>>
            <button type="submit" name="searchButton">Search</button>
        </form>
    </search>
            <?php
                //search from this page
                if (isset($_POST['searchButton'])) {
                    $search = $_POST['searchQuery'];
                    header("Refresh:0; url='search.php?s=$search'");

                }

                //search
                if ($_GET['s']) {
                    $findUsersQuery = "SELECT * FROM user WHERE username LIKE '%$Query%' OR userID LIKE '%$Query%'";
                    $findUsersLink = mysqli_query($mysqli, $findUsersQuery);           
                    $findUsersResult = mysqli_fetch_assoc($findUsersLink);

                    
                    if (empty($findUsersResult)) { //if no results
                        echo("No Results.");
                    }
                    else {
                        while ($findUsersResult = mysqli_fetch_assoc($findUsersLink)) { //if results
                            $username = $findUsersResult['username'];
                            $userID = $findUsersResult['userID'];
                            echo("<h3>$username</h3>");
                            echo("<a href='user.php?user=$userID'>profile</a>");
                        }
                    }

                }
            ?>
    </div>
</body>
</html>