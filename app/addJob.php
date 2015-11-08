<?php
if(!session_id()) {
  session_start();
  date_default_timezone_set('Asia/Singapore');
}

if (!isset($_SESSION['name'])) {
  header("Location: login.php");
  die();
}

include_once('template/header.html');
include_once('template/nav.html');

include_once('template/add_job.html');

include_once('template/footer.html');
?>

<!--  Scripts-->
<script type="text/javascript">
$(document).ready(function() {
    $('jdtextarea').characterCounter();
    $('select').material_select();

    $('#add_job').submit(function(ev) {
      var url = "api/job"; // the script where you handle the form input.
      $.ajax({
        type: "POST",
        url: url,
        data: $("#add_job").serialize(), // serializes the form's elements.
        success: function(data){
          document.getElementById("add_job").reset();
          // Should redirect to job page
          $('#add_job .status').html("Job added!");
        },
        error: function(data){
          $('#add_job .error').html(data.responseJSON.Status);
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
