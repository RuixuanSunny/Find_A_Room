<?php

header("Access-Control-Allow-Origin: *", true);

ini_set('display_errors', 1);
ini_set('display_starup_errors', 1);
error_reporting(E_ALL);

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

function dbConnect() {
  $dbHost = "localhost";
  $dbName = "Sunny";
  $dbUser = "root";
  $dbPass = "root";

  $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
  return $db;
}

  echo "success_jsonp({";
  echo '"api": {';

  $app->post('/reading', 'insertReading');
  $app->get('/reading', 'insertReading');

  function insertReading() {
    $db = dbConnect();
    $value = $_GET['value'];
    $room = $_GET['room'];
    
    $insert = $db->prepare("INSERT INTO yun (value,room) VALUES ('$value','$room')");
    $insert->execute();

    echo '"status": "success"';

  }

  $app->get('/readings/', 'selectReadings');
  
  function selectReadings() {

  	$db = dbConnect();

    // $select = $db->query("SELECT * FROM yun");
    $select = $db->query("SELECT * FROM yun ORDER BY time DESC LIMIT 1");
    echo '"value": ' . "[";
    $values = "";
    /*while($row = $select->fetch()) {
    	$values .= $row['value'] . ",";
    }*/
    $values = $select->fetch()['value'];
    $values = rtrim($values, ",");
    echo $values;
    echo "], ";

    // $select = $db->query("SELECT * FROM yun");
    $select = $db->query("SELECT * FROM yun ORDER BY time DESC LIMIT 1");
    echo '"room": ' . '["';
    $rooms = "";
    /*while($row = $select->fetch()) {
      $rooms = 
    }*/
    $rooms = $select->fetch()['room'];
    $rooms = rtrim($rooms, ",");
    echo $rooms;
    echo '"]';
  }

  $app->run();

  echo "}";
  echo "})";

?>
