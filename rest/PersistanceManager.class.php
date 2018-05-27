<?php
class PersistanceManager{
  private $pdo;

  public function __construct($params){
    $this->pdo = new PDO('mysql:host='.$params['host'].';dbname='.$params['scheme'], $params['username'], $params['password']);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  }
  public function add_user($user){

    $usernameCheck = $this->pdo->prepare("SELECT username FROM users WHERE username  = :name");
    $usernameCheck->bindParam(':name', $user['username']);
    $usernameCheck->execute();

    $emailCheck = $this->pdo->prepare("SELECT email FROM users WHERE email  = :email");
    $emailCheck->bindParam(':email', $user['email']);
    $emailCheck->execute();

    if($usernameCheck->rowCount() > 0)
      return $x["error"]["username"] = "username already exists";

    if($emailCheck->rowCount() > 0)
      return $x["error"]["email"] = "email already exists";
    
    if(strlen($user['pass'])<6)
      return $x["error"]["password"] = "password should be atleast 6 characters long";

    
    $query = "INSERT INTO users
            (username,
             email,
             password)
            VALUES (:username,
                    :email,
                    :pass)";
    $statement = $this->pdo->prepare($query);
    $statement->execute($user);
    $user['id'] = $this->pdo->lastInsertId();
    return $user;
  }

  //find if username matches password
  public function find_user($user){
    $usernameCheck = $this->pdo->prepare("SELECT username FROM users WHERE username  = :name AND password = :password");
    $usernameCheck->bindParam(':name', $user['username']);
    $usernameCheck->bindParam(':password', $user['pass']);
    $usernameCheck->execute();
    
    if($usernameCheck->rowCount() === 0){
      $x["valid"] = false;
      $x["error"] = "invalid username or password";
      return $x;
    }
    $x = $user;
    $x["valid"] = true;
    return $x;

  }
}
?>
