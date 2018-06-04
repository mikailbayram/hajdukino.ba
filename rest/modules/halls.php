<?php
use \Firebase\JWT\JWT;

Flight::route('POST /halls/create', function () {
    $request = Flight::request();
    $hallDb = new Hall();
    $config = include('config.php');                
    $key = $config["jwtKey"];
    $user = JWT::decode($request->data->token, $key, ["HS256"]);
    $movie = [
        'name' => $request->data->name,
        'cinema_id' => $user->data->cinema_id,
    ];

    $res = $hallDb->add_hall($movie);
    Flight::json($res);
});

Flight::route('GET /halls/all/@id', function ($id) {
    $hallDb = new Hall();
    $res = $hallDb->get_all_halls($id);
    Flight::json($res);
});

Flight::route('PUT /hall/@id', function($id){
    $request = Flight::request();
    $movieDb = new Hall();
    $movieDb->edit_hall($id, $request->data->name,$request->data->description);
});

FLIGHT::route('DELETE /hall/@id', function($id){
    $hallDb = new Hall();
    $hallDb->delete_hall($id);
});

?>
