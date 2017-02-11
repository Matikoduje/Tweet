<?php
include('templates/header.php');
if (!isset($_SESSION['user'])) {
    echo "<h4>Witam na stronie. Aby móc korzystać z serwisu należy się zalogować</h4>";
} else {
    require_once '../src/connection.php';
    require_once '../src/Twitter.php';
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        if (isset($_POST['tag'], $_POST['tweetText'])) {
            $isOk = true;
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
                $obj = unserialize($_SESSION['user']);
                $tweet->setTag($tag);
                $tweet->setText($tweetText);
                $tweet->setCreationDate();
                $tweet->setUserID($obj->getId());

                $tweet->save($conn);
                unset($obj);
            }
        }
    }
    ?>
    <div class="col-lg-6 col-lg-offset-3">
        <div class="twt-wrapper">
            <div class="panel panel-info">
                <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Napisz Tweeta
                        </span>
                </div>
                <div class="panel-body">
                    <form action="#" method="post">
                    <textarea class="form-control" placeholder="Maksymalnie 255 znaków..." rows="5" maxlength="255"
                              name="tweetText"></textarea>
                        <br>
                        <div>
                            <select class="form-control pull-left" style="width: 120px" name="tag">
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
                            <button type="submit" class="btn btn-primary btn-sm pull-right">Tweet</button>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <hr>
                    <ul class="media-list">
                        <?php
                        $allTweets = Twitter::loadAllTweets($conn);
                        if ($allTweets->num_rows > 0) {
                            foreach ($allTweets as $row) {
                                echo "<li class='media'>";
                                echo "<div class='media-body'>";
                                echo "<span class=\"text-muted pull-right\">";
                                echo "<small class=\"text-muted\">" . $row['tag'] . "</small></span>";
                                echo "<strong class=\"text-success\">" . $row['username'] . "</strong>";
                                echo "<p style='word-wrap: break-word'>";
                                echo $row['text'];
                                echo "</p>";
                                echo "</div>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>
                    <span class="text-danger">237K users active</span>
                </div>
            </div>
        </div>
    </div>
    <?php
}
include('templates/footer.php');
?>

