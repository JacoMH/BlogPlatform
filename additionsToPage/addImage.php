<!DOCTYPE html>
<html lang="en">
<body>
    <form method="POST">
    Image Address: <input type="text" name="ImageLink">
    <input type="submit" name="addBtn" value="add">
    </form>
    <?php
        echo("Hi");
        echo(isset($_POST['addBtn']));
    ?>
    <?php
    if (isset($_POST['addBtn'])) {
        echo("Hello");
        if ($_POST['ImageLink'] != "") {
            echo("Hello");
            $ImageLink = $_POST['ImageLink'];
            echo($ImageLink);
            $insertImageLink = $mysqli->prepare("INSERT INTO user ('profilePicture') VALUES ('$ImageLink')");
            $insertImageLink->execute();
            //query to put into database
        }
        else {
            echo("failed to import.");
        }
    }
    ?>
</body>
</html>