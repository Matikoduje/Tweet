<?php
class Message
{
    private $id;
    private $senderId;
    private $receiverId;
    private $text;
    private $creationDate;
    private $isRead;

    public function __construct()
    {
        $this->id = -1;
        $this->senderId = -1;
        $this->receiverId = -1;
        $this->creationDate = "";
        $this->text = "";
        $this->isRead = -1;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setCreationDate()
    {
        date_default_timezone_set('Europe/Warsaw');
        $creationDate = date('Y-m-d G:i:s');
        $this->creationDate = $creationDate;
    }

    public function setReceiverId($receiverId)
    {
        $this->receiverId = $receiverId;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }

    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    }

    public function save(mysqli $conn)
    {
        if (-1 === $this->id) {
            $this->text = $conn->real_escape_string($this->text);
            $conn->query("SET NAMES 'utf8'");
            $sql = sprintf("INSERT INTO `message` (`sender_id`, `text`, `receiver_id`, `creation_date`, `is_read`) VALUES ('%d', '%s', '%d', '%s', '%d')", $this->senderId, $this->text, $this->receiverId, $this->creationDate, $this->isRead);
            $result = $conn->query($sql);

            if ($result) {
                $this->id = $conn->insert_id;
            } else {
                die('Error: tweet not saved' . $conn->error);
            }
        }
    }

    public function validateText($text)
    {
        if (strlen($text) > 140 || strlen($text) < 10) {
            return false;
        }
        return true;
    }
}