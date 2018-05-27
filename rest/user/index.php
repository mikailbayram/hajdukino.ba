<?php
require_once '../../vendor/autoload.php';
require_once '../PersistanceManager.class.php';
require_once '../Config.class.php';

use \Firebase\JWT\JWT;

Flight::register('pm', 'PersistanceManager', [Config::DB]);

Flight::route('POST /register', function(){
    $request = Flight::request();
    $user = [
      'username' => $request->data->username,
      'email' => $request->data->email,
      'pass' => $request->data->pass
    ];
    $added_user = Flight::pm()->add_user($user);
    Flight::json($added_user);
  });

Flight::route('POST /login', function(){
  $request = Flight::request();
  $user = [
    'username' => $request->data->username,
    'pass' => $request->data->pass
  ];

  $res = Flight::pm()->find_user($user);
  if($res["valid"]){
  $key = Config::jwtKey;
  $token = [
    "exp" => time() + 3600,
    "data" => $res
  ];
  $jwt = JWT::encode($token, $key, "HS256");
  Flight::json($jwt);}
  else 
    Flight::json($res);
});

Flight::start();
?>  