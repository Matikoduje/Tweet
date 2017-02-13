<?php
class Comment
{
    private $id;
    private $userID;
    private $tweetId;
    private $text;
    private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->userID = -1;
        $this->tweetId = -1;
        $this->creationDate = "";
        $this->text = "";
    }

    public function setCreationDate()
    {
        date_default_timezone_set('Europe/Warsaw');
        $creationDate = date('Y-m-d G:i:s');
        $this->creationDate = $creationDate;
    }

    public function setTweetId($tweetId)
    {
        $this->tweetId = $tweetId;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    public function validateText($text)
    {
        if (strlen($text) > 130 ) {
            return false;
        }
        return true;
    }

    public function save(mysqli $conn)
    {
        if (-1 === $this->id) {
            $conn->query("SET NAMES 'utf8'");
            $sql = sprintf("INSERT INTO `comment` (`user_id`, `text`, `tweet_id`, `creation_date`) VALUES ('%d', '%s', '%d', '%s')", $this->userID, $this->text, $this->tweetId, $this->creationDate);
            $result = $conn->query($sql);

            if ($result) {
                $this->id = $conn->insert_id;
            } else {
                die('Error: tweet not saved' . $conn->error);
            }
        }
    }

    static public function loadAllCommentsByTweetId(mysqli $conn, $tweetId)
    {
        $sql = "SELECT user.username AS username, `text`, creation_date AS dat, user.id FROM comment JOIN user ON user_id=user.id WHERE tweet_id=" . $tweetId . " ORDER BY dat DESC";
        $conn->query("SET NAMES 'utf8'");
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        return $result;
    }

    static public function countHowManyCommentsByTweetId(mysqli $conn, $tweetId)
    {
        $sql = "SELECT COUNT(tweet_id) AS `count` FROM comment WHERE tweet_id=" . $tweetId;
        $conn->query("SET NAMES 'utf8'");
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        return $result;
    }
}