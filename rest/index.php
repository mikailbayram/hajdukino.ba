<?php
require_once '../vendor/autoload.php';
//require 'lib/auth.php';
require 'database.php';


$config = include('config.php');

//storage
require 'storage/user.php';
require 'storage/movie.php';

//models
require 'modules/auth.php';
require 'modules/movies.php';

Flight::start();
