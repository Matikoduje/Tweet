<?php
include('templates/header.php');
    if (!isset($_SESSION['user'])) {
        echo "<h4>Witam na stronie. Aby móc korzystać z serwisu należy się zalogować</h4>";
    } else {
        ?>
    <p>Zalogowany jesteś</p>
<?php
    }
include('templates/footer.php');
?>

