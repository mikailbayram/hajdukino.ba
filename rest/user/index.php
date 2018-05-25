<?php
require_once '../../vendor/autoload.php';
require_once '../PersistanceManager.class.php';
require_once '../Config.class.php';

Flight::register('pm', 'PersistanceManager', [Config::DB]);

Flight::route('POST /', function(){
    $request = Flight::request();
    $user = [
      'username' => $request->data->username,
      'email' => $request->data->email,
      'pass' => $request->data->pass
    ];
    $added_user = Flight::pm()->add_user($user);
    Flight::json($added_user);
  });

Flight::start();
?>  