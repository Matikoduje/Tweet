<?php

require_once '../src/connection.php';
require_once '../src/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ((isset($_POST['username'])) && (isset($_POST['email'])) && (isset($_POST['password']))) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $user = new User();
        
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        
        $user->save($conn);
    }
}