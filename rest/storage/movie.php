<?php

class Movie
{
    private $config;
    private $database;

    public function __construct()
    {
        $this->config = include 'config.php';
        $this->database = new Database();
    }

    public function add_movie($movie)
    {
        $query = "INSERT INTO Movies
            (name,
             description,
             cinema_id)
            VALUES (:name,
                    :description,
                    :cinema_id)";
        $statement = $this->database->handler->prepare($query);
        $statement->bindParam(':name', $movie['name']);
        $statement->bindParam(':description', $movie['description']);
        $statement->bindParam(':cinema_id', $movie['cinema_id']);
        $statement->execute();

        $movie['id'] = $this->database->handler->lastInsertId();
        return $movie;
    }
    public function get_all_movies($cinema_id)
    {
        $statement = $this->database->handler->prepare("SELECT * FROM Movies WHERE cinema_id  = :cinema_id");
        $statement->bindParam(':cinema_id', $cinema_id);
        $statement->execute();
        $x = $statement->fetchAll();
        return $x;
    }

    public function delete_movie($id){
        $stmt = $this->database->handler->prepare('DELETE FROM Movies WHERE id = :id');
        $stmt->execute(['id' => $id]);                
    }

    public function edit_movie($id, $name, $description){
        $stmt = $this->database->handler->prepare('UPDATE Movies SET name=:name, description=:description WHERE id = :id');
        $stmt->execute(['id' => $id, 'name'=>$name, 'description'=>$description]);
    }
}
