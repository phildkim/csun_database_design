<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
  <link type="text/css" rel="stylesheet" href="css/font-awesome.min.css">
  <link type="text/css" rel="stylesheet" href="css/style.css">
  <style type="text/css"></style>
  <title>COMP 440 | Home</title>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/font-awesome.min.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>
  <script type="text/javascript">
    var table, blogid, psubject, pdescription, pdate, posted_by;
    window.addEventListener('load', function() {
      table = document.getElementById('table');
      for (var i = 1; i < table.rows.length; i++) {
        table.rows[i].onclick = function() {
          blogid = document.getElementById('blogid').value = this.cells[0].innerHTML;
          psubject = document.getElementById('psubject').value = this.cells[1].innerHTML;
          pdescription = document.getElementById('pdescription').value = this.cells[2].innerHTML;
          pdate = document.getElementById('pdate').value = this.cells[3].innerHTML;
          posted_by = document.getElementById('posted_by').value = this.cells[4].innerHTML;
        };
      }
    });
    // ajax
    $(document).ready(function() {
      // ajax post form submit
      $('#postClose').click(function() {
        $('#postForm').each(function() {
          this.reset();
        });
      });
      $('#postCancel').click(function() {
        $('#postForm').each(function() {
          this.reset();
        });
      });
      $('#postForm').submit(function(e) {
        e.preventDefault();
        var subject = $('#subject').val().trim();
        var description = $('#description').val().trim();
        var created_by = $('#created_by').val().trim();
        var tags = $('#tags').val().trim();
        if (subject != '' && description != '' && tags != '') {
          $.ajax({
            url: '/mysql/posts.php',
            type: 'post',
            dataType: 'json',
            data: {
              subject: subject,
              description: description,
              created_by: created_by,
              tags: tags,
            },
            success: function(res) {
              if (res.code == 200) {
                window.location = res.msg;
              } else {
                alert(res.msg);
              }
            }
          });
        } else {
          alert('empty fields');
        }
      });
      // ajax comment form submit
      $('#commentClose').click(function() {
        $('#commentForm').each(function() {
          this.reset();
        });
      });
      $('#commentCancel').click(function() {
        $('#commentForm').each(function() {
          this.reset();
        });
      });
      $('#commentForm').submit(function(e) {
        e.preventDefault();
        var blogid = $('#blogid').val().trim();
        var posted_by = $('#posted_by').val().trim();
        var commented_by = $('#commented_by').val().trim();
        var comment = $('#comment').val().trim();
        var sentiment = $('#sentiment').val().trim();
        if (comment != '' && sentiment != '') {
          $.ajax({
            url: '/mysql/comments.php',
            type: 'post',
            dataType: 'json',
            data: {
              blogid: blogid,
              posted_by: posted_by,
              commented_by: commented_by,
              comment: comment,
              sentiment: sentiment,
            },
            success: function(res) {
              if (res.code == 200) {
                window.location = res.msg;
              } else {
                alert(res.msg);
              }
            }
          });
        } else {
          $('#message').html('missing fields');
          $('#message').css('display', 'block');
        }
      });
      // filtered search
      $('#list').hide();
      $('#search').change(function(e) {
        if (e.target.checked) {
          $('#table').hide();
          window.location = 'list.php';
        }
        if (!e.target.checked) {
          $('#table').show();
          window.location = 'home.php';
        }
      });
    });
  </script>
