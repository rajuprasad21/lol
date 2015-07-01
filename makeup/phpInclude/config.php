<?php
//error_reporting(0);
$servername = $_SERVER['HTTP_HOST'];
$pathimg=$servername."/";
define("ROOT_PATH",$_SERVER['DOCUMENT_ROOT']);

define('LOCALHOST','localhost');
define('USER_NAME','root');
define('USER_PASS','dkbose');
define('DB_NAME','makeup');
define("UPLOAD_PATH","http://localhost/makeup");
define("BASE_PATH","http://localhost/makeup");
define("CLIENT_ID","");
define("CLIENT_SECRET","");
define("V","");
define("AUTH_KEY","");

$DB_HOST = LOCALHOST;
$DB_DATABASE = DB_NAME;
$DB_USER = USER_NAME;
$DB_PASSWORD = USER_PASS;

define('SMTP_USER','admin@embazaar.com');
define('SMTP_EMAIL','admin@embazaar.com');
define('SMTP_PASSWORD','core2duo');
define('SMTP_NAME','Embazaar Inc');
define('SMTP_HOST','gator4105.hostgator.com');
define('SMTP_PORT','465');
