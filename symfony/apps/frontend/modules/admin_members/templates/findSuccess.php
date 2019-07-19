<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Change identity"),
  'action'  => url_for('admin_members/hack'),
  'submit'  => __("Login as this user")
)) ?>