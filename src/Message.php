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

    public static function loadAllMessagesSendByUserId(mysqli $conn, $userId) {
        $sql = "SELECT user.username AS `username`, receiver_id AS receiver, `text`, creation_date AS dat, message.id AS messageId, is_read FROM message JOIN user ON message.receiver_id=user.id WHERE sender_id=" . $userId . " ORDER BY dat DESC";
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        return $result;
    }

    public static function loadAllMessagesReceivedByUserId(mysqli $conn, $userId) {
        $sql = "SELECT user.username AS `username`, sender_id AS sender, `text`, creation_date AS dat, message.id AS messageId, is_read FROM message JOIN user ON message.sender_id=user.id WHERE receiver_id=" . $userId . " ORDER BY dat DESC";
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        return $result;
    }

    public static function countNewMessages(mysqli $conn, $userId) {
        $sql = "SELECT COUNT(id) AS `count` FROM message WHERE is_read=0 AND receiver_id=$userId";
        $result = $conn->query($sql);
        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        foreach ($result as $row) {
            if ($row['count'] > 0) {
                return $row['count'];
            } else {
                return false;
            }
        }
    }

    public static function loadMessageByMessageId(mysqli $conn, $messageId, $ok) {
        if (true == $ok) {
            $sql = "SELECT user.username AS `username`, receiver_id, `text`, creation_date, is_read FROM message JOIN user ON message.sender_id=user.id WHERE message.id=$messageId";
        } else {
            $sql = "UPDATE message SET is_read=1 WHERE id=$messageId";
            $result = $conn->query($sql);
            if (!$result) {
                die('Querry error: ' . $conn->error);
            }
            $sql = "SELECT user.username AS `username`, sender_id, `text`, creation_date, is_read FROM message JOIN user ON message.receiver_id=user.id WHERE message.id=$messageId";
        }

        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        return $result;
    }
}