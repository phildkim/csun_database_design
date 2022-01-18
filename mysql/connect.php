<?php
session_start();
define('serverip', 'localhost');
define('database', 'COMP_440');
define('username', 'comp440');
define('password', 'pass1234');
define('mysqltbl', 'ProjDB.sql');

function connect(string $host, string $db, string $user, string $pass): PDO {
  try {
    $conn = "mysql:host=$host;dbname=$db;charset=UTF8;";
    return new PDO($conn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
return connect(serverip, database, username, password);