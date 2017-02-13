<?php
include('templates/header.php');
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_POST['email'], $_POST['emailSubmit'])) {
        $isOk = true;
        $email = $_POST['email'];
        $user = User::loadUserById($conn, $_SESSION['user']);
        if (!$user->validateEmail($email)) {
            echo "<div class=\"alert alert-danger\">";
            echo "<strong>Wprowadzono nie poprawny adres e-mail!</strong>";
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
            $user->setEmail($email);
            if ($user->changeEmail($conn)) {
                echo "<div class=\"alert alert-success\">";
                echo "<strong>Zmieniono adres E-mail</strong>";
                echo "</div>";
            }
        }
    }
    if (isset($_POST['password1'], $_POST['password2'], $_POST['passSubmit'])) {
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        $user = User::loadUserById($conn, $_SESSION['user']);
        $isOk = true;

        if (!$user->validatePassword($password1, $password2)) {
            echo "<div class=\"alert alert-danger\">";
            echo "<strong>Wprowadzone hasła są różne!</strong>";
            echo "</div>";
            $isOk = false;
        }
        if ($isOk) {
            $user->setPassword($password1);
            if ($user->changePassword($conn)) {
                echo "<div class=\"alert alert-success\">";
                echo "<strong>Zmieniono hasło</strong>";
                echo "</div>";
            }
        }
    }
}
?>
<div class="container" align="center">
    <form class="form-horizontal col-lg-12" action="#" method="post">
        <fieldset>
            <legend>Edycja profilu</legend>
            <div class="form-group col-lg-6">
                <p class="text-primary">Podaj nowy adres E-mail:</p>
                <div class="col-lg-4 col-lg-offset-4">
                    <input type="email" class="form-control" name="email" placeholder="E-mail">
                    <br>
                    <button type="submit" name="emailSubmit" class="btn btn-danger btn-sm">Zmień</button>
                </div>
            </div>
            <div class="form-group col-lg-6" align="center">
                <p class="text-primary">Podaj nowe hasło:</p>
                <div class="col-lg-4 col-lg-offset-4">
                    <input type="password" class="form-control" name="password1" placeholder="Hasło">
                    <br>
                    <input type="password" class="form-control" name="password2" placeholder="Powtórz hasło">
                    <br>
                    <button type="submit" name="passSubmit" class="btn btn-danger btn-sm">Zmień</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
include('templates/footer.php');
?>
