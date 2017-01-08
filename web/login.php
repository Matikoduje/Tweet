<?php

require_once '../src/connection.php';
require_once '../src/User.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ((isset($_POST['username'])) && (isset($_POST['password']))) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user=USER::loadUserByUsername($conn, $username); // ta metode mozemy uzywac przed stworzeniem obiektu stad statyczna
        
        if (false === $user) {
            echo "<p>Podano nieprawidłowy login bądź hasło</p>";
            exit;
        }
        
        if (password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = $user->getId();
        } else {
            echo "<p>Podano nieprawidłowy login bądź hasło</p>";
            exit;
        }
    }
}