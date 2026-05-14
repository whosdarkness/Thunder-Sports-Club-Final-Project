<?php
session_start();
include "ligamysql.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/expap/icon/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
        <header>
            <?php
            if (isset($_SESSION['user'])) {
                require 'menu.php';
            } else {
                require 'menu2.php';   
            }
        ?>
        </header>
        
        <?php
            if (isset($_SESSION['user'])) {
                include "homepage.php";
            } else {
                include "home.php";   
            }
        ?>

</body>
</html>