<?php
include('templates/header.php');
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_POST['email'], $_POST['password'], $_POST['password2'], $_POST['username'])) {
        $isOk = true;
        require_once '../src/connection.php';
        require_once '../src/User.php';
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        $user = new User();
        if (!$user->validateEmail($email)) {
            $isOk = false;
        }
        if (!$user->validatePassword($password,$password2)) {
            $isOk = false;
        }
        if (!$user->validateUsername($username)) {
            $isOk = false;
        }
        if ($isOk) {
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($password);

            $user->save($conn);
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
                    <input type="email" class="form-control" name="email" placeholder="Email">
                </div>
            </div>
            <div class="form-group" align="center">
                <label class="col-lg-2 control-label">Hasło</label>
                <div class="col-lg-3">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group" align="center">
                <label class="col-lg-2 control-label">Potwierdź hasło</label>
                <div class="col-lg-3">
                    <input type="password" class="form-control" name="password2" placeholder="Confirm password">
                </div>
            </div>
            <div class="form-group" align="center">
                <label class="col-lg-2 control-label">Nazwa użytkownika</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" name="username" placeholder="Username">
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
