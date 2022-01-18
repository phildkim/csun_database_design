<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
  <link type="text/css" rel="stylesheet" href="css/font-awesome.min.css">
  <link type="text/css" rel="stylesheet" href="css/style.css">
  <title>COMP 440</title>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/font-awesome.min.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>
  <script type="text/javascript">
    var username, password, showpassword;
    var newPassword, showNewPassword;
    var confirmPassword, showConformPassword;
    var loginForm, signupForm;
    var newUsername, firstname, lastname, email;
    window.addEventListener("load", function() {
      // login
      loginForm = document.getElementById("loginForm");
      password = document.getElementById("password");
      username = document.getElementById("username");
      showpassword = document.getElementById("showpassword");
      // signup
      signupForm = document.getElementById("signupForm");
      newUsername = document.getElementById("newUsername");
      newPassword = document.getElementById("newPassword");
      showNewPassword = document.getElementById("showNewPassword");
      confirmPassword = document.getElementById("confirmPassword");
      showConformPassword = document.getElementById("showConformPassword");
      firstname = document.getElementById("firstname");
      lastname = document.getElementById("lastname");
      email = document.getElementById("email");
      // show passwords
      showpassword.addEventListener("click", function() {
        password.type = (password.type === "password") ? "text" : "password";
      });
      showNewPassword.addEventListener("click", function() {
        newPassword.type = (newPassword.type === "password") ? "text" : "password";
      });
      showConfirmPassword.addEventListener("click", function() {
        confirmPassword.type = (confirmPassword.type === "password") ? "text" : "password";
      });
      // login form
      loginForm.addEventListener("input", debounce(function(e) {
        switch (e.target.id) {
          case "username":
            validateInputFields(0, username.value.trim(), username);
            break;
          case "password":
            validateInputFields(1, password.value.trim(), password);
            break;
          default:
            break;
        }
      }, 1000));
      // signup form
      signupForm.addEventListener("input", debounce(function(e) {
        switch (e.target.id) {
          case "newUsername":
            validateInputFields(0, newUsername.value.trim(), newUsername);
            break;
          case "newPassword":
            validateInputFields(1, newPassword.value.trim(), newPassword);
            break;
          case "confirmPassword":
            validateInputFields(1, confirmPassword.value.trim(), confirmPassword);
            break;
          case "firstname":
            validateInputFields(2, firstname.value.trim(), firstname);
            break;
          case "lastname":
            validateInputFields(2, lastname.value.trim(), lastname);
            break;
          case "email":
            validateInputFields(3, email.value.trim(), email);
            break;
          default:
            break;
        }
      }, 100));
    });
    // ajax login
    $(document).ready(function() {

      $('#loginClose').click(function() {
        showValid(loginMessage, "success");
        $('#loginForm').each(function() {
          this.reset();
        });
      });

      $('#loginCancel').click(function() {
        showValid(loginMessage, "success");
        $('#loginForm').each(function() {
          this.reset();
        });
      });
      
      $('#loginForm').submit(function(e) {
        e.preventDefault();
        var username = $('#username').val().trim();
        var password = $('#password').val().trim();
        if (username != '' && password != '') {
          $.ajax({
            url: '/mysql/login.php',
            type: 'post',
            dataType: 'json',
            data: {
              username: username,
              password: password
            },
            success: function(res) {
              if (res.code == 200) {
                window.location = res.msg;
              } else {
                $('#loginMessage').html(res.msg);
                $('#loginMessage').css('display', 'block');
              }
            }
          });
        } else {
          $('#loginMessage').html('missing fields required');
          $('#loginMessage').css('display', 'block');
        }
      });
      // ajax signup
      $('#signupCancel').click(function() {
        showValid(signupMessage, "success");
        $('#signupForm').each(function() {
          this.reset();
        });
      });
      $('#signupClose').click(function() {
        showValid(signupMessage, "success");
        $('#signupForm').each(function() {
          this.reset();
        });
      });
      $('#signupForm').submit(function(e) {
        e.preventDefault();
        var newUsername = $('#newUsername').val().trim();
        var newPassword = $('#newPassword').val().trim();
        var confirmPassword = $('#confirmPassword').val().trim();
        var firstname = $('#firstname').val().trim();
        var lastname = $('#lastname').val().trim();
        var email = $('#email').val().trim();
        if (newUsername != '' && newPassword != '' && confirmPassword != '' && firstname != '' && lastname != '' && email != '') {
          $.ajax({
            url: '/mysql/signup.php',
            type: 'post',
            dataType: 'json',
            data: {
              newUsername: newUsername,
              newPassword: newPassword,
              confirmPassword: confirmPassword,
              firstname: firstname,
              lastname: lastname,
              email: email
            },
            success: function(res) {
              if (res.code == 200) {
                window.location = res.msg;
              } else {
                // $('#signupMessage').html(res.msg);
                // $('#signupMessage').css('display', 'block');
                alert(res.msg);
              }
            }
          });
        } else {
          $('#signupMessage').html('missing fields required');
          $('#signupMessage').css('display', 'block');
        }
      });
    });
  </script>
