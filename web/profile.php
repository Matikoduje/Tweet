<?php
include('templates/header.php');
if (!isset ($_SESSION['user'])) {
    echo "<p>Nie masz uprawnień do oglądania tej strony</p>";
} else {
require_once '../src/Comment.php';
require_once '../src/Twitter.php';
if (isset($_POST['receiverId'], $_POST['messageText']) && 'POST' === $_SERVER['REQUEST_METHOD']) {
    $isOkMessage = true;
    $receiverId = $_POST['receiverId'];
    $messageText = $_POST['messageText'];
    $message = new Message();
    if (!$message->validateText($messageText)) {
        echo "<div class=\"alert alert-danger\">";
        echo "<strong>Wiadomość musi mieć odpowiednią długość !</strong>";
        echo "</div>";
        $isOkMessage = false;
    }

    if ($isOkMessage) {
        $message->setCreationDate();
        $message->setIsRead(0);
        $message->setText($messageText);
        $message->setSenderId($_SESSION['user']);
        $message->setReceiverId($receiverId);
        $message->save($conn);
    }
}
if (isset($_POST['tweetId'], $_POST['commentText']) && 'POST' === $_SERVER['REQUEST_METHOD']) {
    $isOkComm = true;
    $tweetId = $_POST['tweetId'];
    $commentText = $_POST['commentText'];
    $comment = new Comment();
    if (!$comment->validateText($commentText)) {
        echo "<div class=\"alert alert-danger\">";
        echo "<strong>Komentarz musi mieć odpowiednią długość !</strong>";
        echo "</div>";
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
?>
<div class="col-lg-4">
    <div class="twt-wrapper">
        <div class="panel panel-info">
            <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Skrzynka nadawcza
                        </span>
            </div>
            <div class="panel-body pre-scrollable" style="height: calc(50vh - 100px)">
                <ul class="media-list">
                    <?php
                    $mySendMessages = Message::loadAllMessagesSendByUserId($conn, $_SESSION['user']);
                    if ($mySendMessages->num_rows > 0) {
                        foreach ($mySendMessages as $row) {
                            echo "<li class='media'>";
                            echo "<div class='media-body'>";
                            echo "<form action='#' method='post'>";
                            echo "<span class=\"text-muted pull-right\">";
                            echo "<small class=\"text-muted\">" . $row['dat'] . "</small></span>";
                            if (0 == $row['is_read']) {
                                echo "<strong class='text-danger'>" . $row['username'] . "</strong>";
                            } else {
                                echo "<strong class=\"text-success\">" . $row['username'] . "</strong>";
                            }
                            echo "<p style='word-wrap: break-word'>";
                            echo "<button class='btn-link' style='text-align: left; text-decoration: none; color: black' type='submit' name='messageId1' value='" . $row['messageId'] . "'>";
                            if (strlen($row['text']) > 30) {
                                $partString = substr($row['text'],0,30);
                                echo  $partString . '(...)';
                            } else {
                                echo $row['text'];
                            }
                            echo "</button>";
                            echo "</p>";
                            echo "</form>";
                            echo "</div>";
                            echo "</li>";
                        }
                    } else {
                        echo "<p>Brak wysłanych wiadomości</p>";
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Skrzynka odbiorcza
                        </span>
            </div>
            <div class="panel-body pre-scrollable" style="height: calc(50vh - 100px)">
                <ul class="media-list">
                    <?php
                    $myReceiveMessages = Message::loadAllMessagesReceivedByUserId($conn, $_SESSION['user']);
                    if ($myReceiveMessages->num_rows > 0) {
                        foreach ($myReceiveMessages as $row) {
                            echo "<li class='media'>";
                            echo "<div class='media-body'>";
                            echo "<form action='#' method='post'>";
                            echo "<span class=\"text-muted pull-right\">";
                            echo "<small class=\"text-muted\">" . $row['dat'] . "</small></span>";
                            if (0 == $row['is_read']) {
                                echo "<strong class='text-danger'>" . $row['username'] . "</strong>";
                            } else {
                                echo "<strong class=\"text-success\">" . $row['username'] . "</strong>";
                            }
                            echo "<p style='word-wrap: break-word'>";
                            echo "<button class='btn-link' style='text-align: left; text-decoration: none; color: black' type='submit' name='messageId2' value='" . $row['messageId'] . "'>";
                            if (strlen($row['text']) > 30) {
                                $partString = substr($row['text'],0,30);
                                echo  $partString . '(...)';
                            } else {
                                echo $row['text'];
                            }
                            echo "</button>";
                            echo "</p>";
                            echo "</form>";
                            echo "</div>";
                            echo "</li>";
                        }
                    } else {
                        echo "<p>Brak odebranych wiadomości</p>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="twt-wrapper">
        <div class="panel panel-info">
            <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Moje Tweety
                        </span>
            </div>
            <div class="panel-body pre-scrollable" style="max-height: calc(100vh - 150px)">
                <ul class="media-list">
                    <?php
                    $myTweets = Twitter::loadAllTweetsByUserId($conn, $_SESSION['user']);
                    if ($myTweets->num_rows > 0) {
                        foreach ($myTweets as $row) {
                            echo "<li class='media'>";
                            echo "<div class='media-body'>";
                            echo "<form action='#' method='post'>";
                            echo "<span class=\"text-muted pull-right\">";
                            echo "<small class=\"text-muted\">" . $row['dat'] . "</small></span>";
                            echo "<strong class=\"text-success\">" . $row['tag'] . "</strong>";
                            echo "<p style='word-wrap: break-word'>";
                            echo "<button class='btn-link' style='text-align: left; text-decoration: none; color: black' type='submit' name='tweetId' value='" . $row['tweetId'] . "'>";
                            echo $row['text'];
                            echo "</button>";
                            echo "</p>";
                            echo "</form>";
                            $countComments = Comment::countHowManyCommentsByTweetId($conn, $row['tweetId']);
                            echo "<small class='text-info'><em>Komentarzy: " . $countComments . "</em></small>";
                            echo "</div>";
                            echo "</li>";
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="twt-wrapper">
        <div class="panel panel-info">
            <div class="panel-heading">
                        <span style="text-align: center; color: #004b63;">
                            Informacje:
                        </span>
            </div>
            <div class="panel-body pre-scrollable" style="max-height: calc(100vh - 150px)">
                <?php
                if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['tweetId'])) {
                    $tweetId = $_POST['tweetId'];
                    $tweetDetails = Twitter::loadTweetById($conn, $tweetId);
                    $commentsDetails = Comment::loadAllCommentsByTweetId($conn, $tweetId);
                    if ($tweetDetails->num_rows == 1) {
                        foreach ($tweetDetails as $row) {
                            echo "<div class='media-body'>";
                            echo "<p><form method='post' action='#'><strong class=\"text-primary\">Autor: </strong><button class='btn-link' style='text-decoration: none' type='submit' name='userTweets' value='" . $row['userId'] . "'>" . $row['username'] . "</button></form></p>";
                            echo "<p><strong class=\"text-primary\">Tag: </strong><strong class=\"text-success\">" . $row['tag'] . "</strong></p>";
                            echo "<p><strong class=\"text-primary\">Treść:</strong></p>";
                            echo "<p class='text-success'>" . $row['text'] . "</p>";
                            echo "<p><strong class=\"text-primary\">Data utworzenia: </strong><strong class=\"text-success\">" . $row['dat'] . "</strong></p>";
                            echo "<form method='post' action='#'>";
                            echo "<textarea class=\"form-control\" placeholder=\"Od 5 do 60 znaków...\" rows=\"3\" maxlength=\"60\" name='commentText'></textarea>";
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
                } else if (('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['messageId1'])) || ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['messageId2']))) {
                    if (isset($_POST['messageId1'])) {
                        $messageId = $_POST['messageId1'];
                        $ok = true;
                    } else {
                        $messageId = $_POST['messageId2'];
                        $ok = false;
                    }
                    $messageLoaded = Message::loadMessageByMessageId($conn,$messageId,$ok);
                    if (1 == $messageLoaded->num_rows) {
                        foreach ($messageLoaded as $info) {
                            echo "<li class='media-list'>";
                            echo "<div class='media-body'>";
                            if ($ok) {
                                echo "<p><strong class='text-primary'>Odbiorca: </strong><strong class='text-success'>". $info['username'] ."</strong></p>";
                            } else {
                                echo "<p><strong class='text-primary'>Nadawca: </strong><strong class='text-success'>". $info['username'] ."</strong></p>";
                            }
                            echo "<p><strong class=\"text-primary\">Data utworzenia: </strong><strong class=\"text-success\">" . $info['creation_date'] . "</strong></p>";
                            echo "<p><strong class=\"text-primary\">Treść wiadomości:</strong></p>";
                            echo "<p class='text-success'>" . $info['text'] . "</p>";
                            if (false == $ok) {
                                echo "<form method='post' action='#'>";
                                echo "<textarea class=\"form-control\" placeholder=\"Od 10 do 130 znaków...\" rows=\"3\" maxlength=\"130\" name='messageText'></textarea>";
                                echo "<br><button class='btn btn-primary btn-xs pull-right' type='submit' name='receiverId' value='" . $info['sender_id'] . "'>Odpowiedz</button>";
                                echo "</form>";
                            }
                            echo "</div>";
                            echo "</li>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    }
    include('templates/footer.php');
    ?>
