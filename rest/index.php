<?php
require_once '../vendor/autoload.php';
require_once 'PersistanceManager.class.php';
require_once 'Config.class.php';

use \Firebase\JWT\JWT;

Flight::register('pm', 'PersistanceManager', [Config::DB]);

Flight::route('POST /user/register', function () {
    $request = Flight::request();
    $user = [
        'username' => $request->data->username,
        'email' => $request->data->email,
        'pass' => $request->data->pass,
        'cinema_name' => $request->data->cinema_name,
    ];
    $added_user = Flight::pm()->add_user($user);
    Flight::json($added_user);
});

Flight::route('POST /user/login', function () {
    $request = Flight::request();
    $user = [
        'username' => $request->data->username,
        'pass' => $request->data->pass,
    ];

    $res = Flight::pm()->find_user($user);
    if ($res["valid"]) {
        $key = Config::jwtKey;
        $token = [
            "exp" => time() + 36000,
            "data" => $res,
        ];
        $jwt = JWT::encode($token, $key, "HS256");
        $x["token"] = $jwt;
        $x["cinema_id"] = $res["cinema_id"];
        Flight::json($x);} else {
        Flight::json($res);
    }

});

Flight::route('POST /movies/create', function () {
    $request = Flight::request();
    $key = Config::jwtKey;
    $user = JWT::decode($request->data->token, $key, ["HS256"]);
    $movie = [
        'name' => $request->data->name,
        'description' => $request->data->description,
        'cinema_id' => $user->data->cinema_id,
    ];

    $res = Flight::pm()->add_movie($movie);
    Flight::json($res);
});

Flight::route('GET /movies/all/@id', function ($id) {
    $res = Flight::pm()->get_all_movies($id);
    Flight::json($res);
});
Flight::start();
