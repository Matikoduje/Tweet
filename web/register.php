<?php
include('templates/header.php');
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_POST['email'], $_POST['password'], $_POST['password2'], $_POST['username'])) {
        $isOk = true;
        require_once '../src/connection.php';
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        $user = new User();
        if (!$user->validateEmail($email)) {
            echo "<div class=\"alert alert-danger\">";
            echo "<strong>Wprowadzono nie poprawny adres e-mail!</strong>";
            echo "</div>";
            $isOk = false;
        }
        if (!$user->validatePassword($password, $password2)) {
            echo "<div class=\"alert alert-danger\">";
            echo "<strong>Wprowadzone hasła są różne!</strong>";
            echo "</div>";
            $isOk = false;
        }
        if (!$user->validateUsername($username)) {
            echo "<div class=\"alert alert-danger\">";
            echo "<strong>Login musi mieć co najmniej 3 znaki ale nie więcej niż 15!</strong>";
            echo "</div>";
            $isOk = false;
        }
        if (0 != $user->compareUsername($conn, $username)) {
            echo "<div class=\"alert alert-info\">";
            echo "<strong>Użytkownik o podanym loginie jest już w bazie</strong>";
            echo "</div>";
            $isOk = false;
        }
        if (0 != $user->compareEmail($conn, $email)) {
            echo "<div class=\"alert alert-info\">";
            echo "<strong>Email jest już wykorzystany do rejestracji</strong>";
            echo "</div>";
            $isOk = false;
        }
        if ($isOk) {
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($password);

            $user->save($conn);
            echo "<div class=\"alert alert-success\">";
            echo "<strong>Zarejestrowałeś się !</strong>";
            echo "</div>";
        }
    }
}
?>
<div class="container" align="center">
    <form class="form-horizontal col-lg-12" action="#" method="post">
        <fieldset>
            <legend>Formularz rejestracyjny</legend>
            <div class="form-group">
                <label class="col-lg-2 control-label">Email</label>
                <div class="col-lg-3">
                    <input type="email" class="form-control" name="email" placeholder="E-mail">
                </div>
            </div>
            <div class="form-group" align="center">
                <label class="col-lg-2 control-label">Hasło</label>
                <div class="col-lg-3">
                    <input type="password" class="form-control" name="password" placeholder="Hasło">
                </div>
            </div>
            <div class="form-group" align="center">
                <label class="col-lg-2 control-label">Potwierdź hasło</label>
                <div class="col-lg-3">
                    <input type="password" class="form-control" name="password2" placeholder="Potwierdź hasło">
                </div>
            </div>
            <div class="form-group" align="center">
                <label class="col-lg-2 control-label">Login</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" name="username" placeholder="Login">
                </div>
            </div>
            <div class="form-group" align="center">
                <div class="col-lg-3 col-lg-offset-2">
                    <button type="submit" class="btn btn-primary">Rejestruj</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
include('templates/footer.php');
?>
