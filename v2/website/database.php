<?php

// Include file auth
if(!defined("api")){
    header('HTTP/1.0 403 Forbidden');
    die('File access forbidden.');
}

// Pull config variables
require_once("config.php");

// New MySQL connection
$mysqli = new mysqli($host, $username, $password, $database, $port);

if($mysqli->connect_error){
    die("Database connection failed!");
}