<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home | Blog Site</title>
       <link rel="stylesheet" type="text/css" href="css\mobile.css">
    <link rel="stylesheet" type"text/css" href="css\desktop.css" media="only screen and (min-width: 800px)">
</head>
<body>
    <main class="container">
        <div style="flex-direction: row-reverse; display: flex;">
        <?php
        include("Includes/header.php");
        ?>
        </div>

        <main>
            <search class = "">
                <input type="text" placeholder="Search..">
            </search>
        </main>
    
        <?php
        include("Includes/footer.php");
        ?>
    </main>
</body>
</html>