<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Add User"),
  'action'  => url_for('admin_members/create'),
  'submit'  => __("New")
)) ?>
