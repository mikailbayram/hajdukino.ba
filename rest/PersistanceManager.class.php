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

    
    $cinemaQuery = "INSERT INTO Cinemas
    (name)
    VALUES (:cinema_name)";  
    $statement = $this->pdo->prepare($cinemaQuery);
    $statement->bindParam(':cinema_name', $user['cinema_name']);
    $statement->execute();
    $user['cinema_id'] = $this->pdo->lastInsertId();

    $query = "INSERT INTO users
            (username,
             email,
             password,
             cinema_id)
            VALUES (:username,
                    :email,
                    :pass,
                    :cinema_id)";           
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':username', $user['username']);
    $statement->bindParam(':email', $user['email']);
    $statement->bindParam(':pass', $user['pass']);
    $statement->bindParam(':cinema_id', $user['cinema_id']);
    $statement->execute();

    $user['id'] = $this->pdo->lastInsertId();
    return $user;
  }

  //find if username matches password
  public function find_user($user){
    $usernameCheck = $this->pdo->prepare("SELECT * FROM users WHERE username  = :name AND password = :password");
    $usernameCheck->bindParam(':name', $user['username']);
    $usernameCheck->bindParam(':password', $user['pass']);
    $usernameCheck->execute();
    
    if($usernameCheck->rowCount() === 0){
      $x["valid"] = false;
      $x["error"] = "invalid username or password";
      return $x;
    }
  
    $x=$usernameCheck->fetch(PDO::FETCH_ASSOC);
    $x["valid"]=true;
    return $x;

  }
  public function add_movie($movie){
    $query = "INSERT INTO Movies
            (name,
             description,
             cinema_id)
            VALUES (:name,
                    :description,
                    :cinema_id)";           
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':name', $movie['name']);
    $statement->bindParam(':description', $movie['description']);
    $statement->bindParam(':cinema_id', $movie['cinema_id']);
    $statement->execute();

    $movie['id'] = $this->pdo->lastInsertId();
    return $movie;  
  }
  public function get_all_movies($cinema_id){
    $statement = $this->pdo->prepare("SELECT * FROM Movies WHERE cinema_id  = :cinema_id");
    $statement->bindParam(':cinema_id', $cinema_id);
    $statement->execute();
    $x=$statement->fetchAll(PDO::FETCH_ASSOC);
    return $x;
  }
}
?>
