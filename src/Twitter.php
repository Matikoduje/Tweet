<?php

class Twitter
{
    private $id;
    private $userID;
    private $text;
    private $tag;
    private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->userID = -1;
        $this->creationDate = "";
        $this->tag = "";
        $this->text = "";
    }

    public function setCreationDate()
    {
        date_default_timezone_set('Europe/Warsaw');
        $creationDate = date('Y-m-d G:i:s');
        $this->creationDate = $creationDate;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    public function validateTag($tag)
    {
        if ('Polityka' == $tag || 'Sport' == $tag || 'Zdrowie' == $tag || 'Ekologia' == $tag || 'Technika' == $tag || 'Rozrywka' == $tag || 'Plotki' == $tag || 'Zwierzęta' == $tag || 'Inne' == $tag ) {
            return true;
        }
        return false;
    }

    public function validateText($text)
    {
        if (strlen($text) > 255 ) {
            return false;
        }
        return true;
    }

    public function save(mysqli $conn)
    {
        if (-1 === $this->id) {
            $conn->query("SET NAMES 'utf8'");
            $sql = sprintf("INSERT INTO `tweet` (`user_id`, `text`, `tag`, `creation_date`) VALUES ('%d', '%s', '%s', '%s')", $this->userID, $this->text, $this->tag, $this->creationDate);
            $result = $conn->query($sql);

            if ($result) {
                $this->id = $conn->insert_id;
            } else {
                die('Error: tweet not saved' . $conn->error);
            }
        }
    }
    static public function loadAllTweets(mysqli $conn)
    {
        $sql = "SELECT user.username AS `username`, `tag`, `text` FROM tweet JOIN user ON tweet.user_id=user.id";
        $conn->query("SET NAMES 'utf8'");
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        return $result;
    }
}