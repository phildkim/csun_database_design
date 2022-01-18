<?php

$username = $_POST['username'];
$password = $_POST['password'];
try {
	$pdo = require 'connect.php';
	$sql = 'SELECT * FROM users WHERE username = (:username) AND password = (:password) LIMIT 1;';
	$query = $pdo->prepare($sql);
	$query->bindParam(':username', $username, PDO::PARAM_STR);
	$query->bindParam(':password', $password, PDO::PARAM_STR);
	$query->execute();
	$data = $query->fetch(PDO::FETCH_ASSOC);
	if ($data) {
		$_SESSION['loggedin'] = true;
		$_SESSION['user'] = $data['username'];
		echo json_encode(['code' => 200, 'msg' => 'home.php']);
		$pdo = null;
		exit;
	}
	echo json_encode(['code' => 400, 'msg' => 'invalid credentials']);
	$pdo = null;
	exit;
} catch (PDOException $e) {
	die($e->getMessage());
}
?>