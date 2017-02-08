<?php
include('templates/header.php');
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_POST['password'], $_POST['username'])) {
        require_once '../src/connection.php';
        require_once '../src/User.php';
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user=USER::loadUserByUsername($conn, $username);
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
        echo "<p>Zalogowałeś się " . $user->getUsername() . " Twoje id to " . $user->getId() . "</p>";
        $_SESSION['user'] = $user;
        header('Location: Index.php');
        exit;
    }
}
?>
<div class="container" align="center">
    <form class="form-horizontal col-lg-12" action="#" method="post">
        <fieldset>
            <legend>Logowanie</legend>
            <div class="form-group" align="center">
                <label class="col-lg-2 control-label">Nazwa użytkownika</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" name="username" placeholder="Username">
                </div>
            </div>
            <div class="form-group" align="center">
                <label class="col-lg-2 control-label">Hasło</label>
                <div class="col-lg-3">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group" align="center">
                <div class="col-lg-3 col-lg-offset-2">
                    <button type="submit" class="btn btn-primary">Zaloguj</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
include('templates/footer.php');
?>
