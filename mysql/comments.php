<?php

date_default_timezone_set('America/Los_Angeles');
$blogid = $_POST['blogid'];
$posted_by = $_POST['posted_by'];
$commented_by = $_POST['commented_by'];
$comment = $_POST['comment'];
$sentiment = $_POST['sentiment'];
$cdate = date('Y-m-d');
/**
 * ADD: CLIENT/SERVER MESSAGES (SUCCCESS/ERROR)
 * TEST: 
 *  1. USER CAN COMMENT 3 BLOGS PER DAY
 *  2. USER CAN COMMENT ONCE FOR EACH BLOG
 *  3. USER CAN NOT COMMENT TO SELF
 */
try {
  $pdo = require 'connect.php';
  $sql = 'SELECT count(*) FROM comments WHERE cdate=:cdate AND posted_by=:posted_by;';
  $query = $pdo->prepare($sql);
  $query->bindParam(':cdate', $cdate, PDO::PARAM_STR);
  $query->bindParam(':posted_by', $commented_by, PDO::PARAM_STR);
  $query->execute();
  $count = $query->fetchColumn();
  // 1. USER CAN COMMENT 3 BLOGS PER DAY
  if ($count < 3) {
    $sql = 'SELECT count(*) FROM comments WHERE blogid=:blogid AND posted_by=:posted_by;';
    $query = $pdo->prepare($sql);
    $query->bindParam(':blogid', $blogid, PDO::PARAM_INT);
    $query->bindParam(':posted_by', $commented_by, PDO::PARAM_STR);
    $query->execute();
    $count = $query->fetchColumn();
    // 2. USER CAN COMMENT ONCE FOR EACH BLOG
    if ($count < 1) {
      // 3. USER CAN NOT COMMENT TO SELF
      if ($commented_by != $posted_by) {
        $sql = 'INSERT INTO comments(sentiment, description, cdate, blogid, posted_by) VALUES (:sentiment, :description, :cdate, :blogid, :posted_by);';
        $query = $pdo->prepare($sql);
        $query->execute([
          ':sentiment' => $sentiment,
          ':description' => $comment,
          ':cdate' => $cdate,
          ':blogid' => $blogid,
          ':posted_by' => $commented_by
        ]);
        echo json_encode(['code' => 200, 'msg' => 'home.php']);
        $pdo = null;
        exit;
      } else {
        echo json_encode(['code' => 400, 'msg' => 'only comment on other blogs']);
      }
    } else {
      echo json_encode(['code' => 400, 'msg' => 'only 1 comment per blog']);
    }
  } else {
    echo json_encode(['code' => 400, 'msg' => 'only 3 comments per day']);
  }
  $pdo = null;
} catch(PDOException $e) {
  die($e->getMessage());
}
?>