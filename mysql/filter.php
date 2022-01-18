<?php
$search = $_POST['inputFilters'];
$filters = $_POST['filters'];
$result = '';
try {
	$pdo = require 'connect.php';
	switch ($filters) {
		case 1:
			$sql = 'SELECT `created_by`, `pdate`, `subject`, `description` FROM `blogs` WHERE created_by=(:created_by) AND EXISTS (SELECT * FROM `comments` WHERE blogs.blogid=comments.blogid AND comments.sentiment="positive");';
			$query = $pdo->prepare($sql);
			$query->execute([':created_by' => $search]);
			break;
		case 2:
			$sql = 'SELECT DISTINCT `created_by`, `pdate`, `subject`, `description` FROM `blogs` WHERE `pdate` IN (SELECT `pdate` FROM `blogs` WHERE `pdate`=(:pdate));';
			$query = $pdo->prepare($sql);
			$query->execute([':pdate' => $search]);
			break;
		case 3:
			$sql = 'SELECT DISTINCT `leadername` FROM `follows` WHERE `followername`=(:user1) AND EXISTS (SELECT `leadername` FROM `follows` WHERE `followername`=(:user2));';
			$query = $pdo->prepare($sql);
			$user1 = substr($search, 0, strpos($search, ' '));
			$user2 = substr($search, strpos($search, ' ')+1);
			$query->execute([
				':user1' => $user1,
				':user2' => $user2
			]);
			break;
		case 4:
			$sql = 'SELECT DISTINCT `username` FROM `users` WHERE NOT EXISTS (SELECT * FROM `blogs` WHERE `created_by`=users.username);';
			$query = $pdo->prepare($sql);
			$query->execute();
			break;
		case 5:
			$sql = 'SELECT DISTINCT `sentiment`, `description`, `cdate`, `posted_by` FROM `comments` c1 WHERE `sentiment` = "negative" AND NOT EXISTS (SELECT DISTINCT `posted_by` FROM `comments` c2 WHERE c2.sentiment = "positive" AND c2.posted_by = c1.posted_by);';
			$query = $pdo->prepare($sql);
			$query->execute();
			break;
		case 6:
			$sql = 'SELECT DISTINCT `created_by`, `pdate`, `subject`, `description` FROM `blogs` WHERE NOT EXISTS(SELECT DISTINCT `sentiment` FROM `comments` WHERE comments.sentiment="negative" AND comments.blogid = blogs.blogid);';
			$query = $pdo->prepare($sql);
			$query->execute();
			break;
		default:
			break;
	}
	$query->setFetchMode(PDO::FETCH_ASSOC);
	$data = $query->fetchAll();
	if ($data) {
		foreach ($data as $value) {
			switch ($filters) {
				case 1:
					$result = '<a href="#" class="list-group-item list-group-item-action">'.
						'<div class="d-flex w-100 justify-content-between">'.
	          	'<h4 class="mb-1">Created by: <span style="color: blue;">'.$value['created_by'].'</span></h4>'.
	          	'<small class="text-muted">'.$value['pdate'].'</small>'.
	        	'</div>'.
	        	'<h5 class="mb-1">Subject: <span style="font-style: italic;">'.$value['subject'].'</span></h5>'.
	        	'<div class="row"><h6 class="text-muted text-truncate">Description: '.$value['description'].'</h6></div>'.
        	'</a>';
        	break;
				case 2:
					$result = '<a href="#" class="list-group-item list-group-item-action">'.
						'<div class="d-flex w-100 justify-content-between">'.
	          	'<h4 class="mb-1">Created by: <span style="color: blue;">'.$value['created_by'].'</span></h4>'.
	          	'<small class="text-muted">'.$value['pdate'].'</small>'.
	        	'</div>'.
	        	'<h5 class="mb-1">Subject: <span style="font-style: italic;">'.$value['subject'].'</span></h5>'.
	        	'<div class="row"><h6 class="text-muted text-truncate">Description: '.$value['description'].'</h6></div>'.
        	'</a>';
        	break;
				case 3:
					$result = '<a href="#" class="list-group-item list-group-item-action">'.
	          '<div class="d-flex w-100 justify-content-between">'.
	          	'<h4 class="mb-1">Username: <span style="color: blue;">'.$value['leadername'].'</span></h4>'.
	          	'<small class="text-muted">followed by: catlover, scooby</small>'.
	          '</div>'.
        	'</a>'; 
					break;
				case 4:
					$result = '<a href="#" class="list-group-item list-group-item-action">'.
            '<div class="d-flex w-100 justify-content-between">'.
              '<h4 class="mb-1">username: <span style="color: blue;">'.$value['username'].'</span></h4>'.
            '</div>'.
          '</a>';
					break;
				case 5:
					$result = '<a href="#" class="list-group-item list-group-item-action">'.
						'<div class="d-flex w-100 justify-content-between">'.
	          	'<h4 class="mb-1">Posted by: <span style="color: blue;">'.$value['posted_by'].'</span></h4>'.
	          	'<small class="text-muted">'.$value['cdate'].'</small>'.
	        	'</div>'.
	        	'<h5 class="mb-1">Sentiment: <span style="font-style: italic;">'.$value['sentiment'].'</span></h5>'.
	        	'<div class="row"><h6 class="text-muted text-truncate">Description: '.$value['description'].'</h6></div>'.
        	'</a>';
        	break;
				case 6:
					$result = '<a href="#" class="list-group-item list-group-item-action">'.
						'<div class="d-flex w-100 justify-content-between">'.
	          	'<h4 class="mb-1">Created by: <span style="color: blue;">'.$value['created_by'].'</span></h4>'.
	          	'<small class="text-muted">'.$value['pdate'].'</small>'.
	        	'</div>'.
	        	'<h5 class="mb-1">Subject: <span style="font-style: italic;">'.$value['subject'].'</span></h5>'.
	        	'<div class="row"><h6 class="text-muted text-truncate">Description: '.$value['description'].'</h6></div>'.
        	'</a>';
					break;
			}
		}
		$_SESSION['loggedin'] = true;
		echo json_encode(['code' => 200, 'msg' => $result]);
		$pdo = null;
		exit;
	}
	echo json_encode(['code' => 400, 'msg' => 'result not found']);
	$pdo = null;
} catch(PDOException $e) {
	die($e->getMessage());
}
?>