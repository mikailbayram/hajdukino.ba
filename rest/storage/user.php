<?php

class User
{
    private $config;
    private $database;

    public function __construct()
    {
        $this->config = include 'config.php';
        $this->database = new Database();
    }

    public function add_user($user)
    {

        $usernameCheck = $this->database->handler->prepare("SELECT username FROM users WHERE username  = :name");
        $usernameCheck->bindParam(':name', $user['username']);
        $usernameCheck->execute();

        $emailCheck = $this->database->handler->prepare("SELECT email FROM users WHERE email  = :email");
        $emailCheck->bindParam(':email', $user['email']);
        $emailCheck->execute();

        if ($usernameCheck->rowCount() > 0) {
            return $x["error"]["username"] = "username already exists";
        }

        if ($emailCheck->rowCount() > 0) {
            return $x["error"]["email"] = "email already exists";
        }

        if (strlen($user['pass']) < 6) {
            return $x["error"]["password"] = "password should be atleast 6 characters long";
        }

        $cinemaQuery = "INSERT INTO Cinemas
    (name)
    VALUES (:cinema_name)";
        $statement = $this->database->handler->prepare($cinemaQuery);
        $statement->bindParam(':cinema_name', $user['cinema_name']);
        $statement->execute();
        $user['cinema_id'] = $this->database->handler->lastInsertId();

        $query = "INSERT INTO users
            (username,
             email,
             password,
             cinema_id)
            VALUES (:username,
                    :email,
                    :pass,
                    :cinema_id)";
        $statement = $this->database->handler->prepare($query);
        $statement->bindParam(':username', $user['username']);
        $statement->bindParam(':email', $user['email']);
        $statement->bindParam(':pass', $user['pass']);
        $statement->bindParam(':cinema_id', $user['cinema_id']);
        $statement->execute();

        $user['id'] = $this->database->handler->lastInsertId();
        return $user;
    }

    //find if username matches password
    public function find_user($user)
    {
        $usernameCheck = $this->database->handler->prepare("SELECT * FROM users WHERE username  = :name AND password = :password");
        $usernameCheck->bindParam(':name', $user['username']);
        $usernameCheck->bindParam(':password', $user['pass']);
        $usernameCheck->execute();

        if ($usernameCheck->rowCount() === 0) {
            $x["valid"] = false;
            $x["error"] = "invalid username or password";
            return $x;
        }

        $x = $usernameCheck->fetch();
        $x["valid"] = true;
        return $x;

    }
}
?>