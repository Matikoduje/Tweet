<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Twitter</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
<div class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a href="Index.php" class="navbar-inverse navbar-brand">Twitter</a>
        </div>
        <ul class="nav navbar-nav">
            <?php
            if (isset($_SESSION['user'])) {
                ?>
                <li>
                    <a href="createTweet.php">Stwórz Twiita</a>
                </li>
                <?php
            } else {
                ?>
                <li>
                    <a href="exe.php">Podstrona 1</a>
                </li>
                <li>
                    <a href="http://news.bootswatch.com">Podstrona 2</a>
                </li>
                <?php
            }
            ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php
            if (isset($_SESSION['user'])) {
                ?>
                <li><a href="logout.php"><span class="label label-primary" style="color: black">Wyloguj się</span></a>
                </li>
                <?php
            } else {
                ?>
                <li><a href="register.php"><span class="label" style="color: black">Zarejestruj się</span></a></li>
                <li><a href="login.php"><span class="label label-success" style="color: black">Logowanie</span></a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
