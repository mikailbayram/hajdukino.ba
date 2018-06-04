<?php

class Projection
{
    private $config;
    private $database;

    public function __construct()
    {
        $this->config = include 'config.php';
        $this->database = new Database();
    }
    public function add_projection($projection)
    {
        $query = "INSERT INTO Projections
            (movie_name,
             hall_name,
             cinema_id)
            VALUES (:movie_name,
                    :hall_name,
                    :cinema_id)";
        $statement = $this->database->handler->prepare($query);
        $statement->bindParam(':movie_name', $projection['movie_name']);
        $statement->bindParam(':hall_name', $projection['hall_name']);
        $statement->bindParam(':cinema_id', $projection['cinema_id']);
        $statement->execute();
        $projection['id'] = $this->database->handler->lastInsertId();
        return $projection;
    }

    public function get_all_projections($cinema_id)
    {
        $statement = $this->database->handler->prepare("SELECT * FROM Projections WHERE cinema_id  = :cinema_id");
        $statement->bindParam(':cinema_id', $cinema_id);
        $statement->execute();
        $x = $statement->fetchAll();
        return $x;
    }
    public function delete_projection($id){
        $stmt = $this->database->handler->prepare('DELETE FROM Projections WHERE id = :id');
        $stmt->execute(['id' => $id]);                
    }

}
