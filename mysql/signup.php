<?php

$newUsername = $_POST['newUsername'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$validUsername = false;
$validPassword = false;
$validEmail = false;

try {
	$pdo = require 'connect.php';
	// check for username
	$sql = 'SELECT * FROM users WHERE username=:username;';
	$query = $pdo->prepare($sql);
	$query->bindParam(':username', $newUsername, PDO::PARAM_STR);
	$query->execute();
	$user = $query->fetch(PDO::FETCH_ASSOC);
	if ($user) {
		echo json_encode(['code' => 400, 'msg' => 'username already exists']);
	} else {
		$validUsername = true;
		// check for password equals confirm password
		if ($newPassword !== $confirmPassword) {
			echo json_encode(['code' => 400, 'msg' => 'password does not match']);
		} else {
			$validPassword = true;
			// check for email
			$sql = 'SELECT * FROM users WHERE email=:email;';
			$query = $pdo->prepare($sql);
			$query->bindParam(':email', $email, PDO::PARAM_STR);
			$query->execute();
			$email = $query->fetch(PDO::FETCH_ASSOC);
			if ($email) {
				echo json_encode(['code' => 400, 'msg' => 'email already exists']);
			} else {
				$validEmail = true;
				if ($validUsername && $validPassword && $validEmail) {
					$sql = 'INSERT INTO users (username, password, firstName, lastName, email) VALUES (:username, :password, :firstName, :lastName, :email);';
					$query = $pdo->prepare($sql);
					$query->bindValue(':username', $newUsername);
					$query->bindValue(':password', $newPassword);
					$query->bindValue(':firstName', $firstname);
					$query->bindValue(':lastName', $lastname);
					$query->bindValue(':email', $email);
					$query->execute();
					$_SESSION['loggedin'] = true;
					$_SESSION['user'] = $newUsername;
					echo json_encode(['code' => 200, 'msg' => 'home.php']);
					$pdo = null;
					exit;
				} else {
					echo json_encode(['code' => 400, 'msg' => 'please try again']);
				}
			}
		}
	}
	$pdo = null;
} catch(PDOException $e) {
	die($e->getMessage());
}
?>