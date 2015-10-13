<?php
if(!session_id()) {
    session_start();
    date_default_timezone_set('Asia/Singapore');
}

include_once('template/header.html');
include_once('template/nav.html');

include_once('template/index.html');

include_once('template/footer.html');
?>

<!--  Scripts-->
<script src="js/materialize.js"></script>
<script src="js/init.js"></script>

</body>
</html>
