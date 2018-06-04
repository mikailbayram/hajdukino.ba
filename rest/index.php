<?php
require_once '../vendor/autoload.php';
//require 'lib/auth.php';
require 'database.php';


$config = include('config.php');

//storage
require 'storage/user.php';
require 'storage/movie.php';
require 'storage/hall.php';
require 'storage/projection.php';


//models
require 'modules/auth.php';
require 'modules/movies.php';
require 'modules/halls.php';
require 'modules/projections.php';


Flight::start();
?>