</head>
<body>

  <!-- navigation bar -->
  <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <div class="container-fluid">
      <a href="mysql/init.php" style="text-decoration: unset;">
        <span class="navbar-text mb-0 h5">
          <i class="fas fa-database"></i>&emsp;
          <strong>COMP 440</strong>
        </span>
      </a>
    </div>
    <div class="d-grid gap-2 col-4 d-md-flex justify-content-md-end">
      <a href="#" class="btn btn-secondary me-md-2" data-bs-toggle="modal" data-bs-target="#signup" type="button" name="signup"><i class="fas fa-user-plus"></i>&ensp;<strong>Signup</strong></a>
      <a href="#" class="btn btn-primary me-md-2" data-bs-toggle="modal" data-bs-target="#login" type="button" name="login"><i class="fas fa-sign-in-alt"></i>&emsp;<strong> Log In </strong>&emsp;</a>
    </div>
  </nav>

  <!-- blogs login modal -->
  <div class="modal fade" id="login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="loginLabel">Login</h3>
          <button type="button" class="btn-close" id="loginClose" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="mb-3 loginFormInput">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" autocomplete="off" autofocus>
              <span></span>
            </div>
            <div class="mb-3 loginFormInput">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" autocomplete="off">
              <span></span>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="showpassword">
              <label class="form-check-label" for="showpassword">Show Password</label>
            </div>
            <div class="modal-footer">
              <div class="gap-3 col-6 mx-auto d-lg-block">
                <div class="mb-2 loginFormSubmit">
                  <span id="loginMessage"></span>
                </div><br>
                <button type="button" class="btn btn-secondary btn-lg" id="loginCancel" data-bs-dismiss="modal">
                  <strong>Close</strong>
                </button>
                <input type="submit" class="btn btn-primary btn-lg" name="submit" id="loginBtn" value="Log In">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- blogs signup modal -->
  <div class="modal fade" id="signup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="signupLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="signupLabel">Signup</h3>
          <button type="button" class="btn-close" id="signupClose" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="signupForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="mb-3 signupFormInput">
              <label for="newUsername" class="form-label">Username</label>
              <input type="text" class="form-control" id="newUsername" name="newUsername" autocomplete="off" autofocus>
              <span></span>
            </div>
            <div class="mb-3 signupFormInput">
              <label for="newPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="newPassword" name="newPassword" autocomplete="off">
              <span></span>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="showNewPassword">
              <label class="form-check-label" for="showNewPassword">Show Password</label>
            </div>
            <div class="mb-3 signupFormInput">
              <label for="confirmPassword" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" autocomplete="off">
              <span></span>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="showConfirmPassword">
              <label class="form-check-label" for="showConfirmPassword">Show Password</label>
            </div>
            <div class="mb-3 signupFormInput">
              <label for="firstname" class="form-label">First Name</label>
              <input type="text" class="form-control" id="firstname" name="firstname" autocomplete="off">
              <span></span>
            </div>
            <div class="mb-3 signupFormInput">
              <label for="lastname" class="form-label">First Name</label>
              <input type="text" class="form-control" id="lastname" name="lastname" autocomplete="off">
              <span></span>
            </div>
            <div class="mb-3 signupFormInput">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" autocomplete="off">
              <span></span>
            </div>
            <div class="modal-footer">
              <div class="gap-3 col-6 mx-auto d-lg-block">
                <div class="mb-3 signupFormSubmit">
                  <span id="signupMessage"></span>
                </div><br>
                <button type="button" class="btn btn-secondary btn-lg" id="signupCancel" data-bs-dismiss="modal">
                  <strong>Close</strong>
                </button>
                <input type="submit" class="btn btn-primary btn-lg" name="signupSubmit" value="Submit">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>