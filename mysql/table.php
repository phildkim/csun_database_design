<?php
echo
'<div class="container table-responsive shadow bg-light.bg-gradient" id="ptable">'.
  '<table class="table table-hover table-sm table-bordered border-dark table-dark" id="table">'.
    '<thead class="table table-dark border-light" style="font-weight: bolder; font-size: large;"><tr>'.
      '<th class="header" scope="col" style="text-align: center; max-width: 50px;">BlogId</th>'.
      '<th class="header" scope="col" style="text-align: left; max-width: 80px;">Subject</th>'.
      '<th class="header" scope="col" style="text-align: left; max-width: 500px;">Description</th>'.
      '<th class="header" scope="col" style="text-align: center; max-width: 70px;">Posted On</th>'.
      '<th class="header" scope="col" style="text-align: center; max-width: 70px;">Posted By</th>'.
      '<th class="header" scope="col" style="text-align: center; max-width: 90px;"></th>'.
    '</tr></thead>'.
  '<tbody>';
class Table extends RecursiveIteratorIterator {
  function __construct($it) {
    parent::__construct($it, self::LEAVES_ONLY);
  }
  function current() {
    return parent::current();
  }
  function beginChildren() {
    echo '<tr class="table-light"><div class="row">';
  }
  function endChildren() {
    echo '<td style="text-align: center; max-width: 90px;"><a href="#" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#blogcomment" name="blogcomment" type="button"><strong><i class="fas fa-plus"></i> Comment</strong></a></td></div></tr>';
  }
}
try {
  $pdo = require 'connect.php';
  $sql = 'SELECT * FROM blogs';
  $query = $pdo->prepare($sql);
  $query->execute();
  $query->setFetchMode(PDO::FETCH_ASSOC);
  $data = '';
  foreach(new Table(new RecursiveArrayIterator($query->fetchAll())) as $k => $v) {
    switch ($k) {
      case 'blogid':
        $data = '<th scope="row" style="text-align: center; max-width: 50px;">'.$v.'</th>';
        break;
      case 'subject':
        $data = '<td class="col-2 text-truncate" style="text-align: left; max-width: 80px;">'.$v.'</td>';
        break;
      case 'description':
        $data = '<td class="col-2 text-truncate" style="text-align: left; max-width: 500px;">'.$v.'</td>';
        break;
      case 'pdate':
        $data = '<td style="text-align: center; max-width: 70px;">'.$v.'</td>';
        break;
      case 'created_by':
        $data = '<td style="text-align: center; max-width: 70px;">'.$v.'</td>';
        break;
      default:
        break;
    }
    echo $data;
  }
  $pdo = null;
} catch(PDOException $e) {
  die($e->getMessage());
}
echo '</tbody></table></div>'.
'<input type="hidden" id="blogid" name="blogid" readonly>'.
'<input type="hidden" id="psubject" name="psubject" readonly>'.
'<input type="hidden" id="pdescription" name="pdescription" readonly>'.
'<input type="hidden" id="pdate" name="pdate" readonly>'.
'<input type="hidden" id="posted_by" name="posted_by" readonly>'.
'<br>';
?>