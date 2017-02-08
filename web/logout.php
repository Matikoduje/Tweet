<?php
include('templates/header.php');
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    header('Location: Index.php');
    exit;
}
include('templates/footer.php');
?>
