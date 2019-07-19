<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Change Password"),
  'action'  => url_for('user_update', array('form_type'=>'password')),
  'submit'  => __("Save")
)) ?>