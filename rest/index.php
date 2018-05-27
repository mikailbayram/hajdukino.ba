<?php
require_once '../vendor/autoload.php';
require_once 'PersistanceManager.class.php';
require_once 'Config.class.php';

Flight::register('pm', 'PersistanceManager', [Config::DB]);


Flight::start();
?>  