<?php
use \Firebase\JWT\JWT;

Flight::route('POST /user/register', function () {
    $request = Flight::request();
    $userDb = new User();
    $user = [
        'username' => $request->data->username,
        'email' => $request->data->email,
        'pass' => $request->data->pass,
        'cinema_name' => $request->data->cinema_name,
    ];
    $added_user = $userDb->add_user($user);
    Flight::json($added_user);
});

Flight::route('POST /user/login', function () {
    $config = include('config.php');                
    $request = Flight::request();
    $userDb = new User();
    $user = [
        'username' => $request->data->username,
        'pass' => $request->data->pass,
    ];

    $res = $userDb->find_user($user);
    if ($res["valid"]) {
        $key = $config["jwtKey"];
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

?>