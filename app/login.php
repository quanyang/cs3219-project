<?php
if(!session_id()) {
  session_start();
  date_default_timezone_set('Asia/Singapore');
}

if (isset($_SESSION['name'])) {
  if (isset($_GET['logout'])) {
    session_destroy();
  // If it's desired to kill the session, also delete the session cookie.
  // Note: This will destroy the session, and not just the session data!
    if (ini_get('session.use_cookies')) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
        );
    }
  }

  header("Location: index.php");
  die();
}

include_once('template/header.html');
include_once('template/nav.html');
include_once('template/login.html');
include_once('template/footer.html');
?>
<!--  Scripts-->
<script type="text/javascript">
$(document).ready(function($) {
  $("#register").submit(function(ev) {

    $('#register .status').html("");
    $('#register .error').html("");

    var isPasswordEqual = $('#register #password').val() == $('#register #confirm-password').val();
    if (!isPasswordEqual) {
      $('#register .error').html("Passwords do not match.");
    }
    if (isPasswordEqual) {
          var url = "api/user"; // the script where you handle the form input.
          $.ajax({
            type: "POST",
            url: url,
            data: $("#register").serialize(), // serializes the form's elements.
            success: function(data){
              document.getElementById("register").reset();
              $('#register .status').html("Registration successful, you can login now!");
            },
            error: function(data){
              $('#register .error').html(data.responseJSON.Status);
            }
          });
        }
        ev.preventDefault();
  });
  $("#loginform").submit(function(ev) {
    $('#loginform .error').html("");
        var url = "api/user/login"; // the script where you handle the form input.
        $.ajax({
          type: "POST",
          url: url,
          data: $("#loginform").serialize(), // serializes the form's elements.
          success: function(data){
            document.getElementById("loginform").reset();
            $(location).attr('href', '/')
          },
          error: function(data){
            $('#loginform .error').html(data.responseJSON.Status);
          }
        });
        ev.preventDefault();
      });
});
</script>
<script src="js/materialize.js"></script>
<script src="js/init.js"></script>

</body>
</html>
