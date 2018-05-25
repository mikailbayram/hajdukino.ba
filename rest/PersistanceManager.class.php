<?php
class PersistanceManager{
  private $pdo;

  public function __construct($params){
    $this->pdo = new PDO('mysql:host='.$params['host'].';dbname='.$params['scheme'], $params['username'], $params['password']);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  }
  public function add_user($user){
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
