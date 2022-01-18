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
          $('#post-error').html('missing fields');
          $('#post-error').css('display', 'block');
        }
      });
      // ajax search filter toggle
      $('#search').change(function(e) {
        if (e.target.checked) {
          window.location = 'list.php';
        }
        if (!e.target.checked) {
          window.location = 'home.php';
        }
      });
      $('#listBlogsFilter').hide();
      $('#listUsersFilter').hide();
      $('#listFollowersFilter').hide();
      $('#displayUsers1Filter').hide();
      $('#displayUsers2Filter').hide();
      $('#displayUsers3Filter').hide();
      $('#searchForm').hide();
      $('#inputFilters').attr('disabled', true);
      $('#submitFilter').attr('disabled', true);
      var filters = 0;
      var msg = '';
      $('#listBlogs').click(function() {
        filters = 1;
        // $('#listBlogsFilter').hide();
        $('#listUsersFilter').hide();
        $('#listFollowersFilter').hide();
        $('#displayUsers1Filter').hide();
        $('#displayUsers2Filter').hide();
        $('#displayUsers3Filter').hide();
        $('#searchForm').show();
        $('#inputFilters').attr('placeholder', 'Enter username `X`');
        $('#inputFilters').attr('readonly', false);
        $('#inputFilters').attr('disabled', false);
        $('#submitFilter').attr('disabled', false);
        $('#searchForm').each(function() {
          this.reset();
        });
      });
      $('#listUsers').click(function() {
        filters = 2;
        $('#listBlogsFilter').hide();
        // $('#listUsersFilter').hide();
        $('#listFollowersFilter').hide();
        $('#displayUsers1Filter').hide();
        $('#displayUsers2Filter').hide();
        $('#displayUsers3Filter').hide();
        $('#searchForm').show();
        $('#inputFilters').attr('placeholder', 'Enter date (yyyy-mm-dd)');
        $('#inputFilters').attr('readonly', false);
        $('#inputFilters').attr('disabled', false);
        $('#submitFilter').attr('disabled', false);
        $('#searchForm').each(function() {
          this.reset();
        });
      });
      $('#listFollowers').click(function() {
        filters = 3;
        $('#listBlogsFilter').hide();
        $('#listUsersFilter').hide();
        // $('#listFollowersFilter').hide();
        $('#displayUsers1Filter').hide();
        $('#displayUsers2Filter').hide();
        $('#displayUsers3Filter').hide();
        $('#searchForm').show();
        $('#inputFilters').attr('placeholder', 'Enter username `X` and `Y`');
        $('#inputFilters').attr('readonly', false);
        $('#inputFilters').attr('disabled', false);
        $('#submitFilter').attr('disabled', false);
        $('#searchForm').each(function() {
          this.reset();
        });
      });
      $('#displayUsers1').click(function() {
        filters = 4;
        msg = 'users who never posted a blog';
        $('#listBlogsFilter').hide();
        $('#listUsersFilter').hide();
        $('#listFollowersFilter').hide();
        // $('#displayUsers1Filter').hide();
        $('#displayUsers2Filter').hide();
        $('#displayUsers3Filter').hide();
        $('#searchForm').show();
        $('#inputFilters').attr('placeholder', 'Users who never posted a blog');
        $('#inputFilters').attr('readonly', true);
        $('#inputFilters').attr('disabled', false);
        $('#submitFilter').attr('disabled', false);
        $('#searchForm').each(function() {
          this.reset();
        });
      });
      $('#displayUsers2').click(function() {
        filters = 5;
        msg = 'users who posted negative comments';
        $('#listBlogsFilter').hide();
        $('#listUsersFilter').hide();
        $('#listFollowersFilter').hide();
        $('#displayUsers1Filter').hide();
        // $('#displayUsers2Filter').hide();
        $('#displayUsers3Filter').hide();
        $('#searchForm').show();
        $('#inputFilters').attr('placeholder', 'Users who never posted negative comments');
        $('#inputFilters').attr('readonly', true);
        $('#inputFilters').attr('disabled', false);
        $('#submitFilter').attr('disabled', false);
        $('#searchForm').each(function() {
          this.reset();
        });
      });
      $('#displayUsers3').click(function() {
        filters = 6;
        msg = 'users who posted blogs with positive comments';
        $('#listBlogsFilter').hide();
        $('#listUsersFilter').hide();
        $('#listFollowersFilter').hide();
        $('#displayUsers1Filter').hide();
        $('#displayUsers2Filter').hide();
        // $('#displayUsers3Filter').hide();
        $('#searchForm').show();
        $('#inputFilters').attr('placeholder', 'Users who never posted a blogs with a negative comment');
        $('#inputFilters').attr('readonly', true);
        $('#inputFilters').attr('disabled', false);
        $('#submitFilter').attr('disabled', false);
        $('#searchForm').each(function() {
          this.reset();
        });
      });
      $('#searchForm').submit(function(e) {
        e.preventDefault();
        var inputFilters = (filters <= 3) ?  $('#inputFilters').val().trim() :  msg;
        if (inputFilters != '') {
          $.ajax({
            url: '/mysql/filter.php',
            type: 'post',
            dataType: 'json',
            data: {
              inputFilters: inputFilters,
              filters: filters
            },
            success: function(res) {
              if (res.code == 200) {
                switch (filters) {
                  case 1:
                    $('#listBlogsFilter').html(res.msg);
                    $('#listBlogsFilter').show();
                    break;
                  case 2:
                    $('#listUsersFilter').html(res.msg);
                    $('#listUsersFilter').show();
                    break;
                  case 3:
                    $('#listFollowersFilter').html(res.msg);
                    $('#listFollowersFilter').show();
                    break;
                  case 4:
                    $('#displayUsers1Filter').html(res.msg);
                    $('#displayUsers1Filter').show();
                    break;
                  case 5:
                    $('#displayUsers2Filter').html(res.msg);
                    $('#displayUsers2Filter').show();
                    break;
                  case 6:
                    $('#displayUsers3Filter').html(res.msg);
                    $('#displayUsers3Filter').show();
                    break;               
                  default:
                    break;
                }
              } else {
                alert(res.msg);
              }
            }
          });          
        } else {
          alert('enter search keyword');
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
        <mark>Blogs Filtered Search List in 
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

    <!-- toggle filter search -->
    <div class="container">
      <div class="hstack gap-3">
        <div class="form-check form-switch ms-auto">
          <input class="form-check-input" id="search" name="search" type="checkbox" role="switch" data-toggle="toggle" style="height: 20px; width: 40px;" checked>
          <label class="form-check-label" for="search" style="font-size: large;">
            <strong>&emsp;Filtered Search</strong>
          </label>
        </div>
      </div>
    </div>
    <br>

    <!-- radio buttons for options to filter list -->
    <div class="container">
      <div class="vstack gap-3">
        <h3>Select the following options:</h3>
        <div class="form-check">
          <input class="form-check-input" type="radio" id="listBlogs" name="filter">
          <label class="form-check-label" for="listBlogs">
            1. List all the blogs of user X, such that all the comments are positive for these blogs.
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" id="listUsers" name="filter">
          <label class="form-check-label" for="listUsers">
            2. List the users who posted the most number of blogs on 10/10/2021; if there is a tie, list all the users who have a tie.
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" id="listFollowers" name="filter">
          <label class="form-check-label" for="listFollowers">
            3. List the users who are followed by both X and Y. Usernames X and Y are inputs from the user.
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" id="displayUsers1" name="filter">
          <label class="form-check-label" for="displayUsers1">
            4. Display all the users who never posted a blog.
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" id="displayUsers2" name="filter">
          <label class="form-check-label" for="displayUsers2">
            5. Display all the users who posted some comments, but each of them is negative.
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" id="displayUsers3" name="filter">
          <label class="form-check-label" for="displayUsers3">
            6. Display those users such that all the blogs they posted so far never received any negative comments.
          </label>
        </div>

        <!-- search bar for 1-3 -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="searchForm">
          <div class="hstack gap-3 form-check">
            <div class="input-group mb-3">
              <span class="input-group-text" id="searchFilter"><i class="fas fa-search"></i></span>
              <input type="text" class="form-control" id="inputFilters" placeholder="Enter keywords to filter search" aria-label="searchFilter" aria-describedby="searchFilters" name="inputFilters" value="<?php echo $inputFilters; ?>">
            </div>
            <div class="input-group mb-3">
              <input type="submit" class="btn btn-primary" id="submitFilter" name="submitFilter" value="Search">
            </div>
          </div>
        </form>

        <!-- result list 1-6 -->
        <div class="list-group" id="listBlogsFilter"></div>
        <div class="list-group" id="listUsersFilter"></div>
        <div class="list-group" id="listFollowersFilter"> </div>
        <div class="list-group" id="displayUsers1Filter"> </div>
        <div class="list-group" id="displayUsers2Filter"></div>
        <div class="list-group" id="displayUsers3Filter"> </div>
      </div>
  </div>
</body>
</html>