<?php
include('templates/header.php');
if (isset($_SESSION['user'])) {
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        if (isset($_POST['tag'], $_POST['tweetText'])) {
            $isOk = true;
            require_once '../src/connection.php';
            require_once '../src/Twitter.php';
            $tag = $_POST['tag'];
            $tweetText = $_POST['tweetText'];
            $tweet = new Twitter();

            if (!$tweet->validateTag($tag)) {
                $isOk = false;
            }
            if (!$tweet->validateText($tweetText)) {
                $isOk = false;
            }

            if ($isOk) {
                $tweet->setTag($tag);
                $tweet->setText($tweetText);
                $tweet->setCreationDate();
                $tweet->setUserID($_SESSION['user']->getId());
            }
        }
    }
    ?>
    <div class="container" align="center">
        <form class="form-horizontal col-lg-12" action="#" method="post">
            <fieldset>
                <legend>Stwórz Twitta</legend>
                <div class="form-group" align="center">
                    <label class="col-lg-2 control-label">Tag:</label>
                    <div class="col-lg-3">
                        <select class="form-control" name="tag">
                            <option>Polityka</option>
                            <option>Sport</option>
                            <option>Zdrowie</option>
                            <option>Ekologia</option>
                            <option>Technika</option>
                            <option>Rozrywka</option>
                            <option>Plotki</option>
                            <option>Zwierzęta</option>
                            <option>Inne</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" align="center">
                    <label class="col-lg-2 control-label">Treść</label>
                    <div class="col-lg-3">
                        <textarea class="form-control" rows="5" maxlength="255" name="tweetText"></textarea>
                        <span class="help-block">Proszę zapisać Twiita o maksymalnej wielkości 255 znaków.</span>
                    </div>
                </div>
                <div class="form-group" align="center">
                    <div class="col-lg-3 col-lg-offset-2">
                        <button type="submit" class="btn btn-primary">Dodaj Twiita</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <?php
}
include('templates/footer.php');
?>
