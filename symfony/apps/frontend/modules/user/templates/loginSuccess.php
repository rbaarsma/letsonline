<?php
/* timezone test
$ts = new DateTime("now", new DateTimeZone("UTC"));
echo $ts->format('Y-m-d H:i:s')."<br/>";
$ts->setTimeZone(new DateTimeZone("Europe/Amsterdam"));
echo $ts->format('Y-m-d H:i:s')."<br/>";
*/
?>

<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Login"),
  'action'  => url_for('user_do_login'),
  'submit'  => __("Login")
)) ?>