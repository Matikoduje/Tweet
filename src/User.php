<?php

class User
{
    private $id;
    private $username;
    private $email;
    private $password;

    public function __construct()
    {
        $this->id = -1;
        $this->email = '';
        $this->username = '';
        $this->password = '';
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function save(mysqli $conn)
    {
        if (-1 === $this->id) {
            $conn->query("SET NAMES 'utf8'");
            $sql = sprintf("INSERT INTO `user` (`email`, `username`, `password`) VALUES ('%s', '%s', '%s')", $this->email, $this->username, $this->password);
            $result = $conn->query($sql); /// sprintf wstawia w miejsca %s stringi które są podane jako zmienne.

            if ($result) {
                $this->id = $conn->insert_id; // w insert id jest id ostatniego wiersza wstawionego do bazy danych
            } else {
                die('Error: used not saved' . $conn->error);
            }
        }
    }

    public function setHash($hash)
    {
        $this->password = $hash;
    }

    static public function loadUserByUsername(mysqli $conn, $username)
    {
        $conn->query("SET NAMES 'utf8'");
        $username = $conn->real_escape_string($username);
        $sql = "SELECT * FROM `user` WHERE `username` = '$username'";
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }

        if (1 === $result->num_rows) {
            $userArray = $result->fetch_assoc();
            $user = new User();
            $user->setId($userArray['id']);
            $user->setEmail($userArray['email']);
            $user->setUsername($userArray['username']);
            $user->setHash($userArray['password']);

            return $user;
        }

        return false;
    }

    public function validateEmail($email)
    {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public function validatePassword($pass1, $pass2)
    {
        if ($pass1 != $pass2) {
            return false;
        }
        return true;
    }

    public function validateUsername($username)
    {
        if (strlen($username) > 15 || strlen($username) < 3) {
            return false;
        }
        return true;
    }

    public function getUsername()
    {
        return $this->username;
    }

    static public function findUserIdByUsername($conn, $username)
    {
        $conn->query("SET NAMES 'utf8'");
        $sql = "SELECT id FROM `user` WHERE username='" . $username . "'";
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        return $result;
    }

    static public function findUserNameByUserId($conn, $id)
    {
        $conn->query("SET NAMES 'utf8'");
        $sql = "SELECT username FROM `user` WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        foreach ($result as $row) {
            return $row['username'];
        }
    }

    public function compareUsername($conn, $username) {
        $conn->query("SET NAMES 'utf8'");
        $sql = "SELECT COUNT(id) AS id FROM `user` WHERE username='" . $username . "'";
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        foreach ($result as $row) {
            return $row['id'];
        }
    }

    public function compareEmail($conn, $email) {
        $conn->query("SET NAMES 'utf8'");
        $sql = "SELECT COUNT(id) AS id FROM `user` WHERE email='" . $email . "'";
        $result = $conn->query($sql);

        if (!$result) {
            die('Querry error: ' . $conn->error);
        }
        foreach ($result as $row) {
            return $row['id'];
        }
    }
}