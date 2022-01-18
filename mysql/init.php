<?php
try {
	$pdo = require_once 'connect.php';
	$sql = file_get_contents(mysqltbl);
	$pdo->exec($sql);
	$pdo = null; 
	header('Location: ../index.php');
} catch (PDOException $e) {
	die($e->getMessage());
}
?>