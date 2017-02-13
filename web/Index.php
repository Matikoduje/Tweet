<?php
include('templates/header.php');
if (!isset($_SESSION['user'])) {
    echo "<p class='text-primary' style='text-align: center; font-size: large'>Witam na stronie. Aby móc korzystać z serwisu należy się zalogować.</p>";
} else {
    require_once '../src/Comment.php';
    require_once '../src/Twitter.php';
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        if (isset($_POST['tweetId'], $_POST['commentText'])) {
            $isOkComm = true;
            $tweetId = $_POST['tweetId'];
            $commentText = $_POST['commentText'];
            $comment = new Comment();
            if (!$comment->validateText($commentText)) {
                $isOkComm = false;
            }

            if ($isOkComm) {
                $comment->setCreationDate();
                $comment->setText($commentText);
                $comment->setUserID($_SESSION['user']);
                $comment->setTweetId($tweetId);
                $comment->save($conn);
            }

        }
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
                $tweet->setTag($tag);
                $tweet->setText($tweetText);
                $tweet->setCreationDate();
                $tweet->setUserID($_SESSION['user']);

                $tweet->save($conn);
            }
        }
    }
    ?>
    <!-- Modal -->
    <div class="col-lg-4">
        <div class="twt-wrapper">
            <div class="panel panel-info">
                <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Tweetuj
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
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Wyszukiwanie
                        </span>
                </div>
                <div class="panel-body">
                    <form action="#" method="post">
                        <div class="form-group">
                            <div class="col-lg-6">
                                <input type="number" min='0' class="form-control" name="tweetId" placeholder="Id Twiita">
                            </div>
                            <div class="form-inline">
                                <button type="submit" class="form-control btn btn-primary btn-xs">Szukaj</button>
                            </div>
                        </div>
                    </form>
                    <form action="#" method="post">
                        <div class="form-group">
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="findUser" placeholder="Użytkownik">
                            </div>
                            <div class="form-inline">
                                <button type="submit" class="form-control btn btn-primary btn-xs">Szukaj</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="twt-wrapper">
            <div class="panel panel-info">
                <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Lista Tweetów
                        </span>
                </div>
                <div class="panel-body pre-scrollable" style="max-height: calc(100vh - 150px)">
                    <ul class="media-list">
                        <?php
                        $allTweets = Twitter::loadAllTweets($conn);
                        if ($allTweets->num_rows > 0) {
                            foreach ($allTweets as $row) {
                                echo "<li class='media'>";
                                echo "<div class='media-body'>";
                                echo "<form action='#' method='post'>";
                                echo "<span class=\"text-muted pull-right\">";
                                echo "<small class=\"text-muted\">" . $row['tag'] . "</small></span>";
                                echo "<button class='btn-link' style='text-decoration: none' type='submit' name='userTweets' value='" . $row['userId'] . "'>";
                                echo $row['username'];
                                echo "</button>";
                                echo "<p style='word-wrap: break-word'>";
                                echo "<button class='btn-link' style='text-align: left; text-decoration: none; color: black' type='submit' name='tweetId' value='" . $row['tweetId'] . "'>";
                                echo $row['text'];
                                echo "</button>";
                                echo "</p>";
                                echo "</form>";
                                $countComments = Comment::countHowManyCommentsByTweetId($conn, $row['tweetId']);
                                if ($countComments->num_rows == 1) {
                                    foreach ($countComments as $rows) {
                                        echo "<small class='text-info'><em>Komentarzy: " . $rows['count'] . "</em></small>";
                                    }
                                }
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
    if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['tweetId'])) {
        ?>
        <div class="col-lg-4">
            <div class="twt-wrapper">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Informacje o Tweecie
                        </span>
                    </div>
                    <div class="panel-body pre-scrollable" style="max-height: calc(100vh - 150px)">
                        <?php
                        $tweetId = $_POST['tweetId'];
                        $tweetDetails = Twitter::loadTweetById($conn, $tweetId);
                        $commentsDetails = Comment::loadAllCommentsByTweetId($conn, $tweetId);
                        if ($tweetDetails->num_rows == 1) {
                            foreach ($tweetDetails as $row) {
                                echo "<div class='media-body'>";
                                echo "<p><strong class=\"text-primary\">Autor: </strong><strong class=\"text-success\">" . $row['username'] . "</strong></p>";
                                echo "<p><strong class=\"text-primary\">Tag: </strong><strong class=\"text-success\">" . $row['tag'] . "</strong></p>";
                                echo "<p><strong class=\"text-primary\">Treść:</strong></p>";
                                echo "<p class='text-success'>" . $row['text'] . "</p>";
                                echo "<p><strong class=\"text-primary\">Data utworzenia: </strong><strong class=\"text-success\">" . $row['dat'] . "</strong></p>";
                                echo "<form method='post' action='#'>";
                                echo "<textarea class=\"form-control\" placeholder=\"Maksymalnie 130 znaków...\" rows=\"3\" maxlength=\"130\"
                              name='commentText'></textarea>";
                                echo "<br><button class='btn btn-primary btn-xs pull-right' type='submit' name='tweetId' value='" . $row['tweetId'] . "'>Skomentuj</button></form>";
                                echo "<br><br>";
                                if ($commentsDetails->num_rows > 0) {
                                    foreach ($commentsDetails as $rows) {
                                        echo "<li class='media-list'>";
                                        echo "<div class='media-body'>";
                                        echo "<span class=\"text-muted pull-right\">";
                                        echo "<small class=\"text-muted\">" . $rows['dat'] . "</small></span>";
                                        echo "<strong class=\"text-success\">" . $rows['username'] . "</strong>";
                                        echo "<p style='word-wrap: break-word'>";
                                        echo $rows['text'];
                                        echo "</p>";
                                        echo "</div>";
                                        echo "</li>";
                                    }
                                } else {
                                    echo "<p class='text-primary'>Brak komentarzy</p>";
                                }
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['userTweets'])) || ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['findUser']))) {
        ?>
        <div class="col-lg-4">
            <div class="twt-wrapper">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Informacje o Użytkowniku
                        </span>
                    </div>
                    <div class="panel-body pre-scrollable" style="max-height: calc(100vh - 150px)">
                        <?php
                        if (isset($_POST['userTweets'])) {
                            $userTweets = $_POST['userTweets'];
                        } else {
                            $findUser = $_POST['findUser'];
                            $userIdByUsername = User::findUserIdByUsername($conn, $findUser);
                            if ($userIdByUsername->num_rows == 1) {
                                foreach ($userIdByUsername as $row) {
                                    $userTweets = $row['id'];
                                }
                            }
                        }
                        $tweetList = Twitter::loadAllTweetsByUserId($conn, $userTweets);
                        $isNameFirst = true;
                        if ($tweetList->num_rows >= 1) {
                            foreach ($tweetList as $row) {
                                if (true == $isNameFirst) {
                                    echo "<p><strong class=\"text-primary\">Użytkownik: </strong><strong class=\"text-success\">" . $row['username'] . "</strong></p>";
                                    echo "<div class='clearfix'></div>";
                                    $isNameFirst = false;
                                }
                                echo "<li class='media-list'>";
                                echo "<div class='media-body'>";
                                echo "<span class=\"text-muted pull-right\">";
                                echo "<small class=\"text-muted\">" . $row['dat'] . "</small></span>";
                                echo "<strong class=\"text-success\">" . $row['tag'] . "</strong>";
                                echo "<p style='word-wrap: break-word'>";
                                echo $row['text'];
                                echo "</p>";
                                echo "</div>";
                                echo "</li>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
include('templates/footer.php');
?>
