<?php
use \Firebase\JWT\JWT;

Flight::route('POST /movies/create', function () {
    $request = Flight::request();
    $movieDb = new Movie();
    $config = include('config.php');                
    $key = $config["jwtKey"];
    $user = JWT::decode($request->data->token, $key, ["HS256"]);
    $movie = [
        'name' => $request->data->name,
        'description' => $request->data->description,
        'cinema_id' => $user->data->cinema_id,
    ];

    $res = $movieDb->add_movie($movie);
    Flight::json($res);
});

Flight::route('GET /movies/all/@id', function ($id) {
    $movieDb = new Movie();
    $res = $movieDb->get_all_movies($id);
    Flight::json($res);
});

Flight::route('PUT /movie/@id', function($id){
    $request = Flight::request();
    $movieDb = new Movie();
    $movieDb->edit_movie($id, $request->data->name,$request->data->description);
});

FLIGHT::route('DELETE /movie/@id', function($id){
    $movieDb = new Movie();
    $movieDb->delete_movie($id);
});

?>
