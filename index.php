<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/./vendor/autoload.php';
require __DIR__.'/./config/db.php';

$app = new \Slim\App;
 
require __DIR__.'/./routes/master/pelanggan.php';

 
