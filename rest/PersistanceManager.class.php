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
      $x["error"]["username"] = "username already exists";

    if($emailCheck->rowCount() > 0)
      $x["error"]["email"] = "email already exists";
    
    if(strlen($user['pass'])<6)
      $x["error"]["password"] = "password should be atleast 6 characters long";

    if($x["error"])
      return $x;
    
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
}
?>
