<?php

date_default_timezone_set('America/Los_Angeles');
$subject = $_POST['subject'];
$description = $_POST['description'];
$created_by = $_POST['created_by'];
$tags = $_POST['tags'];
$pdate = date('Y-m-d');
/**
 * ADD: CLIENT/SERVER MESSAGES (SUCCCESS/ERROR)
 * TEST: USER CAN POST 2 BLOGS PER DAY
 */
try {
	$pdo = require 'connect.php';
	$sql = 'SELECT count(*) FROM blogs WHERE pdate=:pdate AND created_by=:created_by';
	$data = $pdo->prepare($sql);
	$data->bindValue(':pdate', $pdate);
	$data->bindValue(':created_by', $created_by);
	$data->execute();
	$count = $data->fetchColumn();
	// get count of users blog post per day
	if ($count < 2) {
		// insert into blogs
		$sql = 'INSERT INTO blogs (subject, description, pdate, created_by) VALUES (:subject, :description, :pdate, :created_by);';
		$data = $pdo->prepare($sql);
		$data->bindValue(':subject', $subject);
		$data->bindValue(':description', $description);
		$data->bindValue(':pdate', $pdate);
		$data->bindValue(':created_by', $created_by);
		$data->execute();
		$blogid = $pdo->lastInsertId();
		$count = 0;
		$tag = [];
		$token = strtok($tags, ",");
		while ($token !== false) {
		  $tag[] = $token;
		  $token = strtok(",");
		  $count++;
		}
		// tokenize tags string and then insert into blogstags
		$sql = 'INSERT INTO blogstags (blogid, tag) VALUES (:blogid, :tag);';
		$data = $pdo->prepare($sql);
		for ($i = 0; $i < $count; $i++) {
			$blogstags = [
				'blogid' => $blogid,
				'tag' => $tag[$i],
			];
			$data->bindValue(':blogid', $blogstags['blogid']);
			$data->bindValue(':tag', $blogstags['tag']);
			$data->execute();
		}
		$pdo = null;
		echo json_encode(['code' => 200, 'msg' => 'home.php']);
		exit;
	} else {
		$pdo = null;
		echo json_encode(['code' => 400, 'msg' => 'only 2 blog posts per day']);
		exit;
	}
} catch (PDOException $e) {
	die($e->getMessage());
}
?>