<?php
class Database{
  private $pdo;
  public $handler;
  
  public function __construct(){
    $config = include('config.php');                
        $host = $config['host'];
        $db   = $config['database'];
        $user = $config['username'];
        $pass = $config['password'];
        $dsn = "mysql:host=$host;dbname=$db;";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->handler = new PDO($dsn, $user, $pass, $opt);   
  }
}
?>
