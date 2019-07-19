<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Send e-mail"),
  'action'  => url_for('user_email_send'),
  'submit'  => __("Send")
)) ?>