</head>
<body>
  
  <!-- navigation bar -->
  <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <div class="container-fluid">
      <a href="index.php" style="text-decoration: unset;">
        <span class="navbar-text mb-0 h5">
          <i class="fas fa-database"></i>&emsp;
          <strong>COMP 440</strong>
        </span>
      </a>
    </div>
    <div class="d-grid gap-2 col-4 d-md-flex justify-content-md-end">
      <a href="#" class="btn btn-primary me-md-2" data-bs-toggle="modal" data-bs-target="#blogpost" type="button" name="blogpost">
        <i class="far fa-edit"></i>&ensp;<strong>Posting</strong>&ensp;
      </a>
      <a href="mysql/logout.php" class="btn btn-secondary me-md-2" id="logout" type="button" name="logout">
        <i class="fas fa-sign-out-alt"></i>&emsp;<strong>Log Out</strong>&ensp;
      </a>    
    </div>
  </nav>
  <br>

  <!-- welcome title and blogs table -->
  <div class="container-fluid">
    <figure class="text-center">
      <blockquote class="blockquote">
        <h1 style="text-align: center;">Welcome <?php echo $_SESSION['user'];?>!</h1>
      </blockquote>
      <h4><figcaption class="blockquote-footer">
        <mark>Blog Posts in 
          <cite title="Blog Feeds">COMP 440&emsp;<i class="fas fa-database"></i></cite>
        </mark>
      </figcaption></h4>
    </figure>
    <hr>

    <!-- blog post modal -->
    <div class="modal fade" id="blogpost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="blogpostLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="blogpostLabel">
              <strong><?php echo $_SESSION['user'];?>'s Blog Post</strong>
            </h5>
            <input type="button" class="btn-close" id="postClose" data-bs-dismiss="modal" aria-label="Close">
          </div>
          <div class="modal-body">
            <form id="postForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <input type="hidden" name="created_by" id="created_by" value="<?php echo $_SESSION['user'];?>">
              <div class="mb-3">
                <label for="subject" class="form-label"><strong>Subject</strong></label>
                <input type="text" class="form-control" id="subject" name="subject" autocomplete="off" placeholder="Enter the subject" autofocus>
                <span></span>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label"><strong>Description</strong></label>
                <textarea type="text" rows="3" class="form-control" id="description" name="description" autocomplete="off" placeholder="Enter the description"></textarea>
                <span></span>
              </div>
              <div class="mb-3">
                <label for="tags" class="form-label"><strong>Tags</strong></label>
                <input type="text" class="form-control" id="tags" name="tags" autocomplete="off" placeholder="Enter the tags">
                <span></span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="postCancel" data-bs-dismiss="modal">
                  <strong>Close</strong>
                </button>
                <input type="submit" class="btn btn-primary" name="submit" value="Submit">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- blog comment modal -->
    <div class="modal fade" id="blogcomment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="blogcommentLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <form id="commentForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" name="commented_by" id="commented_by" value="<?php echo $_SESSION['user'];?>">
            <div class="modal-header">
              <h5 class="modal-title hstack gap-2" id="blogcommentLabel">
                <strong>Posted By <input type="text" class="form-control-plaintext" id="posted_by" name="posted_by" value="<?php echo $posted_by;?>" readonly></strong>
                <strong>Posted On <input type="text" class="form-control-plaintext" id="pdate" name="pdate" value="<?php echo $pdate;?>" readonly></strong>
              </h5>
              <input type="button" class="btn-close" id="commentClose" data-bs-dismiss="modal" aria-label="Close">
            </div>
            <div class="modal-body">
              <div class="row g-3">
                <div class="mb-3 col">
                  <label for="psubject" class="form-label"><strong>Subject:</strong></label>
                  <textarea type="text" class="form-control-plaintext" id="psubject" name="psubject" value="<?php echo $psubject;?>" style="height: 50px; white-space: pre-wrap; border:solid 0.5px grey; padding: 10px;" readonly>
                  </textarea>
                </div>
                <div class="mb-3 col">
                  <label for="postSubject" class="form-label"><strong>Sentiment:</strong></label>
                  <select class="form-select form-select-md mb-2" id="sentiment" aria-label=".form-select-md" style="height: 50px;">
                    <option selected style="font-size: large">Select Postive or Negative</option>
                    <option value="positive" style="font-size: large">Positive</option>
                    <option value="negative" style="font-size: large">Negative</option>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label for="pdescription" class="form-label"><strong>Description:</strong></label>
                <textarea type="text" class="form-control-plaintext" id="pdescription" name="pdescription" value="<?php echo $pdescription;?>" style="height: 100px; white-space: pre-wrap; border:solid 0.5px grey; padding: 10px;" readonly></textarea>
              </div>
              <hr>
              <div class="mb-3">
                <div class="form-floating input-group-lg">
                  <textarea class="form-control" type="text" id="comment" name="comment" autocomplete="off" style="height: 150px" autofocus></textarea>
                  <label for="comment" class="col-sm-4 col-form-label">Enter Comment:</label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <span id="message"></span>
              <button type="button" class="btn btn-secondary" id="commentCancel" data-bs-dismiss="modal">
                <strong>Close</strong>
              </button>
              <input type="submit" class="btn btn-primary" name="submit" value="Submit">
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="hstack gap-3">
        <div class="form-check form-switch ms-auto">
          <input class="form-check-input" id="search" name="search" type="checkbox" role="switch" data-toggle="toggle" style="height: 20px; width: 40px;">
          <label class="form-check-label" for="search" style="font-size: large;">
            <strong>&emsp;Filtered Search</strong>
          </label>
        </div>
      </div>
    </div>

    <?php
      $blogs = require 'mysql/table.php';
      $blogs->setFetchMode(PDO::FETCH_CLASS, 'Table');
      $table = $blogs->fetch();
      var_dump($table);
    ?>
  </div>

</body>
</html>