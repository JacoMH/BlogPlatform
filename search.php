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
        <form class="search" method="GET">
            <input  class = "search" name="searchQuery" type="text" placeholder="Search For User.." value=<?php $Query ?>>
            <button type="submit" name="searchButton">Search</button>
        </form>
    </search>
            <?php
                //search from this page
                $enteredSearch = $_GET['searchQuery'];
                if (isset($_GET['searchButton'])) {
                    $enteredSearch = $_GET['searchQuery'];
                }

                //search
                if ($_GET['s']) {
                    $searchQuery = $_GET['s'];
                    $findUsersQuery = "SELECT * FROM user WHERE username LIKE '%$searchQuery%' OR userID LIKE '%$searchQuery%'";
                    $findUsersLink = mysqli_query($mysqli, $findUsersQuery);           
                    $findUsersResult = mysqli_fetch_assoc($findUsersLink);
                }
                    while ($findUsersResult = mysqli_fetch_assoc($findUsersLink)) {
                        $username = $findUsersResult['username'];
                        $userID = $findUsersResult['userID'];
                        echo("<h3>$username</h3>");
                        echo("<a href='user.php?user=$userID'>profile</a>");
                    }
            ?>
    </div>
</body>
</html>