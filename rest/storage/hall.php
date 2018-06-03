<?php

class Hall
{
    private $config;
    private $database;

    public function __construct()
    {
        $this->config = include 'config.php';
        $this->database = new Database();
    }

    public function add_hall($hall)
    {
        $query = "INSERT INTO Halls
            (name,
             cinema_id)
            VALUES (:name,
                    :cinema_id)";
        $statement = $this->database->handler->prepare($query);
        $statement->bindParam(':name', $hall['name']);
        $statement->bindParam(':cinema_id', $hall['cinema_id']);
        $statement->execute();

        $hall['id'] = $this->database->handler->lastInsertId();
        return $hall;
    }
    public function get_all_halls($cinema_id)
    {
        $statement = $this->database->handler->prepare("SELECT * FROM Halls WHERE cinema_id  = :cinema_id");
        $statement->bindParam(':cinema_id', $cinema_id);
        $statement->execute();
        $x = $statement->fetchAll();
        return $x;
    }

    public function delete_hall($id){
        $stmt = $this->database->handler->prepare('DELETE FROM Halls WHERE id = :id');
        $stmt->execute(['id' => $id]);                
    }

    public function edit_hall($id, $name, $description){
        $stmt = $this->database->handler->prepare('UPDATE Hall SET name=:name WHERE id = :id');
        $stmt->execute(['id' => $id, 'name'=>$name]);
    }
}
