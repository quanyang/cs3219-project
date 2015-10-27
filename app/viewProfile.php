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

include_once('template/viewProfile.html');

include_once('template/footer.html');
?>

<!--  Scripts-->

<script src="js/materialize.js"></script>
<script src="js/init.js"></script>

</body>
</html>
