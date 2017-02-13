<?php
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Message.php';
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
<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a href="Index.php" class="navbar-brand"><span style="color: #004b63; font-weight: bold; font-size: 200%;" >Twitter</span></a>
        </div>
        <ul class="nav navbar-nav">
            <?php
            if (isset($_SESSION['user'])) {
                $username = User::findUserNameByUserId($conn, $_SESSION['user']);
                echo "<li><a href=\"profile.php\"><span class=\"label\" style=\"color: #008000; font-size: 120%; font-weight: bold;\">$username</span></a></li>";
            }
            ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php
            if (isset($_SESSION['user'])) {
                ?>
                <li><a href="editprofile.php"><span class="label" style="color: #004b63; font-size: 120%; font-weight: bold;">Edytuj profil</span></a>
                </li>
                <li><a href="logout.php"><span class="label" style="color: #800000; font-size: 120%; font-weight: bold;">Wyloguj się</span></a>
                </li>
                <?php
            } else {
                ?>
                <li><a href="register.php"><span class="label" style="color: #004b63; font-size: 120%; font-weight: bold;">Zarejestruj się</span></a></li>
                <li><a href="login.php"><span class="label" style="color: #008000; font-size: 120%; font-weight: bold;">Logowanie</span></a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
