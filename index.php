<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


define('ROOT', dirname(__FILE__));
include_once(ROOT.'/components/session.php');
require_once(ROOT.'/components/router.php');
include_once(ROOT.'/components/Db.php');



/* Вызов Router */
$router = new Router();
$router->run();